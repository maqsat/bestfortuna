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


}
