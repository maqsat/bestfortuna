<?php

namespace App\Listeners;

use App\Facades\Balance;
use App\Facades\Hierarchy;
use App\Models\Notification;
use App\Models\Status;
use App\Models\UserProgram;
use DB;

use App\Events\ShopTurnover;


class BonusDistribution
{
    /**
     * Create the event listener.
     *
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ShopTurnover  $event
     * @return void
     */
    public function handle(ShopTurnover $event)
    {

        $user_id = $event->data['user_id'];
        $order_sum = $event->data['sum'];


        $user_program = UserProgram::where('user_id',$user_id)->first();
        $list = ','.$user_id.','.$user_program->inviter_list;

        foreach (Hierarchy::decompression($list,0,5) as $key => $item){

            $item_user_program = UserProgram::where('user_id',$item)->first();

            if(!is_null($item_user_program) ){
                //Пассивный бонус
                if($key < 5){

                    switch ($key) {
                        case 0:
                            $sum = $order_sum*20/100;
                            Balance::changeBalance($item,   $sum, 'turnover_bonus', $user_id, 1,$item_user_program->package_id, $item_user_program->status_id,$order_sum,0,$key+1);
                            Hierarchy::newMatchingBonus($item,$sum,$key);
                            break;
                        case 1:
                            $sum = $order_sum*4/100;
                            Balance::changeBalance($item,   $sum, 'turnover_bonus', $user_id, 1,$item_user_program->package_id, $item_user_program->status_id,$order_sum,0,$key+1);
                            //Hierarchy::newMatchingBonus($item,$sum,$key);
                            break;
                        case 2:
                            $sum = $order_sum*3/100;
                            Balance::changeBalance($item,   $sum, 'turnover_bonus', $user_id, 1,$item_user_program->package_id, $item_user_program->status_id,$order_sum,0,$key+1);
                            //Hierarchy::newMatchingBonus($item,$sum,$key);
                            break;
                        case 3:
                            $sum = $order_sum*2/100;
                            Balance::changeBalance($item,   $sum, 'turnover_bonus', $user_id, 1,$item_user_program->package_id, $item_user_program->status_id,$order_sum,0,$key+1);
                            //Hierarchy::newMatchingBonus($item,$sum,$key);
                            break;
                        case 4:
                            $sum = $order_sum*1/100;
                            Balance::changeBalance($item,   $sum, 'turnover_bonus', $user_id, 1,$item_user_program->package_id, $item_user_program->status_id,$order_sum,0,$key+1);
                            //Hierarchy::newMatchingBonus($item,$sum,$key);
                            break;
                    }

                    //Окончание Пассивного бонуса
                }

                //Смена статуса
                Hierarchy::checkAndMoveNextStatus($item,$item_user_program);
            }
        }

        //Рассылка в телеграмм
        Hierarchy::telegramSmsSender($event);
    }
}
