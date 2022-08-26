<?php

namespace App\Listeners;


use App\Models\Processing;
use DB;
use Auth;
use App\User;
use App\Models\Order;
use App\Models\Counter;
use App\Models\Package;
use App\Models\Status;
use App\Models\UserProgram;
use App\Models\Notification;
use App\Models\Program;
use App\Facades\Balance;
use App\Facades\Hierarchy;
use Carbon\Carbon;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Events\Upgrade;

class UserUpgraded
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Upgrade  $event
     * @return void
     */
    public function handle(Upgrade $event)
    {

        $upgrade_status =  Order::where('type','upgrade')->where('status','!=',4)->where('status','!=',6)->where('id',$event->order->id)->first();
        if(is_null($upgrade_status)) dd("Апгрейд произведен ранее, ссылка не активна");
        Order::where( 'id',$event->order->id)->update(['status' => 4]);


        $id = $event->order->user_id;
        $user_program = UserProgram::whereUserId($id)->first();
        $current_user = User::find($id);
        $program = Program::find($user_program->program_id);
        $inviter = User::find($current_user->inviter_id);
        $new_package = Package::find($event->order->package_id);
        $old_package = Package::find($current_user->package_id);
        $package_cost = Hierarchy::upgradeCost($old_package, $new_package, $current_user);
        $status_id = $new_package->rank;


        Balance::changeBalance($id,$package_cost,'upgrade',$id,$program->id,$new_package->id,1,$new_package->pv - $old_package->pv );

        User::whereId($id)->update(['package_id' => $new_package->id]);
        UserProgram::whereUserId($id)->update(['package_id' => $new_package->id,'status_id' => $status_id]);

        if (Auth::check()) $author_id = Auth::user()->id;
        else $author_id = 0;

        Notification::create([
            'user_id' => $id,
            'type' => 'user_upgraded',
            'author_id' => $author_id
        ]);


        $inviter_list = Hierarchy::getInviterList($event->order->user_id,'').',';
        $inviter_program = UserProgram::where('user_id',$inviter->id)->first();
        $inviter_status = Status::find($inviter_program->status_id);

        //Реферальный бонус
        Hierarchy::setInviterBonus($inviter,$new_package,$id,$program,$inviter_status,$package_cost);

        //Структурный бонус
        Hierarchy::setStructureBonus($inviter_list,$new_package,$id,$program,$package_cost);

        //Проверка и поднятие на следующий статус + ТО
        Hierarchy::setInvitersPV($inviter_list, $new_package, $id);

    }
}
