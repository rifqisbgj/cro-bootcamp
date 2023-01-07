<?php

namespace App\Http\Controllers;

use App\Models\Checkout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    //
    public function dashboard()
    {
        $ck = Checkout::with('camp')->whereUserId(Auth::id())->get();
        return view('user.dashboard', compact('ck'));
    }
}
