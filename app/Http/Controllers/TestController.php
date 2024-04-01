<?php

namespace App\Http\Controllers;


use App\Facades\Balance;
use App\Facades\General;
use App\Models\Basket;
use App\Models\Counter;
use App\Models\FortuneWheel;
use App\Models\MonthlyOrderSum;
use App\Models\Order;
use App\Models\Program;
use App\Models\UserProgram;
use DB;
use Cache;
use File;
use App\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\Notification;
use App\Models\Processing;
use App\Models\Status;
use App\Models\Package;
use App\Facades\Hierarchy;
use App\Facades\Report;
use App\Events\Activation;
use App\Events\ShopTurnover;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\DocBlock\Description;

class TestController extends Controller
{

    public function tester()
    {


    }

    public function tester2()
    {
        $start = microtime(true);

        //Report::setMonthlyOrderSum();
        //Report::setMonthlyСommandPv();
        //echo Report::getMonthlyСommandPv(1);

        $turnover_bonuses =  Processing::whereIn('status', ['cashback','quickstart_bonus', 'invite_bonus', 'turnover_bonus'])//
        ->whereBetween('created_at', [Carbon::now()->startOfMonth()->subMonth()->addDays(1), Carbon::now()->subMonth()->endOfMonth()->addDays(1)])
            ->orderby('user_id','asc')
            ->groupBy('user_id')
            ->selectRaw('*, sum(sum) as sum')
            ->get();

        foreach ($turnover_bonuses as $k => $item) {
            $user_program = UserProgram::where('user_id', $item->user_id)->first();


            //echo $k.')'.$user_program->user_id.'== dec'.implode(",", Hierarchy::decompression($user_program->inviter_list,1,5))."<br>";
            //$for_list = $this->decompression($user_program->inviter_list, 1, 5);


            echo $k.') '.User::find($user_program->user_id)->id_number;

            echo '<br> Декомпрессия для структуры =>';

            foreach (Report::decompression($user_program->inviter_list, 1, 5) as $item){
                echo User::find($item)->id_number.",";
            }

            echo '<br> Декомпрессия для кумулятива =>';
            foreach (Report::decompressionForCumulative($user_program->inviter_list,1,5) as $item){
                echo User::find($item)->id_number.",";
            }

            echo '<br> ';


        }

        // Do some super awesome, well optimised programmer code nonsense

        // Grab the end time
        $end = microtime(true);

        // Subtract the start from the end
        $elapsed = $end - $start;

        echo "<br> Script executed in $elapsed seconds";
    }

    public function testAndCheckCumulative()
    {

        echo '<br>';
        $date = [];
        $innerItem = User::where('id_number',106228)->first()->id;
        $innerItem = User::where('id_number',102095)->first()->id;
        $innerItem = User::where('id_number',102094)->first()->id;
        $innerItem = User::where('id_number',102283)->first()->id;
        $innerItem = 3948;
        Hierarchy::pvCounterAll($innerItem,-1);

        $is_cumulative = 0;
        $sum_cumulative = 0;
        $invited_users = User::where('inviter_id', $innerItem)->get();
        $inner_user_program = UserProgram::where('user_id', $innerItem)->first();
        $item_status = Status::find($inner_user_program->status_id);
        $max = 0;

        foreach ($invited_users as $item_invited_users){

            $sum_pv = Hierarchy::pvCounterAll($item_invited_users->id,-1);
            echo $item_invited_users->id.')'.$item_invited_users->name." | ".$sum_pv.'<br>';
            if($sum_pv >= $item_status->matching_bonus) $is_cumulative++;
            $sum_cumulative += $sum_pv;

            if($max < $sum_pv) $max = $sum_pv;
        }

        $pv_from_register  = Counter::where('user_id',$innerItem)->whereBetween('created_at', [Carbon::parse('06/05/2023'), Carbon::parse('06/12/2023')])->sum('sum');
        //if($pv_from_register >= $item_status->matching_bonus) $is_cumulative++;
        //if($max < $pv_from_register) $max = $pv_from_register;

        $sum_own_pv = Hierarchy::orderSumOfMonth($date,$innerItem);
        if($sum_own_pv >= $item_status->matching_bonus) $is_cumulative++;
        if($max < $sum_own_pv) $max = $sum_own_pv;

        $total_pv = Hierarchy::pvCounterAll($innerItem,-1);
        $sumOfsum = $sum_cumulative+$sum_own_pv;


        echo "ID: ".$innerItem.'<br>';
        echo $inner_user_program->inviter_list.'<br>';
        echo "pv_from_register: ".$pv_from_register.'<br>';
        echo "own order: ".$sum_own_pv.'<br>';
        echo "sum of foreach: ".$sum_cumulative.'<br>';
        echo "<b>БОЛЬШАЯ ВЕТКА: </b>".$max.'<br>';
        echo "matching_bonus: ".$item_status->matching_bonus.'<br>';
        echo "plus: ".$sumOfsum.'<br>';
        echo "<b>КОМАНДНЫЙ ТО: </b>".$total_pv.'<br>';

        if($is_cumulative >= 2) echo "YES";
        else {
            $sum_minus_max = $total_pv - $max;
            if($max >= $item_status->matching_bonus && $sum_minus_max >= $item_status->matching_bonus) echo "YES";
            else echo "NO";
        }

    }

    public function ToCompare()
    {
        $to = [
'777,20',
'9098,20',
'104530,70',
'15693,20',
'33557,420',
'55575,20',
'71113,680',
'81494,20',
'28770A1,20',
'32222,20',
'1007,25,4',
'102416,200',
'103345,15',
'106478,60',
'234509,60',
'234591,20',
'106253,60',
'43883813,100',
'234580,30',
'102229,60',
'43993948,80.1',
'102659,12',
'102240,18',
'234539,131',
'102201,63',
'102317,60',
'102171,146',
'234557,108',
'101437,27',
'101292,12',
'102181,94.6',
'102173,51',
'102175,84',
'102326,30',
'102662,24',
'234584,70.3',
'102167,50',
'101439,36',
'101361,60',
'234577,105',
'102196,60',
'106148,33.5',
'85048,800',
'85082,400',
'105414,800',
'224489,463',
'234512,141',
'234552,60',
'78138,204',
'81570,200',
'97758,186.3',
'100354,110',
'100373,74',
'224469,202',
'234583,200',
'74041,210',
'234527,',
'2750,20',
'29233,20',
'415,20,',
'1,20',
'5984,268,1',
'8454,20',
'9228,115',
'45455,180',
'100975,220',
'100976,200',
'101415,140',
'102288,20',
'102283,140',
'102301,60',
'104503,20',
'106224,330',
'106239,105',
'106235,20',
'102593,123',
'106278,50',
'104520,20',
'100558,120',
'102094,20',
'102095,20',
'106228,50',
'48155,20',
'76961,20',
'76997,400',
'76998,400',
'78177,20',
'78193,20',
'85006,20',
'85014,20',
'85021,20',
'85022,35',
'85027,20',
'85030,420',
'85032,400',
'85035,30',
'85041,400',
'85067,80',
'85068,20',
'90441,15',
'105410,420',
'90489,20',
'78797,60',
'81469,20',
'81475,20',
'81507,410',
'81555,20',
'89825,800',
'100364,22',
'100386,310',
'100398,200',
'71779,400',
'78617,20',
'106264,120',
'106243,20',
'78200,58',
'85081,409',
'102566,500',
'52218,52',
'52785,72',
    ];

        $date = new \DateTime();
/*
dd(Hierarchy::orderSumOfMonth($date,3119));*/

        foreach ($to as $item){

            $pieces = explode(',',$item);
            $entered_user = User::where('id_number', $pieces[0])->first();

            if(round($pieces[1]) != round(Hierarchy::orderSumOfMonth($date,$entered_user->id))){
                echo "<br>========<br>";
                echo "ID:".$pieces[0]." | ".$entered_user->id."<br>";
                echo $pieces[1]."<br>".round(Hierarchy::orderSumOfMonth($date,$entered_user->id));
            }



        }
    }

    public function decompressionCompare()
    {

        /*$entered = User::where('id_number', 234591)->first();
        $user_program = UserProgram::where('user_id', $entered->id)->first();
        dd($user_program);
        dd(Hierarchy::decompression($user_program->inviter_list,1,5));*/

        $decompression = [
'1',
'9098,2750,1',
'104530,104503,9098,2750,1',
'15693,29233,1',
'33557,15693,29233,1',
'55575,32222,1',
'71113,29233,1',
'81494,55575,32222,1',
'28770A1,415,1',
'32222,29233,1',
'1007,1',
'102416,100975,8454,9098,2750,1',
'103345,9228,8454,9098,2750,1',
'106478,9228,8454,9098,2750,1',
'234509,9228,8454,9098,2750,1',
'234591,9228,8454,9098,2750,1',
'106253,104530,104503,9098,2750,1',
'43883813,102566,102095,102094,106228,106278',
'234580,102566,102095,102094,106228,106278',
'102229,106239,106235,106278,104530,104503',
'43993948,101415,102283,102566,102095,102094',
'102659,104530,104503,9098,2750,1',
'102240,102171,9098,2750,1',
'234539,106243,106239,106235,106278,104530',
'102201,104530,104503,9098,2750,1',
'102317,102301,102279,102566,102095,102094',
'102171,100558,104503,9098,2750,1',
'234557,234527,104530,104503,9098,2750',
'101437,104530,104503,9098,2750,1',
'101292,102283,102566,102095,102094,106228',
'102181,106224,104530,104503,9098,2750',
'102173,102317,102301,102566,102095,102094',
'102175,102171,100558,104503,9098,2750',
'102326,106224,104530,104503,9098,2750',
'102662,106114,106243,106239,106235,104530',
'234584,106224,104530,104503,9098,2750',
'102167,104530,104503,9098,2750,1',
'101439,106235,106278,104530,104503,9098',
'101361,106228,106278,104530,104503,9098',
'234577,234527,104530,104503,9098,2750',
'102196,106239,106235,106278,104530,104503',
'106148,106243,106239,106235,106278,104530',
'85048,90489,33557,15693,29233,1',
'85082,85014,15693,29233,1',
'105414,85021,15693,29233,1',
'224489,85006,33557,15693,29233,1',
'234512,85035,85006,33557,15693,29233',
'234552,81469,32222,29233,1',
'78138,55575,32222,1',
'81570,81469,32222,29233,1',
'97758,81469,32222,29233,1',
'100354,55575,32222,1',
'100373,100364,55575,32222,1',
'224469,100364,55575,32222,1',
'234583,100373,100364,55575,32222,1',
'74041,78617,32222,29233,1',
'234527,104530,104503,9098,2750,1',
'2750,1',
'29233,1',
'415,1',
'1',
'5984,9098,2750,1',
'8454,9098,2750,1',
'9228,8454,9098,2750,1',
'45455,1',
'100975,8454,9098,2750,1',
'100976,100975,8454,9098,2750,1',
'101415,102283,102566,102095,102094,106228',
'102288,102283,102566,102095,102094,106228',
'102283,102566,102095,102094,106228,106278',
'102301,102279,102566,102095,102094,106228',
'104503,9098,2750,1',
'106224,104530,104503,9098,2750,1',
'106239,106235,106278,104530,104503,9098',
'106235,104530,104503,9098,2750,1',
'102593,106114,106243,106239,106235,106278',
'106278,104530,104503,9098,2750,1',
'104520,104530,104503,9098,2750,1',
'100558,104503,9098,2750,1',
'102094,106228,106278,104530,104503,9098',
'102095,102094,106228,106278,104530,104503',
'106228,106278,104530,104503,9098,2750',
'48155,52218,52785,15693,29233,1',
'76961,15693,29233,1',
'76997,76961,15693,29233,1',
'76998,76961,15693,29233,1',
'78177,15693,29233,1',
'78193,33557,15693,29233,1',
'85006,33557,15693,29233,1',
'85014,15693,29233,1',
'85021,15693,29233,1',
'85022,85006,33557,15693,29233,1',
'85027,85022,85006,33557,15693,29233',
'85030,78177,15693,29233,1',
'85032,90489,33557,15693,29233,1',
'85035,85006,33557,15693,29233,1',
'85041,78177,15693,29233,1',
'85067,85006,33557,15693,29233,1',
'85068,33557,15693,29233,1',
'90441,52218,52785,15693,29233,1',
'105410,85021,31701,15693,29233,1',
'90489,33557,15693,29233,1',
'78797,29233,1',
'81469,32222,29233,1',
'81475,55575,32222,1',
'81507,81494,55575,32222,1',
'81555,55575,32222,1',
'89825,81475,55575,32222,1',
'100364,55575,32222,1,',
'100386,29233,1',
'100398,81475,55575,32222,1',
'71779,78617,32222,29233,1',
'78617,32222,29233,1',
'106264,104520,104530,104503,9098,2750',
'106243,106239,106235,106278,104530,104503',
'78200,52218,52785,15693,29233,1',
'85081,85014,15693,29233,1',
'102566,102095,102094,106228,106278,104530',
'52218,52785,15693,29233,1',
'52785,15693,29233,1',
            ];

        foreach ($decompression as $decompression_key => $decompression_item) {
            $pieces = explode(",", $decompression_item);
            $entered_user = User::where('id_number', $pieces[0])->first();
            $user_program = UserProgram::where('user_id', $entered_user->id)->first();

            //echo $decompression_item.'<br>';

            $checked_str = $entered_user->id_number;
            foreach (Hierarchy::decompression($user_program->inviter_list,1,5) as $key => $innerItem){
                $checked_str .= ",".User::find($innerItem)->id_number;
            }
            //echo $checked_str;

            if($decompression_item != $checked_str )
            {
                echo "<br>=================<br>";
                echo $decompression_item.'<br>';
                echo $checked_str;
            }
        }

    }

    public function testerLast()//
    {
        $sum = Hierarchy::totalOrderSumOfMonth();

        $percentage_for_masters = $sum*0.045;
        $masters_pv_sum = 0;

        $masters = UserProgram::whereIn('status_id',[6,7,8,9,10])->get();
        $bonused_masters = [];

        //расчет общего количество балов
        foreach ($masters as $master){
            if(Hierarchy::checkIsActive($master->user_id)){
                $balance =  Processing::whereIn('status', ['quickstart_bonus', 'invite_bonus', 'turnover_bonus', 'cashback', 'matching_bonus'])
                    ->whereBetween('created_at', [Carbon::parse('06/05/2023'), Carbon::now()])
                    ->where('user_id',$master->user_id)
                    ->orderby('id','asc')
                    ->sum('sum');

                if($balance >= 400){
                    echo User::find($master->user_id)->name."<br>";
                    $status_bonus = Status::find($master->status_id)->status_bonus;
                    $masters_pv_sum +=  ($balance/100) + $status_bonus;
                    $bonused_masters[] = [ $master->user_id, $balance, $status_bonus,$master->status_id];
                }
            }
        }

        //цена одного бала
        $point_cost = $percentage_for_masters/$masters_pv_sum;

        //начисление бонуса
        foreach ($bonused_masters as $master){

            $sum = $point_cost * (($master[1]/100) + $master[2]);
            echo $sum."<br>";

            Balance::changeBalance($master[0],   $sum, 'status_bonus', 1, 1, 1, $master[3], $point_cost,0,0);

        }

    }

    public function calculateWorldBonusForDirectors()//
    {
        $sum = Hierarchy::totalOrderSumOfMonth();

        $percentage_for_directors = $sum*0.015;
        $directors_pv_sum = 0;

        $directors = UserProgram::where('status_id',5)->get();
        $bonused_directors = [];


        //расчет общего количество балов
        foreach ($directors as $director){
            if(Hierarchy::checkIsActive($director->user_id)){
                $balance =  Processing::whereIn('status', ['quickstart_bonus', 'invite_bonus', 'turnover_bonus', 'cashback', 'matching_bonus'])
                    ->whereBetween('created_at', [Carbon::parse('06/05/2023'), Carbon::now()])
                    ->where('user_id',$director->user_id)
                    ->orderby('id','asc')
                    ->sum('sum');

                if($balance >= 400){
                    $bonused_directors[] = [ $director->user_id, $balance];
                    echo User::find($director->user_id)->name."<br>";
                    $directors_pv_sum +=  $balance/100;
                }
            }

        }

        //цена одного бала
        $point_cost = $percentage_for_directors/$directors_pv_sum;

        //начисление бонуса
        foreach ($bonused_directors as $director){

            $sum = $point_cost * $director[1]/100;
            echo $sum."<br>";
            Balance::changeBalance($director[0],   $sum, 'status_bonus', 1, 1, 1, 5, $point_cost,0,0);


        }

    }






    public function calculateCumulativeBonus()//
    {
        $list_percentage = array( 0 =>50,  1 =>20,  2 =>10,  3 =>5,  4 =>5 );
        $turnover_bonuses =  Processing::whereIn('status', ['cashback','quickstart_bonus', 'invite_bonus', 'turnover_bonus'])//
        ->whereBetween('created_at', [Carbon::parse('06/05/2023'), Carbon::now()])
            ->orderby('id','asc')
            ->get();

        foreach ($turnover_bonuses as $k => $item){
            $user_program = UserProgram::where('user_id', $item->user_id)->first();


            //echo $k.')'.$user_program->user_id.'== dec'.implode(",", Hierarchy::decompression($user_program->inviter_list,1,5))."<br>";
            $for_list = Hierarchy::decompression($user_program->inviter_list,1,5);
            foreach ($for_list as $key => $innerItem){

                //echo '>>>'.User::find($innerItem)->name.' ---'.$item->status.' =>';
                $inner_user_program = UserProgram::where('user_id', $innerItem)->first();
                $item_status = Status::find($inner_user_program->status_id);

                if($item_status->id >= 3){

                    if($item_status->depth_line >= ($key+1)){
                        //echo 'ok --> '.$item_status->depth_line.">=".($key+1);

                        $date = [];
                        $is_cumulative_count = 0;
                        $is_cumulative_status = 0;
                        $sum_cumulative = 0;
                        $max = 0;
                        $invited_users = User::where('inviter_id', $innerItem)->get();
                        foreach ($invited_users as $item_invited_users){
                            $sum_pv = Hierarchy::pvCounterAll($item_invited_users->id,-1);
                            if($sum_pv >= $item_status->matching_bonus) $is_cumulative_count++;

                            if($max < $sum_pv) $max = $sum_pv;
                            $sum_cumulative += $sum_pv;
                        }
                        $sum_own_pv = Hierarchy::orderSumOfMonth($date,$innerItem);
                        if($sum_own_pv >= $item_status->matching_bonus) $is_cumulative_count++;
                        if($max < $sum_own_pv) $max = $sum_own_pv;
                        $sumOfsum = $sum_cumulative+$sum_own_pv;


                        if($is_cumulative_count >= 2) $is_cumulative_status = 1;
                        else {
                            $sum_minus_max = $sumOfsum - $max;
                            if($sum_minus_max >= $item_status->matching_bonus) $is_cumulative_status = 1;
                            else $is_cumulative_status = 0;
                        }

                        $is_cumulative_status_after_check = 0;
                        if($is_cumulative_status >= 2) $is_cumulative_status_after_check = 1;
                        else {
                            $total_pv = Hierarchy::pvCounterAll($innerItem,-1);
                            $sum_minus_max = $total_pv - $max;
                            if($sum_minus_max >= $item_status->matching_bonus) $is_cumulative_status_after_check = 1;
                            else $is_cumulative_status_after_check = 0;
                        }

                        if($is_cumulative_status_after_check == 1){
                            $sum = $item->sum*$list_percentage[$key]/100;

                            Balance::changeBalance($innerItem,   $sum, 'matching_bonus', $item->user_id, $user_program->program_id,$user_program->package_id, $user_program->status_id, $item->sum,0,($key+1));

                        }
                    }

                }
                //echo "<br>";
            }

        }
    }

    public function calculateStructureBonus()//
    {
        $users = User::where('created_at','>=', Carbon::parse('06/05/2023'))->where('status',1)->get();

        foreach ($users as $item){
            $id = $item->id;
            $inviter = User::find($item->inviter_id);
            $package = Package::find($item->package_id);
            $program = Program::find($item->program_id);
            $inviter_program = UserProgram::where('user_id',$inviter->id)->first();
            $inviter_status = Status::find($inviter_program->status_id);
            $inviter_list = Hierarchy::getInviterList( $item->id,'').',';

            Hierarchy::setStructureBonus($inviter_list,$package,$id,$program);

            echo $item->id."=>".$item->package_id."=>".$item->name."<br>";
        }

    }

    public function calculatePassiveAndCashbackBonus()//
    {

        $orders = Order::whereBetween('created_at',[Carbon::now()->subDays(10), Carbon::now()->subMonth()->endOfMonth()] )->where('type','!=','upgrade')->where('status',4)->get();

        foreach ($orders as $item){

            $basket  = Basket::find($item->basket_id);

            if(is_null($basket)) dd($item);
            $user_program = UserProgram::where('user_id',$basket->user_id)->first();

            $order_pv = Order::join('baskets','baskets.id','=','orders.basket_id')
                ->join('basket_good','basket_good.basket_id','=','baskets.id')
                ->join('products','basket_good.good_id','=','products.id')
                ->where('orders.type','shop')
                ->where('orders.basket_id',$item->basket_id)
                ->where('orders.not_original',null)
                ->groupBy('basket_good.good_id')
                ->select([DB::raw('basket_good.quantity * products.pv as sum')])
                ->get();

            $sum_pv = 0;
            foreach ($order_pv as $pv){
                $sum_pv +=$pv->sum;
            }

            $order = Order::where( 'type','shop')
                ->where('basket_id',$item->basket_id)
                ->where('status' ,4)
                ->first();

            Balance::changeBalance($basket->user_id,$order->uuid*0.2,'cashback',$basket->user_id,1,$user_program->package_id,$user_program->status_id,$sum_pv);

            if($order->uuid > 0) {
                $data = [];
                $data['user_id'] = $basket->user_id;
                $data['sum'] = $order->uuid;

                event(new ShopTurnover($data = $data));

                $user = User::find($basket->user_id);

                Notification::create([
                    'user_id'   => Auth::user()->id,
                    'type'      => 'admin_buy_user',
                    'message'   => 'Подтверждение покупки пользователя ' . $user->name . ' ( ' . $user->id . ' ) ',
                ]);

            }
        }
    }

    public function calculateInviteBonus()//
    {
        $users = User::whereBetween('created_at',[Carbon::now()->subDays(10), Carbon::now()->subMonth()->endOfMonth()])->where('status',1)->get();

        foreach ($users as $item){
            $id = $item->id;
            $inviter = User::find($item->inviter_id);
            $package = Package::find($item->package_id);
            $program = Program::find($item->program_id);
            $inviter_program = UserProgram::where('user_id',$inviter->id)->first();
            $inviter_status = Status::find($inviter_program->status_id);
            $inviter_list = Hierarchy::getInviterList($id,'').',';


            //Hierarchy::setInviterBonus($inviter,$package,$id,$program,$inviter_status);
            Hierarchy::setStructureBonus($inviter_list,$package,$id,$program);

            echo $item->id."=>".$item->package_id."=>".$item->name."<br>";
        }

    }






    public function checkAndSetActivationSecond()//
    {

        $users = UserProgram::where('status_id','<',3)
            ->whereBetween('created_at', [Carbon::parse('06/01/2023')->subMonth(7), Carbon::parse('06/12/2023')])
            //->take(10)
            ->get();

        foreach ($users as $key => $item){
            echo $key.") ".$item->status_id." |=> ".$item->user_id." |=> ".$item->created_at."<br>";

            $activation_item = DB::table('activations')
                ->where('user_id',$item->user_id)
                ->where('month',5)
                ->update([
                    'status' => 1
                ]);
        }

        //dd($users);
    }

    public function checkAndSetActivation()//
    {
        $list = [
71779,
1228,
44554,
55867,
44594,
77418,
77295,
54188,
98900,
1202,
106029,
17327,
22237,
35421,
1471,
1500,
4141,
87508,
24364,
51560,
77308,
84822,
88417,
84824,
13934,
29383,
15693,
33557,
85006,
90489,
52785,
78177,
85014,
52218,
76961,
85027,
85021,
85035,
85022,
224489,
32222,
78617,
29233,
55575,
81475,
81494,
81469,
100364,
81555,
96104,
'4745A1',
29071,
29077,
104503,
104530,
106224,
102095,
102094,
106239,
106278,
106228,
101415,
102288,
102283,
102566,
'43367A1',
1,
777,
9098,
'9228A1',
9228,
5984,
9750,
2750,
        ];

        /*$activation = DB::table('activations')->where('month',4)->where('status',1)->get();

        $count = 0;
        foreach ($activation as $item){
            $user = User::find($item->user_id);
            if (array_search($user->id_number, $list) === false){
                $activation_item = DB::table('activations')->where('id',$item->id)
                    ->update([
                        'status' => 0
                    ]);
            }
        }
        echo $count;*/

        foreach ($list as $key => $item){
            $user = User::where('id_number',$item)->first();
            $activation = DB::table('activations')->where('month',5)->where('user_id',$user->id)->first();
            echo $key.') '.$user->id.":".$item."=>".$activation->status."<br>";

            DB::table('activations')->where('user_id',$user->id)
                ->update([
                    'sum' => 20,
                    'status' => 1
                ]);
        }


    }

    public function testerExport()
    {
        $json = File::get("users_new.json");
        $todos = json_decode($json);

        //check isset user
        /*foreach ($todos as $key => $value) {
            $isset_user = User::where('id_number',$value->login)->first();
            if(is_null($isset_user)) dd($value);
            else User::whereId($isset_user->id)->update(['type' => 1]);
        }*/

        //contain status and change difference
        /*foreach ($todos as $key => $value) {
            $isset_user = User::where('id_number',$value->login)->first();

            $inviter_program = UserProgram::where('user_id',$isset_user->id)->first();
            $inviter_status = Status::find($inviter_program->status_id);

            if($value->status != $inviter_status->title) {
                echo $value->login." $value->status <- $inviter_status->title <br>";

                $status = Status::where('title',$value->status)->first();

                $inviter_program->status_id = $status->id;
                $inviter_program->save();

            }
        }*/

        // check PV and set PV
        /*foreach ($todos as $key => $value) {

            // do table truncate
            $isset_user = User::where('id_number',$value->login)->first();

           Processing::create([
                'status' => 'admin_add',
                'sum' => $value->pv,
                'in_user' => 0,
                'user_id' => $isset_user->id,
                'program_id' => $isset_user->program_id,
                'created_at' => Carbon::now()->subMonth()->format('Y-m-d H:i:s'),
                'status_id' => 1,
                'package_id' => $isset_user->package_id,
                'card_number' => $isset_user->card
            ]);

            Processing::create([
                'status' => 'out',
                'sum' => $value->pv,
                'in_user' => 0,
                'user_id' => $isset_user->id,
                'program_id' => $isset_user->program_id,
                'created_at' => Carbon::now()->subMonth()->format('Y-m-d H:i:s'),
                'status_id' => 1,
                'package_id' => $isset_user->package_id,
                'card_number' => $isset_user->card
            ]);
        }*/

        // check activation
        /*foreach ($todos as $key => $value) {
            $isset_user = User::where('id_number',$value->login)->first();


            if(!Hierarchy::checkIsActive($isset_user->id) and isset($value->activation)) {
                echo $key.") "; echo $value->login."<br>";

                $date = new \DateTime();
                $date->modify('-1 month');

                DB::table('activations')->updateOrInsert(
                    [
                        'user_id' => $isset_user->id,
                        'month' => Carbon::parse($date)->month,
                        'year' => Carbon::parse($date)->year,
                        'status' => 1
                    ],
                    [
                        'user_id' => $isset_user->id,
                        'month' => Carbon::parse($date)->month,
                        'year' => Carbon::parse($date)->year,
                        'sum' => 20,
                        'status' => 1
                    ]
                );
            }

        }*/

    }

    public function calculatePassiveBonus()//
    {
        dd('Пассивный бонус уже сидить в Кашбеке');
        $users = \App\User::join('activations', 'users.id', '=', 'activations.user_id')
            ->where('activations.month',4)
            ->where('activations.status',1)
            ->where('activations.sum',20)
            ->get(['activations.*']);

        foreach ($users as $item){

            $id = $item->user_id;
            $inviter_list = Hierarchy::getInviterList( $item->user_id,'').',';
            $user_program = UserProgram::where('user_id',$item->user_id)->first();
            $package = Package::find($user_program->package_id);
            $program = Program::find($user_program->program_id);

            Hierarchy::setPassiveBonus($inviter_list,$package,$id,$program, 20);

            echo $id."=>".$user_program->package_id."<br>";
        }


    }

    public function termination()
    {

        $users = UserProgram::where('inviter_list','like','%,3,%')->get();

        foreach ($users as $item){

            $inviter_list2 = str_replace(',3,1,',',1,', $item->inviter_list);
            echo $inviter_list2.'<='.$item->inviter_list."<br>";

            $item->inviter_list = $inviter_list2;
            $item->save();

        }

       /* $users = UserProgram::where('inviter_list','like','%,52,%')->get();

        foreach ($users as $item){
            //dd($item);
            echo $item->inviter_list.'<br>';
            $pattern = '/,52/i';
            echo preg_replace($pattern, '', $item->inviter_list).'<br>';
            echo '===<br>';

            UserProgram::where('id', $item->id)
                ->update([
                    'inviter_list' => preg_replace($pattern, '', $item->inviter_list)
                ]);
        }*/

    }


    public function testerOld()
    {
        $users = User::where('id','!=',1)->get();
        $count = 0;
        foreach ($users as $key => $item){
            $temp = DB::table('users_tempp')->where('login',$item->id_number)->first();
            $inviter_program = UserProgram::where('user_id',$item->id)->first();
            $inviter_status = Status::find($inviter_program->status_id);


            //if(Balance::getIncomeBalance($user->id) > $temp->pv){
                $count++;
                echo "PV:".Balance::getIncomeBalance($item->id)." | ".$item->name." | ID:".$item->id_number." | ".$inviter_status->title." | ".$item->created_at." Kazakhstan data<br>";
                echo "PV:".$temp->pv."                          | ".$temp->name." | ID:".$temp->login."     | ".$temp->status."          | ".$temp->created_at." Chine data<br>";
                echo "======================<br>";
            //}
        }
        echo "   $count++;";
    }

    public function setBots()
    {
        for ($i = 0; $i < 10000; $i++){

            $all_users = User::whereNull('is_office_lider')->get();

            foreach ($all_users as $item){

                $listeners_count = User::where('sponsor_id',$item->id)->count();

                if($listeners_count == 0){
                    User::create([
                        'name'          => "1 name ".$item->id,
                        'number'        => "870170889".$item->id,
                        'email'         => "1mail@com.kz".$item->id,
                        'gender'        => 1,
                        'birthday'      => "04.04.20",
                        'address'       => "address",
                        'password'      => '$2y$10$VEeAZGJdX3ge9FEP3gDXn.6bxBlluFu49n2dTVfDSvKn35uBEoCxe',
                        'created_at'    => "2020-02-01 07:39:39",
                        'country_id'    => 1,
                        'city_id'       => 1,
                        'inviter_id'    => $item->id,
                        'sponsor_id'    => $item->id,
                        'position'      => 1,
                        'package_id'    => 3,
                        'program_id'    => 1,
                    ]);


                    User::create([
                        'name'          => "2 name ".$item->id,
                        'number'        => "870170889".$item->id,
                        'email'         => "2mail@com.kz".$item->id,
                        'gender'        => 1,
                        'birthday'      => "04.04.20",
                        'address'       => "address",
                        'password'      => '$2y$10$VEeAZGJdX3ge9FEP3gDXn.6bxBlluFu49n2dTVfDSvKn35uBEoCxe',
                        'created_at'    => "2020-02-01 07:39:39",
                        'country_id'    => 1,
                        'city_id'       => 1,
                        'inviter_id'    => $item->id,
                        'sponsor_id'    => $item->id,
                        'position'      => 2,
                        'package_id'    => 3,
                        'program_id'    => 1,
                    ]);


                    if($item->id == 5000) dd($item->id);

                    $item->is_office_lider = 1;
                    $item->save();

                }

            }
        }


    }


    public function testerExportPackage()
    {
        $users_tempp = DB::table('users_tempp')->get();

        foreach ($users_tempp as $key => $value) {
            $user = DB::table('users')->where('id_number', $value->login)->first();

            $data = Processing::create([
                'status' => 'out',
                'sum' => $value->pv,
                'in_user' => 0,
                'user_id' => $user->id,
                'program_id' => $user->program_id,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'status_id' => 1,
                'package_id' => $user->package_id,
                'card_number' => $user->card
            ]);

        }
    }




    public function tester3()
    {
        $users_tempp = DB::table('users_tempp')->get();
        $users_string = '';

        foreach ($users_tempp as $item){

               $checker =  DB::table('users_tempp')->where('login',$item->sponsor)->first();
               if(is_null($checker))  $users_string .= $item->name."($item->login|$item->sponsor), ";
        }


        dd($users_string);
    }


    public function delete()
    {
        $id = $_GET['id'];
        UserProgram::where('user_id',$id)->delete();
        Processing::where('user_id',$id)->delete();
        Counter::where('user_id',$id)->delete();
        User::find($id)->delete();

    }

    public function testerActivation()
    {

        $users = User::where('id','!=',1)->where('status',0)->orderBy('id')->get();

        foreach ($users as $item){
            event(new Activation($user = $item));
        }

    }

    public function changeStatusesPercentage()
    {
        dd(0);
        $list = DB::select('SELECT * FROM `processing` WHERE `created_at` >= \'2019-11-01 00:00:00\' AND `status` != \'register\'');

        foreach ($list as $item){
            $inviter_status = Status::find($item->status_id);
            $package = Package::find($item->package_id);

            if($item->status == 'sponsor_bonus'){
                Processing::where('user_id',$item->user_id)
                    ->where('sum',$item->sum)
                    ->where('status',$item->status)
                    ->where('program_id',$item->program_id)
                    ->where('package_id',$item->package_id)
                    ->where('status_id',$item->status_id)
                    ->where('created_at',$item->created_at)
                    ->update(['sum' => $package->bv*$inviter_status->sponsor_bonus/100*1]);
            }
            if($item->status == 'partner_bonus'){
                Processing::where('user_id',$item->user_id)
                    ->where('sum',$item->sum)
                    ->where('status',$item->status)
                    ->where('program_id',$item->program_id)
                    ->where('package_id',$item->package_id)
                    ->where('status_id',$item->status_id)
                    ->where('created_at',$item->created_at)
                    ->update(['sum' => $package->bv*$inviter_status->partner_bonus/100*1]);
            }
        }
    }



    // Export from excel


    public function setBotsExcel()
    {

        while (DB::table('users_tempp')->where('activated',0)->count() > 0) {

            $user = DB::table('users_tempp')->where('activated',0)->orderBy('created_at','asc')->orderBy('id','asc')->first();

            $sponsor = User::where('id_number',$user->sponsor)->first();

            if(is_null($sponsor)){
                $this->sponsorNotFound($user);
            }
            else{
                $this->createAndActivate($user,$sponsor);
                DB::table('users_tempp')
                    ->where('id', $user->id)
                    ->update(['activated' => 1]);

                $sponsor_users = DB::table('users_tempp')->where('activated',0)->where('sponsor',$sponsor->id_number)->orderBy('created_at','asc')->get();
                foreach ($sponsor_users as $item){

                    $this->createAndActivate($item,$sponsor);
                    DB::table('users_tempp')
                        ->where('id', $item->id)
                        ->update(['activated' => 1]);
                }
            }

        }

    }

    public function createAndActivate($user,$sponsor)
    {

        try {
            $created = User::create([
                'name'          => $user->name,
                'number'        => "870170889".$user->id,
                'email'         => "2mail@com.kz".$user->id,
                'gender'        => 1,
                'birthday'      => "04.04.20",
                'address'       => "address",
                'password'      => '$2y$10$VEeAZGJdX3ge9FEP3gDXn.6bxBlluFu49n2dTVfDSvKn35uBEoCxe',
                'created_at'    => "2020-02-01 07:39:39",
                'country_id'    => 1,
                'city_id'       => 1,
                'inviter_id'    => $sponsor->id,
                'sponsor_id'    => 0,
                'position'      => 1,
                'package_id'    => 1,
                'program_id'    => 1,
                'id_number'     => $user->login,
            ]);

            event(new Activation($user = $created));
        } catch (Exception $e) {
            dd($user);
        }



    }

    public function sponsorNotFound($user)
    {

        $not_found_sponsor_user = DB::table('users_tempp')->where('login',$user->sponsor)->first();
        $sponsor_from_not_found_sponsor = User::where('id_number',$not_found_sponsor_user->sponsor)->first();

        if(is_null($sponsor_from_not_found_sponsor)){

            $this->sponsorNotFound($not_found_sponsor_user);
        }

        $this->createAndActivate($not_found_sponsor_user,$sponsor_from_not_found_sponsor);
        DB::table('users_tempp')
            ->where('id', $not_found_sponsor_user->id)
            ->update(['activated' => 1]);

        $sponsor_users = DB::table('users_tempp')->where('activated',0)->where('sponsor',$sponsor_from_not_found_sponsor->id_number)->orderBy('created_at','asc')->get();
        foreach ($sponsor_users as $item){

            $this->createAndActivate($item,$sponsor_from_not_found_sponsor);
            DB::table('users_tempp')
                ->where('id', $item->id)
                ->update(['activated' => 1]);
        }
    }
}
