<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class LocalizationController extends Controller
{
    public function index($lang){
        App::setLocale($lang);
        session()->put('locale', $lang);
        if ($user = Auth::user())
            $user->setPreferredLanguage($lang);
        return redirect()->back();
    }
}
