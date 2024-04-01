<?php

namespace App\Helpers;

use DB;
use Cache;
use App\User;
use App\Models\Counter;
use App\Models\MonthlyData;
use App\Models\Order;
use App\Models\Package;
use App\Facades\Balance;
use \App\Facades\Hierarchy;
use App\Models\Processing;
use App\Models\Status;
use App\Models\UserProgram;
use Carbon\Carbon;

class Report {


    /******
     *
     * Кроны
     *
     */

    //Кумулятивный бонус//  через крон
    public function cumulativeCalculation()
    {

        $all_cum = 0;
        $list_percentage = array( 0 =>50,  1 =>20,  2 =>10,  3 =>5,  4 =>5 );
        $turnover_bonuses =  Processing::whereIn('status', ['cashback','quickstart_bonus', 'invite_bonus', 'turnover_bonus'])//
        ->whereBetween('created_at', [Carbon::now()->startOfMonth()->subMonth()->addDays(1), Carbon::now()->subMonth()->endOfMonth()->addDays(1)])
            ->orderby('user_id','asc')
            ->groupBy('user_id')
            ->selectRaw('*, sum(sum) as sum')
            ->get();

        foreach ($turnover_bonuses as $k => $item){
            $user_program = UserProgram::where('user_id', $item->user_id)->first();


            //echo $k.')'.$user_program->user_id.'== dec'.implode(",", Hierarchy::decompression($user_program->inviter_list,1,5))."<br>";
            $for_list = $this->decompressionForCumulative($user_program->inviter_list,1,5);
            foreach ($for_list as $key => $innerItem){

                //echo '>>>'.User::find($innerItem)->name.' ---'.$item->status.' =>';
                $inner_user_program = UserProgram::where('user_id', $innerItem)->first();
                $item_status = Status::find($inner_user_program->status_id);

                if($item_status->id >= 3){

                    if($item_status->depth_line >= ($key+1)){
                        //echo 'ok --> '.$item_status->depth_line.">=".($key+1);

                        $command_pv = $this->getMonthlyСommandPv($innerItem);

                        if($command_pv >= $item_status->matching_bonus){
                            $sum = $item->sum*$list_percentage[$key]/100;
                            echo $innerItem.' -> '.$sum.'<br>';
                            $all_cum += $sum;
                            Balance::changeBalance($innerItem,   $sum, 'matching_bonus', $item->user_id, $user_program->program_id,$user_program->package_id, $user_program->status_id, $item->sum,0,($key+1));
                        }
                    }
                }
                //echo "<br>";
            }

        }

        echo $all_cum;

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
        $sum = $this->getMonthlyСommandPv(1);

        $percentage_for_directors = $sum*0.015;
        $directors_pv_sum = 0;

        $directors = UserProgram::where('status_id',5)->get();
        $bonused_directors = [];


        //расчет общего количество балов
        foreach ($directors as $director){
            if(Hierarchy::checkIsActive($director->user_id)){
                echo  User::find($director->user_id)->name."<br>";

                $balance =  Processing::whereIn('status', ['quickstart_bonus', 'invite_bonus', 'turnover_bonus', 'cashback', 'matching_bonus'])
                    ->whereBetween('created_at', [Carbon::now()->startOfMonth()->subMonth()->addDays(1), Carbon::now()->subMonth()->endOfMonth()->addDays(1)])
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
        if ($percentage_for_directors > 0 && $directors_pv_sum > 0)
            $point_cost = $percentage_for_directors/$directors_pv_sum;
        else $point_cost = 0;

        //начисление бонуса
        foreach ($bonused_directors as $director){

            $sum = $point_cost * $director[1]/100;
            echo $director[0].'=>'.$sum."<br>";
            Balance::changeBalance($director[0],   $sum, 'status_bonus', 1, 1, 1, 5, $point_cost,0,0);


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

        $sum = $this->getMonthlyСommandPv(1);

        $percentage_for_directors = $sum*0.045;
        $directors_pv_sum = 0;

        $directors = UserProgram::whereIn('status_id',[6,7,8,9,10])->get();
        $bonused_directors = [];


        //расчет общего количество балов
        foreach ($directors as $director){
            if(Hierarchy::checkIsActive($director->user_id)){

                $balance =  Processing::whereIn('status', ['quickstart_bonus', 'invite_bonus', 'turnover_bonus', 'cashback', 'matching_bonus'])
                    ->whereBetween('created_at', [Carbon::now()->startOfMonth()->subMonth()->addDays(1), Carbon::now()->subMonth()->endOfMonth()->addDays(1)])
                    ->where('user_id',$director->user_id)
                    ->orderby('id','asc')
                    ->sum('sum');

                echo  User::find($director->user_id)->name.'->'.$balance."<br>";

                if($balance >= 400){
                    $bonused_directors[] = [ $director->user_id, $balance];
                    //echo User::find($director->user_id)->name."<br>";
                    $directors_pv_sum +=  $balance/100;
                }
            }

        }

        //цена одного бала
        if ($percentage_for_directors > 0 && $directors_pv_sum > 0)
            $point_cost = $percentage_for_directors/$directors_pv_sum;
        else $point_cost = 0;

        //начисление бонуса
        foreach ($bonused_directors as $director){

            $sum = $point_cost * $director[1]/100;
            echo $sum."<br>";
            Balance::changeBalance($director[0],   $sum, 'status_bonus', 1, 1, 1, 5, $point_cost,0,0);


        }


        $message = "Зачислен ежемесячный Мировой Бонус Мастеров";
        $ch = curl_init("https://api.telegram.org/bot338084061:AAEf5s-TegdOIQB8Akx0yj82v18ZyJ07XwI/sendMessage?chat_id=-890158682&text=$message");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //---curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_exec($ch);
        curl_close($ch);
    }


    /******
     *
     * Данные для экспорта
     *
     */

    public function getIncomePreviousMonthBalanceByStatus($user_id, $status)
    {

        $sum =  Processing::whereUserId($user_id)
            ->where('status', $status)
            ->whereBetween('created_at', [Carbon::now()->startOfMonth()->subMonth()->addDays(1), Carbon::now()->subMonth()->endOfMonth()->addDays(1)])
            ->sum('sum');
        return round($sum, 2);
    }


    /******
     *
     * Командный Товароборот
     *
     */

    //Получание сумма заказов одного партенра за месяц
    public function getMonthlyСommandPv($user_id)
    {
        $month = Carbon::now()->subMonth()->startOfMonth()->month;
        $year  = Carbon::now()->subMonth()->startOfMonth()->year;

        $cache_name = 'MonthlyСommandPv_'.$user_id.$month.$year;
        $value = Cache::remember($cache_name, 60, function ()  use ($user_id,$month,$year) {
            $sum = MonthlyData::where('user_id',$user_id)
                ->where('month',$month)
                ->where('year',$year)
                ->first();
            if(is_null($sum)) return 0;
            else return $sum->comand_pv;
        });

        return (float)$value;

    }

    //Пишет в базу сумму заказов для расчета кумулятивного бонуса -------> крон
    public function setMonthlyСommandPv()
    {

        $users = User::where('status',1)->get();


        foreach ($users as $k => $item){

            DB::table('monthly_datas')->updateOrInsert(
                [
                    'user_id' => $item->id,
                    'month' => Carbon::now()->subMonth()->startOfMonth()->month,
                    'year' => Carbon::now()->subMonth()->startOfMonth()->year,
                ],
                [
                    'user_id' =>  $item->id,
                    'month' => Carbon::now()->subMonth()->startOfMonth()->month,
                    'year' => Carbon::now()->subMonth()->startOfMonth()->year,
                    'comand_pv' => $this->calculateСommandPv($item->id),
                ]
            );
        }
    }

    //Калкульяция командного товароборота за месяц
    public function calculateСommandPv($user_id)
    {

        $pv_from_own_shop = $this->getOrderSumOfMonth($user_id);


        $pv_from_inviters_shop = 0;
        $inviters_list = $this->inviterList($user_id);

        foreach ($inviters_list as $key => $item){
            $pv_from_inviters_shop += $this->getOrderSumOfMonth($item->user_id);
        }

        return $pv_from_own_shop + $pv_from_inviters_shop;
    }



    /******
     *
     * Товароборот одного партнера
     *
     */

    //Получание сумма заказов одного партенра за месяц
    public function getOrderSumOfMonth($user_id)
    {
        $month = Carbon::now()->subMonth()->startOfMonth()->month;
        $year  = Carbon::now()->subMonth()->startOfMonth()->year;

        $cache_name = 'MonthlyOrderSum_'.$user_id.$month.$year;
        $value = Cache::remember($cache_name, 60, function ()  use ($user_id,$month,$year) {
            $sum = MonthlyData::where('user_id',$user_id)
                ->where('month',$month)
                ->where('year',$year)
                ->first();

            if(is_null($sum)) return 0;
            else return $sum->order_sum;
        });

        return (float)$value;

    }

    //Пишет в базу сумму заказов для расчета кумулятивного бонуса -------> крон
    public function setMonthlyOrderSum()
    {
        $turnover_bonuses =  Processing::whereIn('status', ['cashback','quickstart_bonus', 'invite_bonus', 'turnover_bonus'])//
        ->whereBetween('created_at', [Carbon::now()->startOfMonth()->subMonth()->addDays(1), Carbon::now()->subMonth()->endOfMonth()->addDays(1)])
            ->orderby('user_id','asc')
            ->groupBy('user_id')
            ->selectRaw('*, sum(sum) as sum')
            ->get();

        foreach ($turnover_bonuses as $k => $item){

            DB::table('monthly_datas')->updateOrInsert(
                [
                    'user_id' => $item->user_id,
                    'month' => Carbon::now()->subMonth()->startOfMonth()->month,
                    'year' => Carbon::now()->subMonth()->startOfMonth()->year,
                ],
                [
                    'user_id' =>  $item->user_id,
                    'month' => Carbon::now()->subMonth()->startOfMonth()->month,
                    'year' => Carbon::now()->subMonth()->startOfMonth()->year,
                    'order_sum' => $this->calculateOrderSumOfMonth($item->user_id),
                ]
            );
        }
    }

    //Калкульяция сумма заказов за месяц
    public function calculateOrderSumOfMonth($user_id)
    {

        $sum = Order::whereBetween('created_at', [Carbon::now()->subMonth()->startOfMonth()->addDays(1), Carbon::now()->subMonth()->endOfMonth()->addDays(1)])
            ->where('type','shop')
            ->where('user_id', $user_id)
            ->where(function($query){
                $query->where('status',4)
                    ->orWhere('status',6);
            })
            ->sum('uuid');


        $invites = User::whereBetween('created_at', [Carbon::now()->subMonth()->startOfMonth()->addDays(1), Carbon::now()->subMonth()->endOfMonth()->addDays(1)])
            ->where('inviter_id', $user_id)
            ->get();
        if(count($invites) > 0) {
            foreach ($invites as $invite){

                $sum += Package::find($invite->package_id)->cost;
            }
        }

        return $sum;
    }



    /******
     * Общие функции
     */

    //Список команды
    public function inviterList($user_id)
    {
        $list = UserProgram::where('inviter_list','like','%,'.$user_id.',%')->get();
        return $list;
    }


    //Декомпрессия для кумулятива
    public function decompressionForCumulative($list, $check_inviter, $length)
    {
        $list = explode(",", trim($list,','));
        $new_list = [];

        foreach ($list as $key => $item)
        {

            if( $key === 0 && $check_inviter === 0 )   continue;
            else{

                if($this->checkIsActive($item)){

                    $item_user_program = UserProgram::where('user_id', $item)->first();
                    $item_status = Status::find($item_user_program->status_id);
                    $command_pv = $this->getMonthlyСommandPv($item);

                    if($command_pv >= $item_status->matching_bonus){
                        $new_list[] = $item;
                    }

                }

                if(count($new_list) == $length) break;
            }

        }

        return $new_list;

    }


    //Декомпрессия для структуры
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

    //Проверка активен ли пользователь
    public function checkIsActive($user_id)
    {
        if($user_id == 1)  return true;

        $date = new \DateTime();
        $date->modify('-1 month');

        $activation_status = DB::table('activations')
            ->where('user_id',$user_id)
            ->whereIn('month',[Carbon::parse($date)->month])//
            ->where('year',Carbon::parse($date)->year)
            ->where('status',1)
            ->first();

        if(!is_null($activation_status)) return true;
        else{
            $user_program = UserProgram::where('user_id',$user_id)->first();

            if(!is_null($user_program)){
                if($user_program->status_id >= 3) return false;
                else {

                    $activation_start_date = Balance::getActivationStartDate($user_program->created_at, $user_id);
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
}
