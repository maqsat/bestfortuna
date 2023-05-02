<?php

namespace App\Helpers;

use App\Events\ShopTurnover;

use App\Models\City;
use DB;
use Auth;
use App\User;
use Carbon\Carbon;
use App\Models\Basket;
use App\Models\Status;
use App\Facades\Balance;

use App\Models\Program;
use App\Models\Counter;
use App\Models\Processing;
use App\Models\Notification;
use App\Models\UserProgram;
use App\Models\UserSubscriber;
use App\Models\Order;
use App\Models\Package;
use App\Models\Revitalization;
use Illuminate\Support\Facades\Storage;

class Hierarchy {

//Carbon::parse('04/04/2023')
//Carbon::parse('05/03/2023')

    public $sponsor_id;


    /*************************** New METHODS for BEST FORTUNA ****************************/

    /***************************
     *  Данные
     ***************************
     */

    //Товароборот
    public function pvCounterAll($user_id, $date_status = 0)
    {
        $date = new \DateTime();
        if($date_status == -1){
            $date->modify('-1 month');
        }

        $pv_from_register  = Counter::where('user_id',$user_id)->whereBetween('created_at', [Carbon::parse('04/04/2023'), Carbon::parse('05/03/2023')])->sum('sum');

        $pv_from_own_shop = $this->orderSumOfMonth($date,$user_id);;

        $pv_from_inviters_shop = 0;
        $inviters_list = $this->inviterList($user_id);
        foreach ($inviters_list as $item){
            $pv_from_inviters_shop += $this->orderSumOfMonth($date,$item->user_id);
        }

        return $pv_from_register + $pv_from_own_shop + $pv_from_inviters_shop;
    }

    //Список команды
    public function inviterList($user_id)
    {
        $list = UserProgram::where('inviter_list','like','%,'.$user_id.',%')->get();
        return $list;
    }

    //Количество в команде команды
    public function inviterCount($user_id)
    {
        return User::where('inviter_id',$user_id)->count();
    }

    public function teamCount($user_id)
    {
        return UserProgram::where('inviter_list','like','%,'.$user_id.',%')->count();
    }

    //Товароборот для мировго бонуса
    public function pvCounterForWorldBonus($date_status = 0)
    {
        $date = new \DateTime();
        if($date_status == -1){
            $date->modify('-1 month');
        }

        $pv_from_register  = Counter::whereBetween('created_at', [Carbon::parse('04/04/2023'), Carbon::parse('05/03/2023')])->sum('sum');
        $pv_from_own_shop = Order::whereBetween('created_at', [Carbon::parse('04/04/2023'), Carbon::parse('05/03/2023')])
            ->where('type','shop')
            ->where(function($query){
                $query->where('status',4)
                    ->orWhere('status',6);
            })
            ->sum('amount');

        return $pv_from_register + $pv_from_own_shop;
    }

    //Сумма заказов за месяц
    public function orderSumOfMonth($date,$user_id, $date_status = 0)
    {
        $sum = Order::whereBetween('created_at', [Carbon::parse('04/04/2023'), Carbon::parse('05/03/2023')])
            ->where('type','shop')
            ->where('user_id', $user_id)
            ->where(function($query){
                $query->where('status',4)
                    ->orWhere('status',6);
            })
            ->sum('uuid');

        return $sum;
    }

    //Проверка активен ли пользователь
    public function checkIsActive($user_id)
    {
        if($user_id == 1)  return true;

        $date = new \DateTime();
        $date->modify('-1 month');

        $activation_status = DB::table('activations')
            ->where('user_id',$user_id)
            ->where('month',Carbon::parse($date)->month)
            ->where('year',Carbon::parse($date)->year)
            ->where('status',1)
            ->first();

        if(!is_null($activation_status)) return true;
        else{
            $user_program = UserProgram::where('user_id',$user_id)->first();

            if(!is_null($user_program)){
                if($user_program->status_id >= 3) return false;
                else {
                    $user = User::find($user_id);
                    $activation_start_date = Balance::getActivationStartDate($user->created_at, $user_id);
                    $activation_start_date = Carbon::parse($activation_start_date);
                    $now = Carbon::parse($date);

                    if($now->lte($activation_start_date)){ // less than or equals
                        return true;
                    }
                    else{
                        return false;
                    }

                }
            }

        }
    }

    //Декомпрессия
    public function decompression($list, $check_inviter, $length)
    {
        $list = explode(",", trim($list,','));
        $new_list = [];

        foreach ($list as $key => $item)
        {

            if( $key === 0 && $check_inviter === 0 )   continue;
            else{

                if($this->checkIsActive($item)){
                    $new_list[] = $item;
                }

                if(count($new_list) == $length) break;
            }

        }

        return $new_list;

    }

    //Цена апгрейде
    public function upgradeCost($current_package, $package, $user, $only_cost = 0)
    {
        $created_at = Carbon::parse($user->created_at);

        $date = new \DateTime();
        $now = Carbon::parse($date)->format('m');


        if($created_at->format('m') == $now){
            if($only_cost == 0)
                return  ($package->cost+$package->old_cost) - ($current_package->cost+$current_package->old_cost);
            else  return  $package->cost - $current_package->cost;
        }
        else{
            if($only_cost == 0)
                return  $package->cost+$package->old_cost;
            else  return  $package->cost;
        }

    }

    //PV при апгрейде
    public function upgradeCostPv($current_package, $package, $user)
    {
        $created_at = Carbon::parse($user->created_at);

        $date = new \DateTime();
        $now = Carbon::parse($date)->format('m');


        if($created_at->format('m') == $now){
            return  $package->pv-$current_package->pv;
        }
        else{
            return $package->pv;
        }

    }

    public function getBenefit($id)
    {
        if(is_null($id)){
            $benefit = DB::table('benefits')->where('id',8)->first();
        }
        else{
            $benefit = DB::table('benefits')->where('id',$id)->first();
        }

        return $benefit;
    }

    public function getBenefitPercentage($user_id)
    {
        $user = User::find($user_id);

        if(is_null($user->benefit)){
            $benefit = DB::table('benefits')->where('id',8)->first();

        }
        else{
            $date1 = new \DateTime($user->benefit_time);
            $date2 = new \DateTime();


            if($date1 >= $date2)
                $benefit = DB::table('benefits')->where('id',$user->benefit)->first();
            else {
                $benefit = DB::table('benefits')->where('id',8)->first();
            }
        }

        return $benefit->pension_payments + $benefit->health_insurance + $benefit->ipn;
    }


    //Название статуса
    public function getStatusName($id)
    {
        $user_program = UserProgram::where('user_id',$id)->first();
        $status = Status::find($user_program->status_id);

        return $status->title;
    }

    /***************************
     *  Процессы
     ***************************
     */

    //Кумулятивный бонус//  через крон
    public function cumulativeCalculation()
    {
        $users = UserProgram::where('status_id','>=',2)->get();
        $date = new \DateTime();
        $date->modify('-1 month');
        $list_percentage = array( 1 =>50,  2 =>20,  3 =>10,  4 =>5,  5 =>5 );

        foreach ($users as $item){

            if($this->checkIsActive($item->id)){

                $item_status = Status::find($item->status_id);

                if(\App\Facades\Hierarchy::pvCounterAll($item->id,-1) >= $item_status->matching_bonus*2){

                    for ($i = 1; $i <= $item_status->depth_line; $i++){
                        $sums = 0;
                        $level_users = UserProgram::where('inviter_list','like','%,'.$item->id.',%')->whereLevel($item->level+$i)->get();

                        foreach ($level_users as $item_user){
                            $sums += Processing::whereUserId($item_user->user_id)
                                ->where('status', 'turnover_bonus')
                                ->whereBetween('created_at', [Carbon::parse('04/04/2023'), Carbon::parse('05/03/2023')])
                                ->sum('sum');
                        }

                        $sum = $sums*$list_percentage[$i]/100;

                        if($sum > 0)
                            Balance::changeBalance($item->id,   $sum, 'matching_bonus', $item->id, $item->program_id,$item->package_id, $item->status_id, $sums,0,$i);

                    }

                }
            }

        }

        $message = "Зачислен ежемесячный Кумулятивный бонус";
        $ch = curl_init("https://api.telegram.org/bot338084061:AAEf5s-TegdOIQB8Akx0yj82v18ZyJ07XwI/sendMessage?chat_id=-890158682&text=$message");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //---curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_exec($ch);
        curl_close($ch);

    }

    //Мировой Бонус Директоров//  через крон
    public function cumulativeWorldBonusForDirectors()
    {
        $percentage_for_directors = $this->pvCounterForWorldBonus(-1)*0.015;
        $directors_pv_sum = 0;

        $directors = UserProgram::where('status_id',5)->get();

        //расчет общего количество балов
        foreach ($directors as $director){
            if($this->checkIsActive($director->user_id)){
                $balance = Balance::getIncomePrevMonthBalance($director->user_id);
                if($balance >= 400){
                    $directors_pv_sum +=  $balance/100;
                }
            }

        }

        //цена одного бала
        $point_cost = $percentage_for_directors/$directors_pv_sum;

        //начисление бонуса
        foreach ($directors as $director){
            if($this->checkIsActive($director->user_id)){
                $balance = Balance::getIncomePrevMonthBalance($director->user_id);
                if($balance >= 400){
                    $sum = $point_cost * $balance/100;
                    Balance::changeBalance($director->user_id,   $sum, 'status_bonus', 1, 1, 1, 5, $point_cost,0,0);
                }
            }
        }

        $message = "Зачислен ежемесячный Мировой Бонус Директоров";
        $ch = curl_init("https://api.telegram.org/bot338084061:AAEf5s-TegdOIQB8Akx0yj82v18ZyJ07XwI/sendMessage?chat_id=-890158682&text=$message");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //---curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_exec($ch);
        curl_close($ch);
    }

    //Мировой Бонус Мастеров//  через крон
    public function cumulativeWorldBonusForMasters()
    {
        $percentage_for_masters = $this->pvCounterForWorldBonus(-1)*0.045;
        $masters_pv_sum = 0;

        $masters = UserProgram::whereIn('status_id',[6,7,8,9,10])->get();

        //расчет общего количество балов
        foreach ($masters as $master){
            $balance = Balance::getIncomePrevMonthBalance($master->user_id);
            if($balance >= 400){//

                $masters_pv_sum += $balance/100 + Status::find($master->status_id)->status_bonus;
            }
        }

        //цена одного бала
        $point_cost = $percentage_for_masters/$masters_pv_sum;

        //начисление бонуса
        foreach ($masters as $master){
            $balance = Balance::getIncomePrevMonthBalance($master->user_id);
            if($balance >= 400){//
                $sum = $point_cost * ($balance/100 + Status::find($master->status_id)->status_bonus);
                Balance::changeBalance($master->user_id,   $sum, 'status_bonus', 1, 1, 1, $master->status_id, $point_cost,0,0);
            }
        }

        $message = "Зачислен ежемесячный Мировой Бонус Мастеров";
        $ch = curl_init("https://api.telegram.org/bot338084061:AAEf5s-TegdOIQB8Akx0yj82v18ZyJ07XwI/sendMessage?chat_id=-890158682&text=$message");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //---curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_exec($ch);
        curl_close($ch);
    }

    //Проверка и запись статусов активизации//  через крон
    public function checkActivationStatus()
    {
        $users = UserProgram::all();

        foreach ($users as $user){
            $date = new \DateTime();
            $date->modify('-1 month');

            $sum = $this->orderSumOfMonth($date,$user->id);

            if($sum >= 20) $status = 1;
            else  $status = 0;

            DB::table('activations')->updateOrInsert(
                [
                    'user_id' => $user->id,
                    'month' => Carbon::parse($date)->month,
                    'year' => Carbon::parse($date)->year,
                ],
                [
                    'user_id' => $user->id,
                    'month' => Carbon::parse($date)->month,
                    'year' => Carbon::parse($date)->year,
                    'sum' => $sum,
                    'status' => $status
                ]
            );
        }

        $message = "Прошла ежемесячная Проверка и запись статусов активизации";
        $ch = curl_init("https://api.telegram.org/bot338084061:AAEf5s-TegdOIQB8Akx0yj82v18ZyJ07XwI/sendMessage?chat_id=-890158682&text=$message");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //---curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_exec($ch);
        curl_close($ch);
    }

    //Проверка и перевод статуса
    public function checkAndMoveNextStatus($item,$item_user_program)
    {
        $item_status = Status::find($item_user_program->status_id);
        $next_status = Status::find($item_status->order+1);
        if(!is_null($next_status)){
            $pv = Balance::getIncomeBalance($item);
            $next_status_pv = $next_status->pv;

            if($next_status_pv <= $pv){
                $this->moveNextStatus($item,$next_status->id,$item_user_program->program_id);
                $item_user_program = UserProgram::where('user_id',$item)->first();

                Notification::create([
                    'user_id'   => $item,
                    'type'      => 'move_status',
                    'status_id' => $item_user_program->status_id
                ]);
            }
        }
    }

    //Проверка и поднятие на следующий статус + ТО
    public function setInvitersPV($inviter_list, $package, $id)
    {
        foreach (explode(",", trim($inviter_list,',')) as $key => $item){

            $item_user_program = UserProgram::where('user_id',$item)->first();
            $item_status = Status::find($item_user_program->status_id);

            Balance::setQV($item,$package->pv,$id,$package->id,0,$item_status->id);

            //Смена статуса
            $this->checkAndMoveNextStatus($item,$item_user_program);

        }
    }

    //Структурный бонус
    public function setStructureBonus($inviter_list,$package,$id,$program, $cost = 0)
    {

        foreach (Hierarchy::decompression($inviter_list,0,4) as $key => $item){
            $item_user_program = UserProgram::where('user_id',$item)->first();
            $item_status = Status::find($item_user_program->status_id);

            if($cost == 0 ){
                $sum = $package->pv;
            }
            else{
                $sum = $cost;
            }

            switch ($key) {
                case 0:
                    Balance::changeBalance($item,   $sum*4/100, 'turnover_bonus', $id,     $program->id,$package->id,  $item_status->id,$package->pv,0,$key+1);
                    break;
                case 1:
                    Balance::changeBalance($item,   $sum*3/100, 'turnover_bonus', $id,     $program->id,$package->id,  $item_status->id,$package->pv,0,$key+1);
                    break;
                case 2:
                    Balance::changeBalance($item,   $sum*2/100, 'turnover_bonus', $id,     $program->id,$package->id,  $item_status->id,$package->pv,0,$key+1);
                    break;
                case 3:
                    Balance::changeBalance($item,   $sum*1/100, 'turnover_bonus', $id,     $program->id,$package->id, $item_status->id,$package->pv,0,$key+1);
                    break;
            }
        }

    }

    //Реферальный бонус
    public function setInviterBonus($inviter,$package,$id,$program,$inviter_status, $cost = 0)
    {
        if($this->checkIsActive($inviter->id)){

            if($cost == 0 ){
                $sum = $package->pv;
            }
            else{
                $sum = $cost;
            }

            $inviter_package = Package::find($inviter->package_id);
            Balance::changeBalance($inviter->id,    $sum*$inviter_package->invite_bonus/100, 'invite_bonus', $id,     $program->id,$package->id, $inviter_status->id,$package->pv,0);

        }

    }

    //Дерево Иерархия
    public function getHierarchyTree($id)
    {

        $items = User::where('inviter_id',$id)->where('status',1)->get();
        $render = '';

        if(count($items) > 0){

            $render = '<ul  class="" id="child'.$id.'">';
            foreach ($items as $item) {
                $render .= '<li>
                        <a href="javascript:void(0);">
                            <div class="member-view-box">
                                <div class="member-image">
                                    <img src="'.$item->photo.'" alt="" class="bg-orange">
                                </div>
                                <div class="member-details">
                                    <h6>'.$item->name.'</h6>
                                    <p>'.\App\Facades\Hierarchy::getStatusName($item->id).'</p>
                                    <p>id: '.$item->id_number.' | <i class="mdi mdi-account-multiple-plus"></i> '.\App\Facades\Hierarchy::inviterCount($item->id) .' | <i class="mdi mdi-sitemap"></i> '.\App\Facades\Hierarchy::teamCount($item->id) .'</p>
                                </div>
                            </div>
                        </a>';

                $innerItem = User::where('inviter_id',$id)->where('status',1)->get();
                if (count($innerItem) > 0) {
                    $render .= $this->getHierarchyTree($item->id);
                }
                $render .= '</li>';
            }

            $render .= '</ul>';
        }

        return $render;

    }

    //Рассылка телегамрам
    public function telegramSmsSender($event)
    {

        if(isset($event->user)){
            $package = Package::find($event->user->package_id);
            $inviter = User::find($event->user->inviter_id);
            $counter = UserProgram::count();
            $city    = City::find($event->user->city_id);

            $message = "АКТИВИРОВАН НОВЫЙ ПОЛЬЗОВАТЕЛЬ \n";
            $message .= "Пакет: $package->title ($package->cost$)\n";
            $message .= "Спонсор: ".$inviter->name." \n";
            $message .= "Имя: ".$event->user->name." \n";
            $message .= "Почта: ".$event->user->email." \n";
            $message .= "Телефон: ".$event->user->number." \n";
            $message .= "Город: ".$city->title." \n";
            $message .= "Всего зарегистрировано: ".$counter." \n";
            $message = urlencode($message);
        }
        elseif(isset($event->data)) {
            $user_id = $event->data['user_id'];
            $order_sum = $event->data['sum'];
            $user = User::find($user_id);
            $cost = $order_sum + $order_sum*0.05;

            $message = "ПОКУПКА \n";
            $message .= "Пользователь: $user->name ($user->id_number) \n";
            $message .= "Сумма покупки: $cost$\n";

            $message = urlencode($message);
        }
        else{

            $id = $event->order->user_id;
            $user = User::find($id);
            $cost = $event->order->amount;
            $new_package = Package::find($event->order->package_id);

            $message = "АПГРЕЙД ПОЛЬЗОВАТЕЛЯ \n";
            $message .= "Город: $user->name ($user->id_number) \n";
            $message .= "Сумма апгрейда: $cost $\n";
            $message .= "На пакет: $new_package->title $\n";

            $message = urlencode($message);

        }


        $ch = curl_init("https://api.telegram.org/bot338084061:AAEf5s-TegdOIQB8Akx0yj82v18ZyJ07XwI/sendMessage?chat_id=-890158682&text=$message");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //---curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_exec($ch);
        curl_close($ch);
    }


    /*************************** Old METHODS from CORE ****************************/





    /**
     * @param $user_id
     * @param $position
     * @return Counter
     */
    public function pvWeekCounter($user_id,$position)
    {
        return Counter::where('user_id',$user_id)->where('position',$position)->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('sum');
    }


    /**
     * @param $user_id
     * @return Counter
     */
    public function pvCounterAllForStatus($user_id)
    {
        $user_program = UserProgram::where('user_id',$user_id)->first();
        return Counter::where('user_id',$user_id)->where('status_id',$user_program->status_id)->sum('sum');
    }

    /**
     * @param $user_id
     * @param $status
     * @param $program_id
     */
    public function moveNextStatus($user_id,$status,$program_id)
    {
        DB::table('user_programs')
            ->where('program_id',$program_id)
            ->where('user_id', $user_id)
            ->update(['status_id' => $status]);
    }

    /**
     * @param $inviter_id
     * @return int
     */
    public function getSponsorId($inviter_id)
    {
        $sponsor_user = User::find($inviter_id);

        if($sponsor_user->default_position == 0){
            $left_pv = $this->pvCounter($inviter_id,1);
            $right_pv = $this->pvCounter($inviter_id,2);
            if($left_pv > $right_pv) $branch_position = 2;
            else $branch_position = 1;
        }
        else{
            $branch_position = $sponsor_user->default_position;
        }

        $this->getSponsorIdRecursion($inviter_id,$branch_position);

        $data = [];
        $data[] = $this->sponsor_id;
        $data[] = $branch_position;
        return $data;
    }

    public function getSponsorIdRecursion($inviter_id,$branch_position)
    {
        $position_user = User::where('sponsor_id',$inviter_id)
            ->where('position',$branch_position)
            ->where('users.status',1)
            ->first();

        if(!is_null($position_user)){
            $this->getSponsorIdRecursion($position_user->id,$branch_position);
        }
        else  $this->sponsor_id = $inviter_id;
    }

    /**
     * @param $sponsor_id
     * @param $str
     * @return string
     */
    public function getSponsorsList($sponsor_id,$str)
    {
        $user = User::where('id',$sponsor_id)->where('sponsor_id','!=',0);

        if($user->exists())
        {
            $user = $user->first();

            if(!is_null($user->id))
            {
                $str .= ",$user->sponsor_id";
                $str = Hierarchy::getSponsorsList($user->sponsor_id,$str);
            }
        }


        return $str;//substr($str, 1,-1);
    }

    /**
     * @param $inviter_id
     * @param $str
     * @return string
     */
    public function getInviterList($inviter_id,$str)
    {
        $user = User::where('id',$inviter_id)->where('inviter_id','!=',0);

        if($user->exists())
        {
            $user = $user->first();

            if(!is_null($user->id))
            {
                $str .= ",$user->inviter_id";
                $str = Hierarchy::getInviterList($user->inviter_id,$str);
            }
        }


        return $str;//substr($str, 1,-1);
    }

    public function getFollowersList($user_id, $str)
    {
        $users = User::where('sponsor_id', $user_id)->get();

        if($users->count())
        {
            foreach ($users as $user) {
                if(!is_null($user->id))
                {
                    $str .= ",$user->id";
                    $str = Hierarchy::getFollowersList($user->id,$str);
                }
            }
        }

        return $str;
    }

    public function getInviterFollowerList($user_id, $str)
    {
        $users = User::where('inviter_id', $user_id)->get();

        if($users->count())
        {
            foreach ($users as $user) {
                if(!is_null($user->id))
                {
                    $str .= ",$user->id";
                    $str = Hierarchy::getInviterFollowerList($user->id,$str);
                }
            }
        }

        return $str;
    }



    /**
     * @param $id
     * @return string
     */
    public function getTree($id)
    {

        $render = '<ul>';

        $items = User::where('sponsor_id',$id)->where('status',1)->orderBy('position')->get();

        foreach ($items as $item) {
            $render .= '<li><div><a href="/tree/'.$item->id.'" target="blank">' . $item->name.'</a></div>';

            $innerItem = User::where('sponsor_id',$id)->where('status',1)->orderBy('position')->get();
            if (count($innerItem) > 0) {
                $render .= $this->getTree($item->id);
            }
            $render .= '</li>';
        }

        return $render . '</ul>';

    }

    /**
     * @param $id
     * @return string
     */
    public function getNewTree($id){

        $items = User::where('inviter_id',$id)->where('status',1)->orderBy('position')->get();
        $render= [];
        foreach ($items as $key => $item) {
            $child = User::where('inviter_id',$item->id)->where('status',1)->count();
            if($item->position == 1) $pos = 'L:';
            else $pos = 'R:';

            if ($child){
                $render[$key]['value'] = $pos.$item->name;
                $render[$key]['parent'] = $id;
                $render[$key]['children'] = $this->getNewTree($item->id);
            }
            else $render[$key]['value'] = $pos.$item->name;
        }

        return $render;


    }

    /**
     * @param $id
     */
    public function setQS()
    {
        $user_programs = UserProgram::where(DB::raw("WEEKDAY(user_programs.created_at)"),Carbon::now()->format('N')-1)->get();

        foreach ($user_programs as $item){

            $users = User::join('user_programs','users.id','=','user_programs.user_id')
                ->where('users.inviter_id',$item->user_id)
                ->where('users.status',1)
                ->whereBetween('users.created_at', [Carbon::now()->subDay(8), Carbon::now()])
                ->get();

            if(count($users) >= 2){
                foreach ($users as $innerItem){
                    if($item->package_id != 0){
                        if($innerItem->package_id != 0){
                            $package = Package::find($innerItem->package_id);
                            $sum = $package->pv*20/100*env('COURSE');
                            $check = Processing::where('user_id',$item->user_id)->where('in_user',$innerItem->user_id)->where('status','quickstart_bonus')->first();
                            if(is_null($check)){
                                echo $item->user_id."<br>";
                                Balance::changeBalance($item->user_id,$sum,'quickstart_bonus',$innerItem->user_id,1,$package->id,$item->status_id,$package->pv);
                            }
                        }
                    }
                }
            }
        }
    }

    public function setTempQS()
    {
        echo "<br>";

        for($i = 1; $i <= 5; $i++){ $date = new \DateTime();
            $date->setDate(2020, 5, $i);

            $dt = Carbon::create($date->format('Y'), $date->format('m'), $date->format('d'), 0, 0, 0, 'Asia/Almaty');

            $user_programs = UserProgram::where(DB::raw("WEEKDAY(user_programs.created_at)"),$date->format('N')-1)->get();

            foreach ($user_programs as $item){
                $dt2 = $dt->copy();
                $users = User::join('user_programs','users.id','=','user_programs.user_id')
                    ->where('users.inviter_id',$item->user_id)
                    ->where('users.status',1)
                    ->whereBetween('users.created_at', [$dt2->subDay(7), $dt])
                    ->get();

                if(count($users) >= 2){
                    foreach ($users as $innerItem){
                        if($item->package_id != 0){
                            if($innerItem->package_id != 0){

                                $package = Package::find($innerItem->package_id);
                                $sum = $package->pv*20/100*env('COURSE');
                                $check = Processing::where('user_id',$item->user_id)->where('in_user',$innerItem->user_id)->where('status','quickstart_bonus')->first();
                                if(is_null($check)){
                                    echo $item->user_id."<br>";
                                    Balance::changeBalance($item->user_id,$sum,'quickstart_bonus',$innerItem->user_id,1,$package->id,$item->status_id,$package->pv);
                                }
                            }
                        }

                    }
                }
            }
        }

    }

    /**
     * @param $id
     */
    public function revitalization()
    {
        $dt = Carbon::now()->addDay(1);
        $end = $dt->toDateString();
        $start = Carbon::now()->subMonth()->toDateString();


       $user_programs = User::whereDay('created_at', '=', date('d'))->get();

       foreach ($user_programs as $item) {

           $balance = Balance::getWeekBalanceByRange($item->id,$start,$end);
           if($balance >= 200){

               $order_amount = Order::where('type','shop')
                   ->where('user_id',$item->id)
                   ->where('not_original',null)
                   ->whereBetween('created_at', [$start, $end])
                   ->sum('amount');

               $order_pv = Order::join('baskets','baskets.id','=','orders.basket_id')
                   ->join('basket_good','basket_good.basket_id','=','baskets.id')
                   ->join('products','basket_good.good_id','=','products.id')
                   ->where('orders.type','shop')
                   ->where('orders.user_id',$item->id)
                   ->where('orders.not_original',null)
                   ->whereBetween('orders.created_at', [$start, $end])
                   ->sum(DB::raw('basket_good.quantity * products.pv'));

               if($balance >= 400){
                   $commission_sum = 125;
                   $commission_pv = 100;
                   if($order_amount < $commission_sum){
                       $commission_sum = $commission_sum - $order_amount;
                       $commission_pv = $commission_pv - $order_pv;
                   }
                   else{
                       $commission_sum = 0;
                       $commission_pv = 0;
                   }
               }
               else{
                   $commission_sum = 65;
                   $commission_pv = 50;

                   if($order_amount < $commission_sum){
                       $commission_sum = $commission_sum - $order_amount;
                       $commission_pv = $commission_pv - $order_pv;
                   }
                   else{
                       $commission_sum = 0;
                       $commission_pv = 0;
                   }
               }

               Revitalization::insert([
                   'end_date'       => $end,
                   'start_date'     => $start,
                   'earn_amount'    => $balance,
                   'order_amount'   => $order_amount,
                   'user_id'        => $item->id,
                   'pv'             => $order_pv,
                   'cron_status'    => 0,
                   'commission_sum' => $commission_sum,
                   'commission_pv'  => $commission_pv,
               ]);
           }
       }

    }

    public function revitalizationCron()
    {
        $list = Revitalization::where('cron_status',0)->get();

        foreach ($list as $item){

            $user_program = UserProgram::where('user_id',$item->user_id)->first();

           if($item->commission_sum > 0) {
               Balance::changeBalance($item->user_id,$item->commission_sum,'revitalization',$item->user_id,1,$user_program->package_id,$user_program->status_id,$item->commission_pv);
               Balance::changeBalance($item->user_id,$item->commission_sum*0.2,'cashback',$item->user_id,1,$user_program->package_id,$user_program->status_id,$item->commission_pv);

               $data = [];
               $data['pv'] = $item->commission_pv;
               $data['user_id'] = $item->user_id;

               event(new ShopTurnover($data = $data));
           }

            $item->cron_status = 1;
            $item->save();
        }
    }

    public function orderPv($order_id,$user_id)
    {
        $order_pv = Order::join('baskets','baskets.id','=','orders.basket_id')
            ->join('basket_good','basket_good.basket_id','=','baskets.id')
            ->join('products','basket_good.good_id','=','products.id')
            ->where('orders.type','shop')
            ->where('orders.user_id',$user_id)
            ->where('orders.not_original',null)
            ->where('orders.id', $order_id)
            ->sum(DB::raw('basket_good.quantity * products.pv'));

        return $order_pv;
    }

    public function userCount($user_id,$position)
    {
        $position_user = User::whereInviterId($user_id)->wherePosition($position)->whereStatus(1)->first();

        if(!is_null($position_user)){
            return UserProgram::join('users','user_programs.user_id','=','users.id')
                    ->where('list','like','%,'.$position_user->id.','.$user_id.',%')
                    ->where('users.inviter_id',$user_id)
                    ->count() + 1;
        }

        return 0;

    }


    public function deleteNonActivations()
    {
        $users = User::whereStatus('0')->whereBetween('created_at', [Carbon::now()->subDay(2),Carbon::now()->subDay(1)])->get();

        foreach ($users as $item){
            $result = UserProgram::where('user_id',$item->id)->first();
            if(is_null($result))  $item->delete();
        }
    }




}
