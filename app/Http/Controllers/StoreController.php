<?php

namespace App\Http\Controllers;

use App\Facades\Hierarchy;
use App\Models\Order;
use App\Models\UserProgram;
use DB;
use Auth;
use Carbon\Carbon;
use App\Facades\Balance;
use App\Models\Basket;
use App\Models\Notification;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::user();

        if($request->history == 'delete'){
            Order::where('user_id', Auth::user()->id)->where('type','shop')->where('status',0)->update(['status' => 12]);
        }

        $orders = Order::where('user_id', Auth::user()->id)->where('type','shop')->where('payment','manual')->orderBy('id','desc')->first();

        if(false){//$user->type==1
            $list = Product::whereNull('is_client')->orderBy('created_at','desc')->paginate();
            $tag = Tag::all();
            if($request->has('tag')){
                $list = Tag::find($request->tag)->products;
            }
            return view('product.user-main', compact('list','tag','orders'));
        }
        else{

            $date = new \DateTime();

            $sum = Hierarchy::orderSumOfMonth($date,$user->id);

            $list = Product::whereNull('is_client')->orderBy('created_at','desc')->get();
            $tag = Tag::all();
            if($request->has('tag')){
                $list = Tag::find($request->tag)->products;
            }

            return view('product.main', compact('list','tag','orders','sum'));
        }


    }

    public function story()
    {
        $list = Basket::where('user_id',Auth::user()->id)->where('status',1)->paginate(30);

        return view('basket.story',compact('list'));
    }

    public function activationCalendar()
    {
        $user_program = UserProgram::where('user_id',Auth::user()->id)->first();

        return view('basket.activation', compact('user_program'));
    }


    public function show($id){
        if(Auth::check()){
            $user = Auth::user();
            if($user->type==1){
                $product = Product::find($id);
                return view('product.user-single',compact('product'));
            }
            else{
                $orders = Order::where('user_id', Auth::user()->id)
                    ->where('type','shop')
                    ->where('payment','manual')
                    ->where('status','!=','4')
                    ->orderBy('id','desc')->first();

                if(is_null($orders) or $orders->status == 12) {
                    $product = Product::find($id);
                    return view('product.single',compact('product'));
                }
               else return redirect('main-store');
            }
        }
        else{
            $product = Product::find($id);
            return view('product.user-single',compact('product'));
        }


    }


}
