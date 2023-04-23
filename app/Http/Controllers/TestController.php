<?php

namespace App\Http\Controllers;


use App\Facades\Balance;
use App\Facades\General;
use App\Models\Counter;
use App\Models\FortuneWheel;
use App\Models\Order;
use App\Models\UserProgram;
use DB;
use File;
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
use phpDocumentor\Reflection\DocBlock\Description;

class TestController extends Controller
{

    public function tester()
    {
        $user_id = 4582;
        $date = new \DateTime();
        //$date->modify('-1 month');

        $check_invite_count = User::where('inviter_id',$user_id)
            ->whereBetween('created_at', [Carbon::parse($date)->startOfMonth(), Carbon::parse($date)->endOfMonth()])
            ->count();

        $amount = Order::whereUserId($user_id)
            ->whereBetween('created_at', [Carbon::parse($date)->startOfMonth(), Carbon::parse($date)->endOfMonth()])
            ->sum('amount');

        if($amount >= 800) $amount_count = 1;
        elseif($amount >= 1400) $amount_count = 2;
        elseif($amount >= 2000) $amount_count = 3;
        else $amount_count = 0;

        $attempt_count = $check_invite_count + $amount_count - FortuneWheel::whereUserId($user_id)->count();

        dd($attempt_count);

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


    public function termination()
    {

        $users = UserProgram::where('inviter_list','like','%,3,%')->get();

        foreach ($users as $item){

            $inviter_list2 = str_replace(',3,1,',',1,', $item->inviter_list);
            echo $inviter_list2.'<='.$item->inviter_list."<br>";

            $item->inviter_list = $inviter_list2;
            $item->save();

        }


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




    public function tester2()
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
