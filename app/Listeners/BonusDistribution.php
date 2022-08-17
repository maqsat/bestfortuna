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

        $inviter_list = Hierarchy::getInviterList($user_id,'').',';

        $inviter_list_for_slice = explode(',',trim($inviter_list,','));
        $inviter_list = array_slice($inviter_list_for_slice, 0, 4);

        foreach ($inviter_list as $key => $item){

            $item_user_program = UserProgram::where('user_id',$item)->first();

            if(!is_null($item_user_program) ){
                //Пассивный бонус
                if($key < 4){

                    switch ($key) {
                        case 0:
                            $sum = $order_sum*4/100;
                            $status_name = 'quickstart_bonus';
                            Balance::changeBalance($item,   $sum, $status_name, $user_id, 1,$item_user_program->package_id, $item_user_program->status_id,$order_sum,0,$key+1);
                            break;
                        case 1:
                            $sum = $order_sum*3/100;
                            $status_name = 'quickstart_bonus';
                            Balance::changeBalance($item,   $sum, $status_name, $user_id, 1,$item_user_program->package_id, $item_user_program->status_id,$order_sum,0,$key+1);
                            break;
                        case 2:
                            $sum = $order_sum*2/100;
                            $status_name = 'quickstart_bonus';
                            Balance::changeBalance($item,   $sum, $status_name, $user_id, 1,$item_user_program->package_id, $item_user_program->status_id,$order_sum,0,$key+1);
                            break;
                        case 3:
                            $sum = $order_sum*1/100;
                            $status_name = 'quickstart_bonus';
                            Balance::changeBalance($item,   $sum, $status_name, $user_id, 1,$item_user_program->package_id, $item_user_program->status_id,$order_sum,0,$key+1);
                            break;
                    }

                    //Окончание Пассивного бонуса
                }

                //Смена статуса
                Hierarchy::checkAndMoveNextStatus($item,$item_user_program);
            }
        }
    }
}
