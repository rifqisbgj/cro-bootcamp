<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Checkout\Store;
use App\Models\Camp;
use Illuminate\Http\Request;
use App\Models\Checkout;
use App\Mail\Checkout\AfterCheckout;
use Exception;
use Illuminate\Support\Facades\Auth;
use Mail;
use Str;
use Midtrans;

class CheckoutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        Midtrans\Config::$serverKey = env('MIDTRANS_SERVERKEY');
        Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION');
        Midtrans\Config::$isSanitized = env('MIDTRANS_IS_SANITIZED');
        Midtrans\Config::$is3ds = env('MIDTRANS_IS_3DS');
    }

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Camp $camp, Request $request)
    {
        if ($camp->isRegistered) {
            $request->session()->flash('error', "You already registered on {$camp->title} camp.");
            return redirect(route('user.dashboard'));
        }
        return view('checkout.create', [
            'camp' => $camp
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Store $request, Camp $camp)
    {
        $data = $request->all();
        $data['user_id'] = Auth::id();
        $data['camp_id'] = $camp->id;

        // update occupation user
        $user = Auth::user();
        $user->occupation = $data['occupation'];
        $user->save();

        // Add checkout
        $checkout = Checkout::create($data);
        $this->getSnapDirect($checkout);

        // send email after checkout
        Mail::to(Auth::user()->email)->send(new AfterCheckout($checkout));
        return redirect(route('checkout.success'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function success()
    {
        return view('checkout.success_checkout');
    }

    public function getSnapDirect(Checkout $checkout)
    {
        // generate order ID
        $orderId = $checkout->id . '-' . Str::random(5);
        $price = $checkout->camp->price * 1000;
        $checkout->midtrans_booking_code = $orderId;

        $transaction_details = [
            'order_id' => $orderId,
            'gross_amount' => $price,

        ];

        $item_details = [
            'id' => $orderId,
            'price' => $price,
            'quantity' => 1,
            'name' => "Payment for {$checkout->camp->title} Camp",
        ];

        $userData = [
            'first_name' => $checkout->user->name,
            'last_name' => "",
            'address' => $checkout->user->address,
            'city' => "",
            'postal_code' => "",
            'phone' => $checkout->user->phone,
            'country_code' => "IDN"
        ];

        $cus_details = [
            'first_name' => $checkout->user->name,
            'last_name' => "",
            'email' => $checkout->user->email,
            'phone' => $checkout->user->phone,
            'billing_address' => $userData,
            'shipping_address' => $userData,
        ];

        $midtrans_param = [
            'transaction_details' => $transaction_details,
            'item_details' => $item_details,
            'customer_details' => $cus_details
        ];

        try {
            $paymentUrl = \Midtrans\Snap::createTransaction($midtrans_param)->redirect_url();
            $checkout->midtrans_url = $paymentUrl;
            $checkout->save();

            return $paymentUrl;
        } catch (Exception $th) {
            return false;
        }
    }

    public function midtransCallback()
    {
        $notif = new Midtrans\Notification();

        $transaction_status = $notif->transaction_status;
        $fraud = $notif->fraud_status;

        $checkout_id = explode('-', $notif->order_id);
        $checkout = Checkout::find($checkout_id);

        if ($transaction_status == 'capture') {
            if ($fraud == 'challenge') {
                // TODO Set payment status in merchant's database to 'challenge'
                $checkout->payment_status = 'pending';
            } else if ($fraud == 'accept') {
                // TODO Set payment status in merchant's database to 'success'
                $checkout->payment_status = 'paid';
            }
        } else if ($transaction_status == 'cancel') {
            if ($fraud == 'challenge') {
                // TODO Set payment status in merchant's database to 'failure'
                $checkout->payment_status = 'failed';
            } else if ($fraud == 'accept') {
                // TODO Set payment status in merchant's database to 'failure'
                $checkout->payment_status = 'failed';
            }
        } else if ($transaction_status == 'deny') {
            // TODO Set payment status in merchant's database to 'failure'
            $checkout->payment_status = 'failed';
        } else if ($transaction_status == 'settlement') {
            // TODO set payment status in merchant's database to 'Settlement'
            $checkout->payment_status = 'paid';
        } else if ($transaction_status == 'pending') {
            // TODO set payment status in merchant's database to 'Pending'
            $checkout->payment_status = 'pending';
        } else if ($transaction_status == 'expire') {
            // TODO set payment status in merchant's database to 'expire'
            $checkout->payment_status = 'failed';
        }

        $checkout->save();
        return redirect(route('checkout.success'));
    }
}
