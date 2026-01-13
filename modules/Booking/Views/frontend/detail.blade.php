@extends('layouts.app')
@section('head')
    <link href="{{ asset('dist/frontend/module/booking/css/checkout.css?_ver='.config('app.version')) }}" rel="stylesheet">
@endsection
@section('content')
    <section class="inner_page_breadcrumb">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 offset-xl-3 text-center">
                    <div class="breadcrumb_content">
                        <h4 class="breadcrumb_title">{{__('ORDER')}}</h4>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url(app_get_locale())}}">{{__('Home')}}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{__('ORDER')}}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="shop-order">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 offset-xl-2">
                    <div class="shop_order_box">
                        <h4 class="main_title">{{__('Order')}}</h4>
                        <p class="text-center text-success">{{__('Thank you. Your order has been received.')}}</p>
                        <p class="text-danger text-center" style="font-size: 18px;">Please click below button to download Course Content</p>
                        <?php
                            $item_row = $booking->items->first();
                            $slug = $item_row->service->slug;
                        ?>
                        <p class="text-center"><a href="{{ route('course.detail', ['slug' => $slug]) }}" class="btn btn-success btn-sm">Video & PDF</a></p>
                        <div class="order_list_raw">
                            <ul>
                                <li class="list-inline-item">
                                    <h4>{{__('Order Number')}}</h4>
                                    <p>{{$booking->id}}</p>
                                </li>
                                <li class="list-inline-item">
                                    <h4>{{__('Date')}}</h4>
                                    <p>{{display_date($booking->create_date)}}</p>
                                </li>
                                <li class="list-inline-item">
                                    <h4>{{__('Total')}}</h4>
{{--                                    <p>{{format_money($booking->total)}}</p>--}}
                                    <p>
                                        {{ $booking->gateway == 'easypaisa' ? 'Rs ' . $booking->total : '$' . $booking->total }}
                                    </p>

                                </li>
                                <li class="list-inline-item">
                                    <h4>{{__('Payment Method')}}</h4>
                                    <p>
                                    @if(!empty($gateway))
                                    {{$gateway->name}}
                                    @endif</p>
                                </li>
                            </ul>
                        </div>

                        <div class="order_details">
                            <h4 class="title text-center mb40">{{__('Order Details')}}</h4>
                            <div class="od_content">
                                <ul>
                                    @foreach($booking->items as $item)
                                        <li>{{$item->service->title}}  × {{$item->qty}} <span class="float-right">{{ $booking->gateway == 'easypaisa' ? 'Rs ' . $booking->total : '$' . $item->subtotal }}</span></li>
                                    @endforeach
                                <li>{{__('Total')}} <span class="float-right tamount">{{ $booking->gateway == 'easypaisa' ? 'Rs ' . $booking->total : '$' . $booking->total }}</span></li>
    </ul>
</div>
<div class="od_details_contact text-center">
    <h4 class="title2">{{__('Billing Address')}}</h4>
    <p class="mb0">{{$booking->address}}</p>
    <p class="mb0">{{$booking->address2}}</p>
    <p class="mb0">{{$booking->phone}}</p>
    <p class="mb0">{{$booking->email}}</p>
    <p class="mb0">{{$booking->first_name}} {{$booking->last_name}}</p>
</div>
</div>
</div>
</div>
</div>
</div>
</section>
@endsection
@section('footer')
@endsection
