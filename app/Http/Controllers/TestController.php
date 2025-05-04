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


    public function setBots()
    {
        for ($i = 0; $i < 1000; $i++){

            $all_users = User::whereNull('is_office_lider')->get();


            foreach ($all_users as $item){

                $stop = User::all();

                if(count($stop) >= 10000) dd(count($stop));

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
                        'package_id'    => 2,
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
                        'package_id'    => 2,
                        'program_id'    => 1,
                    ]);

                    User::create([
                        'name'          => "3 name ".$item->id,
                        'number'        => "870170889".$item->id,
                        'email'         => "3mail@com.kz".$item->id,
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
                        'package_id'    => 2,
                        'program_id'    => 1,
                    ]);

                    User::create([
                        'name'          => "4 name ".$item->id,
                        'number'        => "870170889".$item->id,
                        'email'         => "4mail@com.kz".$item->id,
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
                        'package_id'    => 2,
                        'program_id'    => 1,
                    ]);

                    User::create([
                        'name'          => "5 name ".$item->id,
                        'number'        => "870170889".$item->id,
                        'email'         => "5mail@com.kz".$item->id,
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
                        'package_id'    => 2,
                        'program_id'    => 1,
                    ]);

                    User::create([
                        'name'          => "6 name ".$item->id,
                        'number'        => "870170889".$item->id,
                        'email'         => "6mail@com.kz".$item->id,
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
                        'package_id'    => 2,
                        'program_id'    => 1,
                    ]);

                    User::create([
                        'name'          => "7 name ".$item->id,
                        'number'        => "870170889".$item->id,
                        'email'         => "7mail@com.kz".$item->id,
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
                        'package_id'    => 2,
                        'program_id'    => 1,
                    ]);

                    User::create([
                        'name'          => "8 name ".$item->id,
                        'number'        => "870170889".$item->id,
                        'email'         => "8mail@com.kz".$item->id,
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
                        'package_id'    => 2,
                        'program_id'    => 1,
                    ]);

                    User::create([
                        'name'          => "9 name ".$item->id,
                        'number'        => "870170889".$item->id,
                        'email'         => "9mail@com.kz".$item->id,
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
                        'package_id'    => 2,
                        'program_id'    => 1,
                    ]);

                    User::create([
                        'name'          => "10 name ".$item->id,
                        'number'        => "870170889".$item->id,
                        'email'         => "10mail@com.kz".$item->id,
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
                        'package_id'    => 2,
                        'program_id'    => 1,
                    ]);

                    User::create([
                        'name'          => "11 name ".$item->id,
                        'number'        => "870170889".$item->id,
                        'email'         => "11mail@com.kz".$item->id,
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
                        'package_id'    => 2,
                        'program_id'    => 1,
                    ]);

                    User::create([
                        'name'          => "12 name ".$item->id,
                        'number'        => "870170889".$item->id,
                        'email'         => "12mail@com.kz".$item->id,
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
                        'package_id'    => 2,
                        'program_id'    => 1,
                    ]);

                    User::create([
                        'name'          => "13 name ".$item->id,
                        'number'        => "870170889".$item->id,
                        'email'         => "13mail@com.kz".$item->id,
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
                        'package_id'    => 2,
                        'program_id'    => 1,
                    ]);

                    User::create([
                        'name'          => "14 name ".$item->id,
                        'number'        => "870170889".$item->id,
                        'email'         => "14mail@com.kz".$item->id,
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
                        'package_id'    => 2,
                        'program_id'    => 1,
                    ]);

                    User::create([
                        'name'          => "15 name ".$item->id,
                        'number'        => "870170889".$item->id,
                        'email'         => "15mail@com.kz".$item->id,
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
                        'package_id'    => 2,
                        'program_id'    => 1,
                    ]);

                    $item->is_office_lider = 1;
                    $item->save();

                }

            }
        }


    }



    public function autoActivate()
    {

        $users = User::whereStatus(0)->limit(10000)->get();//

        foreach ($users as $item){
            $user = User::find($item->id);

            if($user->status == 1) echo "<h4>$item->id => Пользователь уже активирован!</h4>";

            event(new Activation($user = $user));
            echo  "<h4>$item->id => Пользователь успешно активирован!</h4>";
        }

    }

}
