<?php

namespace App\Http\Controllers;

use App\Events\Upgrade;
use App\Exceptions\PayPostExceptionGenerateUrl;
use App\Facades\Balance;
use App\Facades\Hierarchy;
use App\Models\UserProgram;
use DB;
use Auth;
use Storage;
use PayPost;
use App\User;
use App\Models\Package;
use App\Models\Order;
use App\Models\Basket;
use App\Models\UserClients;
use Illuminate\Http\Request;
use App\Events\Activation;
use App\Events\ShopTurnover;

class PayController extends Controller
{

    public function payTypes(Request $request)
    {
        if(isset($request->package)) $package = Package::find($request->package);
        else $package = null;

        if (isset($request->upgrade)){
            $current_package = Package::find($request->upgrade);
            return view('processing.types-for-upgrade',compact('package','current_package'));
        }
        elseif (isset($request->basket)){

            $balance = Balance::getBalance(Auth::user()->id);

            $basket = Basket::find($request->basket);
            $all_cost = DB::table('basket_good')
                ->join('products','basket_good.good_id','=','products.id')
                ->where(['basket_id' => $basket->id])
                ->sum(DB::raw('basket_good.quantity*products.partner_cost'));//['products.*','basket_good.quantity']

            return view('processing.types-for-shop',compact('basket','all_cost','balance'));
        }
        else {
            return view('processing.types',compact('package'));
        }
    }

    public function payPrepare(Request $request)
    {
        $package_id = 0;
        if (isset($request->upgrade)){
            $package = Package::find($request->package);
            $package_id  = $package->id;
            $current_package = Package::find($request->upgrade);
            if (is_null($current_package))  $cost = $package->cost - 0;
            else $cost = Hierarchy::upgradeCost($current_package,$package, Auth::user());

            $order =  Order::updateOrCreate(
                [
                    'type' => 'upgrade',
                    'status' => 0,
                    'payment' => $request->type,
                    'uuid' => 0,
                    'user_id' => Auth::user()->id,
                ],
                ['amount' => $cost, 'package_id' => $package_id]
            );
        }
        elseif(isset($request->basket)){

            $cost = DB::table('basket_good')
                ->join('products','basket_good.good_id','=','products.id')
                ->where(['basket_id' => $request->basket])
                ->sum(DB::raw('basket_good.quantity*products.partner_cost'));//['products.*','basket_good.quantity']
            $firstly_cost = $cost;
            $cost = $cost + $cost*0.05;

            $order =  Order::updateOrCreate(
                [
                    'type' => 'shop',
                    'status' => 0,
                    'payment' => $request->type,
                    'uuid' => $firstly_cost,
                    'user_id' => Auth::user()->id,
                    'basket_id' => $request->basket
                ],
                ['amount' => $cost, 'package_id' => 0]
            );

        }
        else{
            if(!is_null($request->package)){
                $package = Package::find($request->package);
                $cost = $package->cost + $package->old_cost;
                $package_id  = $package->id;
            }
            else dd('Пакет не выбран');

            $order =  Order::updateOrCreate(
                [
                    'type' => 'register',
                    'status' => 0,
                    'payment' => $request->type,
                    'uuid' => 0,
                    'user_id' => Auth::user()->id,
                ],
                ['amount' => $cost, 'package_id' => $package_id]
            );

            $user = User::find(Auth::user()->id);
            $user->package_id = $package_id;
            $user->save();
        }


        $order_id = $order->id;
        $message = "Вы собираетесь оплатить $cost$";

        if($request->type == "manual"){
            return view('processing.manual', compact('order', 'cost'));
        }
        if($request->type == "paypost"){
            $payment_webhook = env('APP_URL', false) . "/pay-processing/$order_id/";

            try {
                $payPost = PayPost::generateUrl([
                    'amount' => $cost*config('marketing.dollar_course'),
                    //'amount' => 10,
                    'email' => Auth::user()->email,
                    'language' => 'ru',
                    'currency' => 'KZT',
                    'type' => 'card',
                    'payment_webhook' => $payment_webhook
                ]);
            } catch (\Exception $exception) {
                throw new PayPostExceptionGenerateUrl($exception->getMessage());
            }

            if ($payPost->success) {
                // todo white success instructions
                $paymentId = $payPost->result->payment;
                $paymentUrl = $payPost->result->url;

                Order::where('id',$order->id)->update([
                    'uuid' => $paymentId,
                ]);

                return redirect($paymentUrl);
            }
            else{
                dd("Что то пошло не так, уведовимте администратора сайта");
            }
        }
    }

    public function payProcessing(Request $request, $id)
    {

        $order = Order::find($id);
        $user_id = $order->user_id;

        if($order->payment == 'manual'){
            if ($request->hasFile('scan')) {
                $extension = $request->file('scan')->extension();
                $dir = 'public/scan/'.date('Y-m-d');
                $name = $id . '.' . $extension;
                $request->scan->storeAs($dir, $name);
                $path = "storage/scan/".date('Y-m-d').'/'.$name;

                Order::where('id', $id)
                    ->update(
                        [
                            'scan' => $path,
                            'status' => 11,
                        ]
                    );

                return redirect('/home')->with('status', 'Квитанция успешно отправлено');
            }

            return redirect()->back()->with('status', 'Вышла ошибка при оплате квитанции');
        }
        if($order->payment == 'indigo') {

            $body = $request->body;
            $signature = $request->signature;

            $mysignature = md5($body . config('pay.indigo_key'));

            if ($signature != $mysignature) return;

            $data = json_decode($body);
            if (!$data) return;

            if ($data->status != 'successful') return;

            $order->status = 4;
            $order->save();

            $uuid_order = Order::find($id);
            if ($uuid_order->type == 'shop') {
                Basket::whereId($uuid_order->basket_id)->update(['status' => 1]);
                $basket = Basket::find($uuid_order->basket_id);

                $order_pv = Order::join('baskets', 'baskets.id', '=', 'orders.basket_id')
                    ->join('basket_good', 'basket_good.basket_id', '=', 'baskets.id')
                    ->join('products', 'basket_good.good_id', '=', 'products.id')
                    ->where('orders.type', 'shop')
                    ->where('orders.basket_id', $basket->id)
                    ->where('orders.not_original', null)
                    ->groupBy('basket_good.good_id')
                    ->select([DB::raw('basket_good.quantity * products.pv as sum')])
                    ->get();

                $sum_pv = 0;
                foreach ($order_pv as $pv) {
                    $sum_pv += $pv->sum;
                }

                if ($sum_pv > 0) {
                    $data = [];
                    $data['pv'] = $sum_pv;
                    $data['user_id'] = $basket->user_id;

                    event(new ShopTurnover($data = $data));

                    return "<h2>Заказ успешно одобрен!</h2>";
                }
            }
            elseif ($uuid_order->type == 'upgrade') {

                event(new Upgrade($order = $order));
                return "<h2>Success upgraded!</h2>";
            }
            else {
                $user = User::find($uuid_order->user_id);

                if ($user->status == 1) {
                    Storage::disk('local')->prepend('/paypost_logs/' . date('Y-m-d'), "Пользователь уже активирован: $user->id");
                } else {
                    User::whereId($user->id)->update(['status' => 1]);
                    event(new Activation($user = $user));
                    Storage::disk('local')->prepend('/paypost_logs/' . date('Y-m-d'), "Пользователь успешно активирован: $user->id");
                }
            }
        }
        if($order->payment == 'paypost'){
            $check = PayPost::checkStatusPay("$order->uuid");

            if($check->success){

                $order = Order::where('uuid',$check->result->id)->first();
                $order_status = $order->status;
                $order
                    ->update([
                        'status' => $check->result->status,
                    ]);

                $uuid_order = Order::where('uuid',$check->result->id)->first();

                $user = User::find($uuid_order->user_id);

                if(($check->result->status == 4 or $check->result->status == 6) && !in_array($order_status, [4,6])) {
                    if($uuid_order->type == 'shop') {
                        Basket::whereId($uuid_order->basket_id)->update(['status' => 1]);
                        $basket = Basket::find($uuid_order->basket_id);

                        $order_pv = Order::join('baskets','baskets.id','=','orders.basket_id')
                            ->join('basket_good','basket_good.basket_id','=','baskets.id')
                            ->join('products','basket_good.good_id','=','products.id')
                            ->where('orders.type','shop')
                            ->where('orders.basket_id',$basket->id)
                            ->where('orders.not_original',null)
                            ->groupBy('basket_good.good_id')
                            ->select([DB::raw('basket_good.quantity * products.pv as sum')])
                            ->get();

                        $sum_pv = 0;
                        foreach ($order_pv as $pv){
                            $sum_pv +=$pv->sum;
                        }

                        Balance::changeBalance($basket->user_id,$order->amount*0.2,'cashback',$basket->user_id,1,1,1,$sum_pv);

                        if($sum_pv > 0) {

                            $data = [];
                            $data['pv'] = $sum_pv;
                            $data['user_id'] = $basket->user_id;

                            event(new ShopTurnover($data = $data));

                            return "<h2>Заказ успешно одобрен!</h2>";
                        }
                    }
                    elseif($uuid_order->type == 'upgrade'){

                        event(new Upgrade($order = $order));
                        return "<h2>Success upgraded!</h2>";
                    }
                    else{
                        if($user->status == 1) {
                            Storage::disk('local')->prepend('/paypost_logs/'.date('Y-m-d'),"Пользователь уже активирован: $user->id");
                        }
                        else{
                            User::whereId($user->id)->update(['status' => 1]);
                            event(new Activation($user = $user));
                            Storage::disk('local')->prepend('/paypost_logs/'.date('Y-m-d'),"Пользователь успешно активирован: $user->id");
                        }
                    }
                }
                else{
                    $success = $check->result->status;
                    Storage::disk('local')->prepend('/paypost_logs/'.date('Y-m-d'),"Ошибка оплаты с кодом: $success у пользователя: $user->id");
                }

                return redirect('/home');
            }
            else{
                Storage::disk('local')->prepend('/paypost_logs/'.date('Y-m-d'),'Pay Error 2');
            }
        }

    }

    public function payeer(Request $request)
    {
        if (!in_array($_SERVER['REMOTE_ADDR'], array('185.71.65.92', '185.71.65.189', '149.202.17.210'))) return 0;
        if (isset($_POST['m_operation_id']) && isset($_POST['m_sign']))
        {
            $m_key = 'G1UvTbE6370Q0Vj3';

            $arHash = array(
                $_POST['m_operation_id'],
                $_POST['m_operation_ps'],
                $_POST['m_operation_date'],
                $_POST['m_operation_pay_date'],
                $_POST['m_shop'],
                $_POST['m_orderid'],
                $_POST['m_amount'],
                $_POST['m_curr'],
                $_POST['m_desc'],
                $_POST['m_status']
            );

            if (isset($_POST['m_params']))
            {
                $arHash[] = $_POST['m_params'];
            }

            $arHash[] = $m_key;
            $sign_hash = strtoupper(hash('sha256', implode(':', $arHash)));

            if ($_POST['m_sign'] == $sign_hash && $_POST['m_status'] == 'success')
            {
                $order = Order::find($_POST['m_orderid']);
                Order::where('id',$_POST['m_orderid'])->update([
                    'status' => 1,
                ]);

                if ($order->type == 'shop') {
                    Basket::whereId($order->basket_id)->update(['status' => 1]);
                    $basket = Basket::find($order->basket_id);

                    $order_pv = Order::join('baskets', 'baskets.id', '=', 'orders.basket_id')
                        ->join('basket_good', 'basket_good.basket_id', '=', 'baskets.id')
                        ->join('products', 'basket_good.good_id', '=', 'products.id')
                        ->where('orders.type', 'shop')
                        ->where('orders.basket_id', $basket->id)
                        ->where('orders.not_original', null)
                        ->groupBy('basket_good.good_id')
                        ->select([DB::raw('basket_good.quantity * products.pv as sum')])
                        ->get();

                    $sum_pv = 0;
                    foreach ($order_pv as $pv) {
                        $sum_pv += $pv->sum;
                    }

                    if ($sum_pv > 0) {
                        $data = [];
                        $data['pv'] = $sum_pv;
                        $data['user_id'] = $basket->user_id;

                        event(new ShopTurnover($data = $data));

                        return "<h2>Заказ успешно одобрен!</h2>";
                    }
                } elseif ($order->type == 'upgrade') {

                    event(new Upgrade($order = $order));
                    return "<h2>Success upgraded!</h2>";
                } else {
                    $user = User::find($order->user_id);

                    if ($user->status == 1) {
                        Storage::disk('local')->prepend('/paypost_logs/' . date('Y-m-d'), "Пользователь уже активирован: $user->id");
                    } else {
                        User::whereId($user->id)->update(['status' => 1]);
                        event(new Activation($user = $user));
                        Storage::disk('local')->prepend('/paypost_logs/' . date('Y-m-d'), "Пользователь успешно активирован: $user->id");
                    }
                }
                ob_end_clean(); exit($_POST['m_orderid'].'|success');
            }
            ob_end_clean(); exit($_POST['m_orderid'].'|error');
        }
        else return 0;
    }
}
