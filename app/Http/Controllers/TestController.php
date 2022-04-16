<?php

namespace App\Http\Controllers;


use App\Facades\Balance;
use App\Models\Counter;
use App\Models\Order;
use App\Models\UserProgram;
use DB;
use App\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\Notification;
use App\Models\Processing;
use App\Models\Status;
use App\Models\Package;
use App\Facades\Hierarchy;
use App\Events\Activation;
use App\Events\ShopTurnover;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestController extends Controller
{

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

    public function tester()
    {
        Hierarchy::controlBonus(134,100);
    }

    public function tester2()
    {
        $users = User::join('user_programs','users.id','=','user_programs.user_id')
            ->where('users.status',1)
            ->get();

        foreach ($users as $item){
            $package_title = 'Без пакета';

            if($item->package_id != 0)  $package_title = Package::find($item->package_id)->title;

            echo $item->name.','.$item->number.','.$package_title.','.$item->created_at."<br>";
        }
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

    public function setQS()
    {
        Hierarchy::setQSforManager(3);
        Hierarchy::setQSforManager(4);
    }

}
