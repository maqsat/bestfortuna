<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class WebController extends Controller
{

    public function welcome()
    {
        return view('web.welcome');
    }

    public function about()
    {
        return view('web.about');
    }

    public function documents()
    {
        return view('web.documents');
    }

    public function contacts()
    {
        return view('web.contacts');
    }

    public function webNews()
    {
        return view('web.news');
    }

    public function newsInner($id)
    {
        return view('web.news-inner');
    }

    public function marketing()
    {
        return view('web.marketing');
    }

    public function business()
    {
        return view('web.business');
    }

    public function benefits()
    {
        return view('web.benefits');
    }

    public function promotion()
    {
        return view('web.promotion');
    }

    public function rules()
    {
        return view('web.rules');
    }



    public function products()
    {
        return view('web.products');
    }





    public function reviews()
    {
        $this->lang();

        $user = Auth::user();
        $reviews = Review::with('user','user_likes')->where('approved', '=', 1)->get();
        return view('review.reviews', compact('reviews', 'user'));
    }

    private function lang() {
        if(!isset($_GET['lang']))
            $_GET['lang'] = 'ru';

        if (! in_array($_GET['lang'], ['en', 'ru', 'kz'])) {
            abort(400);
        }

        App::setLocale($_GET['lang']);
    }

    public function review($id)
    {
        $this->lang();

        $user = Auth::user();
        $review = Review::with('user','user_likes')->where('approved', '=', 1)->where('id', $id)->first();

        if(!$review)
            return response()->redirectTo('reviews');

        return view('review.review', compact('review', 'user'));
    }




    public function faq()
    {
        $faq=Faq::where('is_admin','0')->get();
        return view('page.faq',compact('faq'));
    }
}
