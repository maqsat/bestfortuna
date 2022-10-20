<?php

namespace App\Listeners;

use DB;
use Auth;
use App\User;
use App\Models\Package;
use App\Models\Status;
use App\Models\UserProgram;
use App\Models\Notification;
use App\Models\Program;
use App\Models\Country;
use App\Models\City;
use App\Facades\Balance;
use App\Facades\Hierarchy;
use App\Events\Activation;
use Carbon\Carbon;
use App\Models\Processing;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserActivated
{
    /**
     * Create the event listener.
     *
     */

    public function __construct()
    {

    }

    /**
     * Handle the event.
     *
     * @param  Activation  $event
     * @return void
     */
    public function handle(Activation $event)
    {
        $id = $event->user->id;
        $program = Program::find($event->user->program_id);
        $this_user = User::find($id);
        $inviter = User::find($event->user->inviter_id);

        /*start check*/
        if(is_null($this_user)) dd("Пользователь не найден");

        $check_user_program = UserProgram::where('program_id', $program->id)->where('user_id',$id)->count();
        if($check_user_program != 0) dd("Пользователь уже активирован -> $id");
        /*end check*/

        /*start sponsor check*/
        $check_this_user_sponsor_id_program = UserProgram::where('program_id', $program->id)->where('user_id',$this_user->inviter_id)->count();
        if($check_this_user_sponsor_id_program == 0) dd("Спонсор не активирован -> $this_user->sponsor_id");
        /*end sponsor check*/

        $package = Package::find($event->user->package_id);

        if(is_null($package)) dd("Пакет не выбран -> $id");
        $package_id = $package->id;
        $status_id = $package->rank;
        $package_cost = $package->cost;


        User::whereId($event->user->id)->update([
            'status'    => 1,
            'id_number' => date('y').$event->user->id,
        ]);

        $list = Hierarchy::getSponsorsList($event->user->id,'').',';
        $inviter_list = Hierarchy::getInviterList($event->user->id,'').',';
        $inviter_program = UserProgram::where('user_id',$inviter->id)->first();
        $inviter_status = Status::find($inviter_program->status_id);


        //Balance::setQV($id,$package->pv,$id,$package->id,0,$status_id);
        Balance::changeBalance($id,$package_cost,'register',$event->user->id,$event->user->program_id,$package_id,0,$package->pv);
        Balance::changeBalance($id,$package_cost*0.05,'register',$event->user->id,$event->user->program_id,$package_id,0,$package->pv);


        UserProgram::insert(
            [
                'user_id' => $event->user->id,
                'list' => $list,
                'status_id' => $status_id,
                'inviter_list' => $inviter_list,
                'program_id' => $event->user->program_id,
                'package_id' => $package_id,
                'level'   => $inviter_program->level + 1
            ]
        );

        if (Auth::check()) $author_id = Auth::user()->id;
        else $author_id = 0;

        Notification::create([
            'user_id' => $event->user->id,
            'type' => 'user_activated',
            'author_id' => $author_id
        ]);


        //Реферальный бонус
        Hierarchy::setInviterBonus($inviter,$package,$id,$program,$inviter_status);

        //Структурный бонус
        Hierarchy::setStructureBonus($inviter_list,$package,$id,$program);

        //Проверка и поднятие на следующий статус + ТО
        Hierarchy::setInvitersPV($inviter_list, $package, $id);

        //Рассылка в телеграмм
        Hierarchy::telegramSmsSender($event);


    }
}
