@extends('layouts.app')

@section('title')
    My Dashboard
@endsection

@section('content')
    <section class="dashboard my-5">
        <div class="container">
            <div class="row text-left">
                <div class=" col-lg-12 col-12 header-wrap mt-4">
                    <p class="story">
                        DASHBOARD
                    </p>
                    <h2 class="primary-header ">
                        My Bootcamps
                    </h2>
                </div>
            </div>
            <div class="row my-5">
                @include('components.alert')
                <table class="table">
                    <tbody>
                        @forelse ($ck as $co)
                            <tr class="align-middle">
                                <td width="18%">
                                    <img src="/assets/images/item_bootcamp.png" height="120" alt="">
                                </td>
                                <td>
                                    <p class="mb-2">
                                        <strong>{{ $co->camp->title }}</strong>
                                    </p>
                                    <p>
                                        {{ $co->created_at->format('M d, Y') }}
                                    </p>
                                </td>
                                <td>
                                    <strong>{{ $co->camp->price }}.000</strong>
                                </td>
                                <td>
                                    <strong>
                                        @if ($co->is_paid)
                                            <strong class="text-green">Payment Success</strong>
                                        @else
                                            <strong>Waiting for Payment</strong>
                                        @endif
                                    </strong>
                                </td>
                                <td>
                                    <a href="https://wa.me" class="btn btn-primary">
                                        Contact Support
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">No Data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
