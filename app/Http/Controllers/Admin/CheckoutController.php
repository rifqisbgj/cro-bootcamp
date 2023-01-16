<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Checkout;
use Mail;
use App\Mail\Checkout\Paid;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function update(Request $req, Checkout $checkout)
    {
        $checkout->is_paid = true;
        $checkout->save();

        // send email to user
        Mail::to($checkout->user->email)->send(new Paid($checkout));

        $req->session()->flash('success',"Checkout with ID {$checkout->id} has been updated");
        return redirect(route('admin.dashboard'));
    }
}
