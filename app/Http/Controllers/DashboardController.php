<?php

namespace App\Http\Controllers;

use App\Collections\ReferralsCollection;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function render()
    {
        $referrals = new ReferralsCollection(Auth::user());
        return view('dashboard', [
            'referrals' => $referrals->referralsTree
        ]);
    }
}
