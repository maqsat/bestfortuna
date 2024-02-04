<?php

namespace App\Helpers;

use App\Models\Counter;
use App\Models\Log;
use App\Models\Notification;
use App\Models\UserProgram;
use App\User;
use App\Models\UserSubscriber;
use App\Models\Status;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Facades\Hierarchy;
use App\Models\Processing;

class Balance {

    /*************************** New METHODS for BEST FORTUNA ****************************/

    public function changeBalance($user_id,$sum,$status,$in_user,$program_id,$package_id=0,$status_id=0,$pv = 0,$limited_sum = 0,$matching_line = 0,$card_number = 0,$message = '', $withdrawal_method = null)
    {
        $processing = new Processing(
            [
                'user_id' => $user_id,
                'sum' => $sum,
                'status' => $status,
                'in_user' => $in_user,
                'program_id' => $program_id,
                'package_id' => $package_id,
                'status_id' => $status_id,
                'pv' => $pv,
                'card_number' => $card_number,
                'matching_line' => $matching_line,
                'limited_sum' => $limited_sum,
                'message' => $message,
                'withdrawal_method' => $withdrawal_method,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                //'created_at' => '2024-02-01 00:01:00',
                //WHERE `status` LIKE 'matching_bonus' AND `created_at` >= '2024-02-01 00:01:00';
            ]
        );
        $processing->save();
        return $processing->id;
    }

    public function setQV($user_id,$sum,$in_user,$package_id,$position,$status_id, $alias = null)
    {
        Counter::insert(
            [
                'user_id' => $user_id,
                'sum' => $sum,
                'inner_user_id' => $in_user,
                'package_id' => $package_id,
                'position' => $position,
                'status_id' => $status_id,
                'alias' => $alias,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                //'created_at' => '2024-02-01 00:01:00',

            ]
        );
    }

    public function getIncomeBalance($user_id)
    {
        $sum =  Processing::whereUserId($user_id)
            ->whereIn('status', ['invite_bonus','turnover_bonus', 'matching_bonus', 'cashback', 'quickstart_bonus', 'status_bonus', 'admin_add'])->sum('sum');
        return round($sum, 2);
    }


    public function getActivationStartDate($user_created_at, $user_id)
    {
        $user_program =  UserProgram::where('user_id',$user_id)->first();

        if($user_program->status_id < 3){
            $date1 = new \DateTime($user_created_at);
            $date2 = new \DateTime();
            $diff = $date1->diff($date2);

            $yearsInMonths = $diff->format('%r%y') * 12;
            $months = $diff->format('%r%m');

            $activation_start_date = date('Y-m-d', strtotime("+6 months", strtotime($user_created_at)));
        }

        else {
            $activation_start_date = Notification::where('user_id', $user_id)
                ->where('type', 'move_status')
                ->where('status_id', 3)
                ->first();

            if(is_null($activation_start_date))
            {
                $activation_start_date = '2022-08-31 00:13:28';
            }
            else{
                $activation_start_date = $activation_start_date->created_at ;
            }
        }

        return $activation_start_date;
    }



    /*************************** Old METHODS from CORE ****************************/


    public function getBalance($user_id)
    {
        $sum = $this->getIncomeBalance($user_id) - $this->getBalanceOut($user_id) - $this->getWeekBalance($user_id);
        return round($sum, 2);
    }


    public function getWeekBalance($user_id)
    {
        $sum = Processing::whereUserId($user_id)->whereIn('status', ['admin_add', 'turnover_bonus', 'status_bonus', 'invite_bonus','quickstart_bonus','matching_bonus'])->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('sum');
        return round($sum, 2);
    }


    public function getBalanceOut($user_id)
    {
        $sum = Processing::whereUserId($user_id)->whereIn('status', ['out','shop','revitalization'])->sum('sum');
        return round($sum, 2);
    }

    public function getWeekBalanceByStatus($user_id,$date_from,$date_to,$status)
    {
        $date_from = explode('-',$date_from);
        $date_from = Carbon::create($date_from[0], $date_from[1], $date_from[2],0,0,0, date_default_timezone_get())->toDateTimeString();

        $date_to = explode('-',$date_to);
        $date_to = Carbon::create($date_to[0], $date_to[1], $date_to[2],23,59,59, date_default_timezone_get())->toDateTimeString();

        $sum = Processing::whereUserId($user_id)->where('status', $status)->whereBetween('created_at', [$date_from, $date_to])->sum('sum');
        return round($sum, 2);
    }

    public function getWeekBalanceByRange($user_id,$date_from,$date_to)
    {
        $date_from = explode('-',$date_from);
        $date_from = Carbon::create($date_from[0], $date_from[1], $date_from[2],0,0,0, date_default_timezone_get())->toDateTimeString();

        $date_to = explode('-',$date_to);
        $date_to = Carbon::create($date_to[0], $date_to[1], $date_to[2],23,59,59, date_default_timezone_get())->toDateTimeString();
        $sum = Processing::whereUserId($user_id)->whereIn('status', ['turnover_bonus', 'status_bonus', 'invite_bonus','quickstart_bonus','matching_bonus'])->whereBetween('created_at', [$date_from, $date_to])->sum('sum');
        return round($sum, 2);
    }


    public function getBalanceByStatus($status)
    {
        $sum = Processing::where('status', $status)->sum('sum');
        return round($sum, 2);
    }

    public function getUserBalanceByStatus($user,$status)
    {
        $sum = Processing::where('user_id', $user)->where('status', $status)->sum('sum');
        return round($sum, 2);
    }

    public function revitalizationBalance($user_id)
    {
        $sum1 = Processing::whereUserId($user_id)->whereIn('status', ['cashback'])->sum('sum');
        $sum2 = Processing::whereUserId($user_id)->whereIn('status', ['revitalization-shop'])->sum('sum');

        return round($sum1-$sum2, 2);
    }

    public function getMonthBalanceByStatus($user_id,$date_from,$date_to,$status)
    {
        $date_from = explode('-',$date_from);
        $date_from = Carbon::create($date_from[0], $date_from[1], $date_from[2],0,0,0, date_default_timezone_get())->toDateTimeString();

        $date_to = explode('-',$date_to);
        $date_to = Carbon::create($date_to[0], $date_to[1], $date_to[2],23,59,59, date_default_timezone_get())->subday(1)->toDateTimeString();

        $sum = Processing::whereUserId($user_id)->where('status', $status)->whereBetween('created_at', [$date_from, $date_to])->sum('sum');
        return round($sum, 2);
    }


    public function totalMonthFromRegister($user_created_at)
    {
        $date1 = new \DateTime($user_created_at);
        $date2 = new \DateTime();;
        $diff = $date1->diff($date2);

        $yearsInMonths = $diff->format('%r%y') * 12;
        $months = $diff->format('%r%m');
        $totalMonths = $yearsInMonths + $months;

        return $totalMonths;
    }

    /*************************** Export METHODS ****************************/

    public function getExportUserBalanceByStatus($user,$status,$date)
    {
        $sum = Processing::where('user_id', $user)
            ->where('status', $status)
            ->whereBetween('created_at', [Carbon::parse($date)->startOfMonth(), Carbon::now()])
            ->sum('sum');
        return round($sum, 2);
    }




    /*************************** OLD METHODS ****************************/

    public function getBalanceAllUsers()
    {
        $sum = Processing::whereIn('status', ['admin_add', 'turnover_bonus', 'status_bonus', 'invite_bonus','quickstart_bonus','matching_bonus'])->sum('sum') - Processing::whereStatus('out')->sum('sum');
        return round($sum, 2);
    }

    public function getBalanceOutAllUsers()
    {
        $sum = Processing::whereStatus('out')->sum('sum');
        return round($sum, 2);
    }

    public function getBalanceWithOut($user_id)
    {
        $sum = Processing::whereUserId($user_id)->whereStatus('in')->sum('sum') + Processing::whereUserId($user_id)->whereStatus('bonus')->sum('sum') + Processing::whereUserId($user_id)->whereStatus('percentage')->sum('sum')  + Processing::where('in_user',$user_id)->whereStatus('transfered_in')->sum('sum') - Processing::whereUserId($user_id)->whereStatus('out')->sum('sum')  - Processing::whereUserId($user_id)->whereStatus('transfered')->sum('sum')  - Processing::whereUserId($user_id)->whereStatus('request')->sum('sum') - Processing::whereUserId($user_id)->whereStatus('transfer')->sum('sum');
        return round($sum, 2);
    }

    public function getMondaysInRange($dateFromString, $dateToString)
    {
        $dateFrom = new \DateTime($dateFromString);
        $dateTo = new \DateTime($dateToString);
        $dates = [];

        if ($dateFrom > $dateTo) {
            return $dates;
        }

        if (1 != $dateFrom->format('N')) {
            $dateFrom->modify('this week monday ');
        }

        while ($dateFrom <= $dateTo) {
            $dates[] = $dateFrom->format('Y-m-d');
            $dateFrom->modify('+1 week');
        }

        return $dates;
    }

    public function getMonthByRange($start, $end)
    {

        $months = [];

        $period = CarbonPeriod::create($start, '1 month', $end);

        foreach ($period as $dt) {
            $months[] = $dt->format("Y-m");
        }

        return $months;

    }
}
