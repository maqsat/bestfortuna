<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\FortuneWheel;
use App\Models\Office;
use App\Models\Review;
use DB;
use App\User;
use Illuminate\Support\Facades\Gate;
use PayPost;
use Storage;
use Validator;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\Counter;
use App\Models\Package;
use App\Models\Status;
use App\Models\Product;
use App\Models\Notification;
use App\Facades\Balance;
use App\Models\Processing;
use App\Models\Program;
use App\Events\Activation;
use App\Facades\Hierarchy;
use App\Models\UserProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('activation')->except('index');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function index(Request $request)
    {

        if(Auth::user()->status == 1){

            $user = Auth::user();
            $user_program = UserProgram::where('user_id',$user->id)->first();
            $package = Package::find($user_program->package_id);
            $invite_list = User::whereInviterId($user->id)->whereStatus(1)->get();
            $pv_counter_all = Hierarchy::pvCounterAll($user->id);
            $pv_accumulative=  Balance::getIncomeBalance($user->id);
            $status = Status::find($user_program->status_id);
            $next_status = Status::find($status->order+1);
            if(!is_null($next_status)){
                $percentage = $pv_accumulative*100/$next_status->pv;
            }
            else  $percentage = 100;
            $list = UserProgram::where('inviter_list','like','%,'.$user->id.',%')->count();

            $balance = Balance::getBalance($user->id);

            $not_cash_bonuses = DB::table('not_cash_bonuses')->where('user_id', $user->id)->where('status',0)->get();

            $move_status = Notification::where('user_id',$user->id)->where('type','move_status')
                ->whereBetween('created_at', [Carbon::now()->subDays(1), Carbon::now()])
                ->orderBy('created_at', 'desc')
                ->first();

            $small_branch = Hierarchy::pvCounterAll($user->id);
            $activation_start_date = Balance::getActivationStartDate($user->created_at,$user->id );
            $totalMonths = Balance::totalMonthFromRegister($user->created_at);

            $date = new \DateTime();
            $activation =  Hierarchy::orderSumOfMonth($date,$user->id);

            return view('profile.home', compact(
                'package',
                'invite_list',
                'list',
                'status',
                'pv_counter_all',
                'user',
                'invite_list',
                'balance',
                'percentage',
                'next_status',
                'move_status',
                'not_cash_bonuses',
                'small_branch',
                'pv_accumulative',
                'totalMonths',
                'activation_start_date',
                'activation'
            ));
        }
        else{
            $orders = Order::where('user_id',Auth::user()->id)->where('type','register')->where('payment','manual')->orderBy('id','desc')->first();

            if(Auth::user()->country_id == 1){
                $currency_symbol = '₸';
                $current_currency = config('marketing.dollar_course');
            }
            else{
                $currency_symbol = '$';
                $current_currency = 1;
            }

            return view('profile.non-activated', compact('orders','current_currency','currency_symbol'));
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function partner()
    {
        if(!Gate::allows('admin_user_create')) {
            abort('401');
        }

        $users = \App\User::whereStatus(1)->get();
        return view('user.partner', compact('users'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function partnerStore(Request $request)
    {
        if(!Gate::allows('admin_user_create')) {
            abort('401');
        }

        $request->validate([
            'name'          => 'required',
            'number'        => 'required',
            'email'         => ['required', 'string', 'email', 'max:255', 'unique:users'],
            //gender'        => 'required',
            //'birthday'      => 'required',
            'country_id'    => 'required',
            //'address'       => 'required',
            'password'      => [ 'required', 'string', 'min:6'],
            'created_at'    => 'required',
            'city_id'       => 'required',
            'inviter_id'    => ['required', "sponsor_in_program:1", 'exists:users,id'],
            'sponsor_id'    => 'required',
            'position'      => 'required',
            'package_id'    => 'required',
            //'office_id'    => 'required',
        ]);

        $checker = User::where('sponsor_id',$request->sponsor_id)->where('position',$request->position)->count();
        if($checker > 0) return  redirect()->back()->with('status', 'Позиция занята, проверьте, есть не активированный партнер в этой позиции');

        $package = Package::find($request->package_id);
        if (is_null($package))   $status_id = 1;
        else   $status_id = $package->rank;



        if ($status_id < $request->status_id){
            $status_id = $request->status_id;
        }


        $user = User::create([
            'name'          => $request->name,
            'number'        => $request->number,
            'email'         => $request->email,
            'gender'        => $request->gender,
            'birthday'      => $request->birthday,
            'address'       => $request->address,
            'password'      => bcrypt($request->password),
            'created_at'    => $request->created_at,
            'country_id'    => $request->country_id,
            'city_id'       => $request->city_id,
            'inviter_id'    => $request->inviter_id,
            'sponsor_id'    => $request->sponsor_id,
            'position'      => $request->position,
            'package_id'    => $request->package_id,
            'status_id'     => $status_id,
            'office_id'     => $request->office_id,
            'program_id'     =>  1,
        ]);


        if($request->package_id != 0){
            $package = Package::find($request->package_id);
            $cost = $package->cost + env('REGISTRATION_FEE');
            $package_id  = $package->id;
        }
        else $cost = env('REGISTRATION_FEE');

        $order =  Order::updateOrCreate(
            [
                'type' => 'register',
                'status' => 0,
                'payment' => 'manual',
                'uuid' => 0,
                'user_id' => $user->id,
            ],
            ['amount' => $cost, 'package_id' => $request->package_id]
        );

        event(new Activation($user = $user));

        Notification::create([
            'user_id'   => Auth::user()->id,
            'type'      => 'admin_register_user',
            'message'   => 'Зарегистрировал пользователя ' . $user->name . ' ( ' . $user->id . ' ) ',
        ]);

        return redirect('/home')->with('success', 'Действие выполнено успешно!');
    }

    public function partnerSponsorUsers(Request $request)
    {
        if(!Gate::allows('admin_user_create')) {
            abort('401');
        }
        $request->validate([
            'inviter_id' => 'required', 'integer'
        ]);

        $sponsor_users = Hierarchy::followersList($request->inviter_id);
        $text = '';

        foreach ($sponsor_users  as $item){

            $left_user = User::whereSponsorId($item->user_id)->wherePosition(1)->whereStatus(1)->first();
            $right_user = User::whereSponsorId($item->user_id)->wherePosition(2)->whereStatus(1)->first();

            if(is_null($left_user) or is_null($right_user)){
                $name = User::find($item->user_id)->name;
                $tmt_text = '<option value='.$item->user_id.'>'.$name.'</option>';
                $text .= $tmt_text;
            }

        }

        return $text;
    }

    public function partnerSponsorPositions(Request $request)
    {
        if(!Gate::allows('admin_user_create')) {
            abort('401');
        }
        $request->validate([
            'sponsor_id' => 'required', 'integer'
        ]);

        $text = '';
        $left_user = User::whereSponsorId($request->sponsor_id)->wherePosition(1)->whereStatus(1)->first();
        $right_user = User::whereSponsorId($request->sponsor_id)->wherePosition(2)->whereStatus(1)->first();

        if(is_null($left_user))   $text .= '<option value=1>Слева</option>';
        if(is_null($right_user))   $text .= '<option value=2>Справа</option>';


        return $text;
    }

    public function partnerUserOffices(Request $request)
    {
        if(!Gate::allows('admin_user_create')) {
            abort('401');
        }
        $request->validate([
            'city_id' => 'required', 'integer'
        ]);

        $text = '<option>Не указан</option>';

        $offices = Office::where('city_id',$request->city_id)->get();

        foreach ($offices  as $item){
            $tmt_text = '<option value='.$item->id.'>'.$item->title.'</option>';
            $text .= $tmt_text;
        }


        return $text;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        if(!Gate::allows('admin_user_create')) {
            abort('401');
        }

        $request->validate([
            'name'          => 'required',
            'number'        => 'required',
            'email'         => ['required', 'string', 'email', 'max:255', 'unique:users'],
            //gender'        => 'required',
            //'birthday'      => 'required',
            'country_id'    => 'required',
            //'address'       => 'required',
            'password'      => [ 'required', 'string', 'min:6'],
            'created_at'    => 'required',
            'city_id'       => 'required',
            'inviter_id'    => ['required', "sponsor_in_program:1", 'exists:users,id'],
            'sponsor_id'    => 'required',
            'position'      => 'required',
            'package_id'    => 'required',
            'office_id'    => 'required',
        ]);

        $checker = User::where('sponsor_id',$request->sponsor_id)->where('position',$request->position)->count();
        if($checker > 0) return  redirect()->back()->with('status', 'Позиция занята, проверьте, есть не активированный партнер в этой позиции');

        $package = Package::find($request->package_id);
        if (is_null($package))   $status_id = 1;
        else   $status_id = $package->rank;



        if ($status_id < $request->status_id){
            $status_id = $request->status_id;
        }


        $user = User::create([
            'name'          => $request->name,
            'number'        => $request->number,
            'email'         => $request->email,
            'gender'        => $request->gender,
            'birthday'      => $request->birthday,
            'address'       => $request->address,
            'password'      => bcrypt($request->password),
            'created_at'    => $request->created_at,
            'country_id'    => $request->country_id,
            'city_id'       => $request->city_id,
            'inviter_id'    => $request->inviter_id,
            'sponsor_id'    => $request->sponsor_id,
            'position'      => $request->position,
            'package_id'    => $request->package_id,
            'status_id'     => $status_id,
            'office_id'     => $request->office_id,
            'program_id'     =>  1,
        ]);


        if($request->package_id != 0){
            $package = Package::find($request->package_id);
            $cost = $package->cost + env('REGISTRATION_FEE');
            $package_id  = $package->id;
        }
        else $cost = env('REGISTRATION_FEE');

        $order =  Order::updateOrCreate(
            [
                'type' => 'register',
                'status' => 0,
                'payment' => 'manual',
                'uuid' => 0,
                'user_id' => $user->id,
            ],
            ['amount' => $cost, 'package_id' => $request->package_id]
        );

        event(new Activation($user = $user));

        Notification::create([
            'user_id'   => Auth::user()->id,
            'type'      => 'admin_register_user',
            'message'   => 'Зарегистрировал пользователя ' . $user->name . ' ( ' . $user->id . ' ) ',
        ]);

        return redirect('/user');
    }

    public function processing(Request $request)
    {
        $balance = Balance::getBalance(Auth::user()->id);
        $all = Balance::getIncomeBalance(Auth::user()->id);;
        $out = Balance::getBalanceOut(Auth::user()->id);
        $week = Balance::getWeekBalance(Auth::user()->id);
        $shop = 0;
        //$activation = Hierarchy::activationCheck();


        if(isset($request->weeks)){
            $first_transaction = Processing::whereUserId(Auth::user()->id)->orderby('id')->first();
            if(!is_null($first_transaction))
                $dateFromString = date('Y-m-d',strtotime($first_transaction->created_at));
            else $dateFromString = date('Y-m-d');
            $dateToString = date('Y-m-d');

            $weeks = Balance::getMondaysInRange($dateFromString, $dateToString);
            array_push($weeks, $dateToString);
            $weeks = array_reverse($weeks);

            return view('profile.processing.weeks', compact('weeks','balance', 'all', 'out','week','shop'));
        }


        $list = Processing::whereUserId(Auth::user()->id)->where('sum','!=','0')->where('pv', '!=', '0')->orderBy('id','desc')->paginate(100);


        return view('profile.processing.processing', compact('list', 'balance', 'all', 'out','week','shop'));
    }

    public function profile()
    {
        $feed = User::whereSponsorId(Auth::user()->id)->orderBy('created_at','desc')->get();
        $list = User::whereSponsorId(Auth::user()->id)->get();
        $balance = Balance::getBalance(Auth::user()->id);
        return view('profile.profile', compact('list','balance','feed'));
    }

    public function review($id = 0)
    {
        $review = $id ? Review::find($id) : null;
        if($review && $review->user()->first()->id !== Auth::user()->id) {
            return redirect()->back();
        }
        $products = Product::all();
        return view('profile.review', compact('review','products'));
    }

    public function commentAdd(Request $request, $id)
    {
        $request->validate([
            'message' => 'required'
        ],[
            'message.required' => 'Это поле обязательное!'
        ]);

        $review = Review::find($id);
        $comment = Comment::find($request->item_id);

        if($request->item_id && $comment && Auth::user()->id === $comment->user->id) {
            $comment->comment = $request->message;
            $comment->save();
        } else {
            $review->comments()->create([
                'comment' => $request->message,
                'user_id' => Auth::user()->id,
                'comment_id' => $request->comment_id,
            ]);
        }

        return redirect()->back();
    }

    public function my_reviews()
    {
        $user = Auth::user();
        $reviews = $user->reviews()->paginate(30);
        return view('review.my_reviews', compact('reviews', 'user'));
    }

    public function reviewsLike(Request $request)
    {
        $user = Auth::user();
        $review = Review::find($request->id);
        $review->user_likes()->toggle($user);
        $count = $review->user_likes()->count();
        return json_encode([
            'count' => $count
        ]);
    }

    public function commentsLike(Request $request)
    {
        $user = Auth::user();
        $comment = Comment::find($request->id);
        $comment->user_likes()->toggle($user);
        $count = $comment->user_likes()->count();
        return json_encode([
            'count' => $count
        ]);
    }

    public function updateReview(Request $request)
    {
        $request->validate( [
            'link'  => ['required', 'regex:/http(?:s?):\/\/(?:www\.)?youtu(?:be\.com\/watch\?v=|\.be\/)/'],
            //'description'   => 'required',
        ], [
            'link.regex' => 'Поле ссылка на youtube указана не верно!<br>Для примера: https://www.youtube.com/watch?v=yZkAv2RrXDw'
        ]);

        $reviews = $request->user_id ? User::find($request->user_id)->reviews() : Auth::user()->reviews();

        $review = $reviews->find($request->review_id);
        if(!$review)
            $review = $reviews->create();

        $review->update([
            'link_youtube' => $request->link,
            'description' => $request->description,
            'product_id' => $request->product_id,
            'approved' => $request->user_id ? $review->approved : NULL,
        ]);

        return redirect()->route($request->user_id ? 'admin_review_edit' : 'review_edit', ['id' => $review->id])->with('status', 'Успешно изменено');

    }

    public function updateReviewImage(Request $request){
        $reviews = $request->user_id ? User::find($request->user_id)->reviews() : Auth::user()->reviews();

        $review = $reviews->find($request->review_id);
        if(!$review)
            $review = $reviews->create();

        $tmp_path = date('Y') . "/" . date('m') . "/" . date('d') . "/" . time()  . '_' . $request->avatar->getFilename() . '.' . $request->avatar->getClientOriginalExtension();
        $path = $request->avatar->storeAs('public/images', $tmp_path);
        $request->avatar = str_replace("public/", "", $path);
        $review->image = $request->avatar;
        $review->save();

        return redirect()->route($request->user_id ? 'admin_review_edit' : 'review_edit', ['id' => $review->id])->with('status', 'Успешно изменено');
    }

    public function marketing()
    {
        $program = Program::whereId(Auth::user('program_id')->program_id)->first();
        return view('page.marketing', compact('program'));
    }

    public function tree($user_id)
    {
        $current_user = User::join('user_programs','users.id','=','user_programs.user_id')
                        ->where('users.id',$user_id)
                        ->first();

        $left_user = User::join('user_programs','users.id','=','user_programs.user_id')
            ->where('users.inviter_id',$current_user->user_id)
            ->where('users.position',1)
            ->where('users.status',1)
            ->first();

        if(!is_null($left_user)){
            $left_user_l = User::join('user_programs','users.id','=','user_programs.user_id')
                ->where('users.inviter_id',$left_user->user_id)
                ->where('users.position',1)
                ->where('users.status',1)
                ->first();
            $left_user_r =  User::join('user_programs','users.id','=','user_programs.user_id')
                ->where('users.inviter_id',$left_user->user_id)
                ->where('users.position',2)
                ->where('users.status',1)
                ->first();
        }
        else{
            $left_user_l = null;
            $left_user_r =   null;
        }

        $right_user = User::join('user_programs','users.id','=','user_programs.user_id')
            ->where('users.inviter_id',$current_user->user_id)
            ->where('users.position',2)
            ->where('users.status',1)
            ->first();

        if(!is_null($right_user)){
            $right_user_l = User::join('user_programs','users.id','=','user_programs.user_id')
                ->where('users.inviter_id',$right_user->user_id)
                ->where('users.position',1)
                ->where('users.status',1)
                ->first();
            $right_user_r =  User::join('user_programs','users.id','=','user_programs.user_id')
                ->where('users.inviter_id',$right_user->user_id)
                ->where('users.position',2)
                ->where('users.status',1)
                ->first();
        }
        else{
           $right_user_l = null;
           $right_user_r = null;
        }


        return view('profile.tree', compact('current_user','left_user','right_user','left_user_l','left_user_r','right_user_l','right_user_r'));

    }

    public function invitations()
    {
        $list = User::where('inviter_id',Auth::user()->id)->get();

        return view('profile.invitations', compact('list'));
    }

    public function hierarchy()
    {
        /* old*/
        /*$tree = Hierarchy::getTree(Auth::user()->id);
        return view('profile.hierarchy', compact('tree'));*/

        /* new*/

        /*$data = [];
        $data['value'] = Auth::user()->name;
        $data['children'] = Hierarchy::getNewTree(Auth::user()->id);
        $data = json_encode($data);
        return view('profile.hierarchy1', compact('data'));*/


        $user = Auth::user();
        $invitations = User::where('inviter_id',$user->id)->get();

        return view('profile.hierarchy2', compact('user','invitations'));
    }

    public function getInvitersHierarchy(Request $request)
    {
        $invitations = Hierarchy::inviterList($request->id);
        return view('profile.hierarchy-append', compact('invitations'));
    }


    public function hierarchyTree($id)
    {
        return response()->json(['value' => Auth::user()->name, 'children' => Hierarchy::getNewTree(Auth::user()->id)]);
    }

    public function team(Request $request)
    {
        if(isset($request->own)){
            $list = UserProgram::where('inviter_list','like','%,'.Auth::user()->id.',%')->paginate(30);
        }
        elseif(isset($request->upgrade)){
            $list = UserProgram::where('inviter_list','like','%,'.Auth::user()->id.',%')
                ->join('notifications','user_programs.user_id','=','notifications.user_id')
                ->where('notifications.type','user_upgraded');


            if (isset($request->status_id)){
                $list = $list->where('user_programs.status_id',$request->status_id);
            }

            if (isset($request->date)){
                $list = $list->where('notifications.created_at','>=',$request->date);
            }

            $list = $list->select(['user_programs.*','notifications.created_at as created_at'])->paginate(30);
        }
        elseif(isset($request->move)){
            $list = UserProgram::where('inviter_list','like','%,'.Auth::user()->id.',%')
                ->join('notifications','user_programs.user_id','=','notifications.user_id')
                ->where('notifications.type','move_status');


            if (isset($request->status_id)){
                $list = $list->where('user_programs.status_id',$request->status_id);
            }

            if (isset($request->date)){
                $list = $list->where('notifications.created_at','>=',$request->date);
            }

            $list = $list->select(['user_programs.*','notifications.created_at as created_at'])->paginate(30);
        }
        else{
            $list = UserProgram::where('inviter_list','like','%,'.Auth::user()->id.',%');

            if (isset($request->status_id ) &&  $request->status_id != 'Не указан'){

                $list = $list->where('user_programs.status_id',$request->status_id);
            }

            if (isset($request->date ) &&  !is_null($request->date)){
                $list = $list->where('user_programs.created_at','<=',$request->date);
            }

            if (isset($request->s ) &&  !is_null($request->s)){
                $list = $list->join('users','user_programs.user_id','=','users.id')
                    ->where('users.id_number','like','%'.$request->s.'%')
                    ->orWhere('users.name','like','%'.$request->s.'%')
                    ->select(['user_programs.*']);
            }

            $list = $list->paginate(30);
        }


        return view('profile.team', compact('list'));
    }

    public function updateAvatar(Request $request){
        $user = User::find(Auth::user()->id);

        $tmp_path = date('Y')."/".date('m')."/".date('d')."/".$request->avatar->getFilename().'.'.$request->avatar->getClientOriginalExtension();
        $path = $request->avatar->storeAs('public/images', $tmp_path);
        $request->avatar = str_replace("public", "storage", $path);
        $user->photo=$request->avatar;
        $user->save();

        return redirect()->back()->with('status', 'Успешно изменено');
    }

    public function updateProfile(Request$request)
    {

        $id = Auth::user()->id;

        $request->validate([
            //'name'          => 'required',
            'number'        => 'required',
            'email'         => ['required', 'string', 'email', 'max:255',"unique:users,email,$id"],
            'gender'        => 'required',
            'birthday'      => 'required',
            'country_id'    => 'required',
            'city_id'       => 'required',
            'address'       => 'required',
            'card'          => 'required',
            'bank'          => 'required',
        ],[
            'required' => 'Пожалуйста, заполните это поле.',
            'unique.exists' => 'Такие данные уже существует.'
        ]);


        $user = User::find(Auth::user()->id);

            if ($request->card !== $user->card) {
                DB::table('user_changes')->insert([
                    'new' => $request->card,
                    'old' => $user->card,
                    'type' => 1,
                    'user_id' => Auth::user()->id,
                ]);
            }

            if ($request->email !== $user->email) {
                DB::table('user_changes')->insert([
                    'new' => $request->email,
                    'old' => $user->email,
                    'type' => 2,
                    'user_id' => Auth::user()->id,
                ]);
            }

            if ($request->password !== null & $request->password !== "") {
                $password = bcrypt($request->password);
            } else {
                $password = $user->password;
            }

            User::whereId(Auth::user()->id)->update([
                //'name' => $request->name,
                'email' => $request->email,
                'number' => $request->number,
                'birthday' => $request->birthday,
                'country_id' => $request->country_id,
                'city_id' => $request->city_id,
                'address' => $request->address,
                'password' => $password,//change
                'card' => $request->card,//hostory
                'gender'        =>  $request->gender,
                'bank'          =>  $request->bank,
            ]);



            return redirect()->back()->with('status', 'Успешно изменено');

    }

    public function notifications()
    {
        $in = [];
        $in[] = Auth::user()->id;
        $list = \App\Models\UserProgram::where('list','like','%,'.Auth::user()->id.',%')->get();

        foreach ($list as $item){
            $in[] = $item->user_id;
        }

        $all = Notification::whereIn('user_id',$in)->where('type', 'NOT LIKE', 'admin_%')->paginate(30);
        return view('profile.notifications', compact('all'));
    }

    public function programs()
    {
        $orders = Order::where('user_id',Auth::user()->id)->where('type','upgrade')->where('payment','manual')->where('status','!=',4)->orderBy('id','desc')->first();

        $user_program = UserProgram::where('user_id',Auth::user()->id)->first();

        $current_package = Package::find($user_program->package_id);

        $packages_query = Package::where('status',1);

        if(!is_null($current_package)){
            $packages_query->where('pv','>',$current_package->pv);
        }

        $packages = $packages_query->get();

        $diff = Carbon::createFromFormat('Y-m-d H:i:s', $user_program->created_at)->diffInDays(Carbon::now());

        return view('profile.programs', compact('orders','packages','current_package','diff'));
    }

    public function fortuneWheel()
    {
        $list = FortuneWheel::orderBy('id','desc')->get();
        return view('profile.fortune_wheel', compact('list'));
    }

    public function fortuneWheelAccess($user_id)
    {
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

        if ($user_id == 2146) $amount_count = 2;
        if ($user_id == 4202) $amount_count = 2;
        if ($user_id == 1722) $amount_count = 2;

        $attempt_count = $check_invite_count + $amount_count - FortuneWheel::whereUserId($user_id)
                ->whereBetween('created_at', [Carbon::parse($date)->startOfMonth(), Carbon::parse($date)->endOfMonth()])
                ->count();

        return $attempt_count;

    }

    public function fortuneWheelAttempt($user_id, $success)
    {
        if($success == 7) $success_result = 1;
        else $success_result = 0;

        FortuneWheel::create([
            'user_id' => $user_id,
            'success' => $success_result
        ]);
    }

}
