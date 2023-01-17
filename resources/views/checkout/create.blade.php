@extends('layouts.app')

@section('title')
    Checkout Camp
@endsection

@section('content')
    <section class="checkout">
        <div class="container">
            <div class="row text-center pb-70">
                <div class="col-lg-12 col-12 header-wrap">
                    <p class="story">
                        YOUR FUTURE CAREER
                    </p>
                    <h2 class="primary-header">
                        Start Invest Today
                    </h2>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-9 col-12">
                    <div class="row">
                        <div class="col-lg-5 col-12">
                            <div class="item-bootcamp">
                                <img src="/assets/images/item_bootcamp.png" alt="" class="cover">
                                <h1 class="package text-uppercase">
                                    {{ $camp->title }}
                                </h1>
                                <p class="description">
                                    Bootcamp ini akan mengajak Anda untuk belajar penuh mulai dari pengenalan dasar sampai
                                    membangun sebuah projek asli
                                </p>
                            </div>
                        </div>
                        <div class="col-lg-1 col-12"></div>
                        <div class="col-lg-6 col-12">
                            <form action="{{ route('checkout.store', $camp->id) }}" method="POST" class="basic-form">
                                @csrf
                                <div class="mb-4">
                                    <label for="exampleInputEmail1" class="form-label">Occupation</label>
                                    <input type="text" name="occupation"
                                        class="form-control {{ $errors->has('occupation') ? 'is-invalid' : '' }}"
                                        value="{{ old('occupation') ?: Auth::user()->occupation }}">
                                    @if ($errors->has('occupation'))
                                        <p class="text-danger">{{ $errors->first('occupation') }}</p>
                                    @endif
                                </div>
                                <div class="mb-4">
                                    <label for="exampleInputEmail1" class="form-label">Phone Number</label>
                                    <input type="text" name="phone"
                                        class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}"
                                        value="{{ old('phone') ?: Auth::user()->phone }}">
                                    @if ($errors->has('phone'))
                                        <p class="text-danger">{{ $errors->first('phone') }}</p>
                                    @endif
                                </div>
                                <div class="mb-4">
                                    <label for="exampleInputEmail1" class="form-label">Address</label>
                                    <textarea name="address" class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}" id=""
                                        cols="30" rows="10">{{ old('address') ?: Auth::user()->address }}</textarea>
                                    @if ($errors->has('address'))
                                        <p class="text-danger">{{ $errors->first('address') }}</p>
                                    @endif
                                </div>
                                <button type="submit" class="w-100 btn btn-primary">Pay Now</button>
                                <p class="text-center subheader mt-4">
                                    <img src="/assets/images/ic_secure.svg" alt=""> Your payment is secure and
                                    encrypted.
                                </p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
