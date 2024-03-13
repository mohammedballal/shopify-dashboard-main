<!DOCTYPE html>
<html class="loading dark-layout" lang="en" data-layout="dark-layout" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
    <title>Invoice Print - {{ env("APP_NAME") }} </title>
    <link rel="apple-touch-icon" href="{{asset('app-assets/images/ico/apple-icon-120.png')}}">
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('app-assets/images/ico/favicon.ico')}}">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/vendors.min.css')}}">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/bootstrap-extended.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/colors.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/components.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/themes/dark-layout.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/themes/bordered-layout.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/themes/semi-dark-layout.css')}}">

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/core/menu/menu-types/vertical-menu.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/pages/app-invoice-print.css')}}">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/style.css')}}">
    <!-- END: Custom CSS-->

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern blank-page navbar-floating footer-static" data-open="click" data-menu="vertical-menu-modern" data-col="blank-page">
@foreach($orders as $order)
    <!-- BEGIN: Content-->
    <div class="app-content content mb-5">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                    @php
                        $items = [];
                        $api_response = json_decode($order->order_object);
                        //        dd($api_response);
                        $order->tax = $api_response->total_tax;
                        $order->sub_total = 0;
                        $order->discount = $api_response->discount_total;
                        $order->store_currency = $api_response->currency;
                        $order->total = $api_response->total;
                        $order->customer_note = $api_response->customer_note;
                        foreach ($api_response->line_items as $item) {
                            $order->sub_total+=$item->price*$item->quantity;
                            $items[] = [
                                'name'=>$item->name,
                                'variant'=>'',
                                'rate'=>$item->price,
                                'quantity'=>$item->quantity,
                                'currency'=>$order->currency
                            ];
                        }
                    @endphp
                    <div class="invoice-print p-3">
                        <div class="d-flex justify-content-between flex-md-row flex-column pb-2">
                            <div>
                                <div class="d-flex mb-1">
                                    <svg viewBox="0 0 139 95" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" height="24">
                                        <defs>
                                            <linearGradient id="linearGradient-1" x1="100%" y1="10.5120544%" x2="50%" y2="89.4879456%">
                                                <stop stop-color="#000000" offset="0%"></stop>
                                                <stop stop-color="#FFFFFF" offset="100%"></stop>
                                            </linearGradient>
                                            <linearGradient id="linearGradient-2" x1="64.0437835%" y1="46.3276743%" x2="37.373316%" y2="100%">
                                                <stop stop-color="#EEEEEE" stop-opacity="0" offset="0%"></stop>
                                                <stop stop-color="#FFFFFF" offset="100%"></stop>
                                            </linearGradient>
                                        </defs>
                                        <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <g id="Artboard" transform="translate(-400.000000, -178.000000)">
                                                <g id="Group" transform="translate(400.000000, 178.000000)">
                                                    <path class="text-primary" id="Path" d="M-5.68434189e-14,2.84217094e-14 L39.1816085,2.84217094e-14 L69.3453773,32.2519224 L101.428699,2.84217094e-14 L138.784583,2.84217094e-14 L138.784199,29.8015838 C137.958931,37.3510206 135.784352,42.5567762 132.260463,45.4188507 C128.736573,48.2809251 112.33867,64.5239941 83.0667527,94.1480575 L56.2750821,94.1480575 L6.71554594,44.4188507 C2.46876683,39.9813776 0.345377275,35.1089553 0.345377275,29.8015838 C0.345377275,24.4942122 0.230251516,14.560351 -5.68434189e-14,2.84217094e-14 Z" style="fill: currentColor"></path>
                                                    <path id="Path1" d="M69.3453773,32.2519224 L101.428699,1.42108547e-14 L138.784583,1.42108547e-14 L138.784199,29.8015838 C137.958931,37.3510206 135.784352,42.5567762 132.260463,45.4188507 C128.736573,48.2809251 112.33867,64.5239941 83.0667527,94.1480575 L56.2750821,94.1480575 L32.8435758,70.5039241 L69.3453773,32.2519224 Z" fill="url(#linearGradient-1)" opacity="0.2"></path>
                                                    <polygon id="Path-2" fill="#000000" opacity="0.049999997" points="69.3922914 32.4202615 32.8435758 70.5039241 54.0490008 16.1851325"></polygon>
                                                    <polygon id="Path-21" fill="#000000" opacity="0.099999994" points="69.3922914 32.4202615 32.8435758 70.5039241 58.3683556 20.7402338"></polygon>
                                                    <polygon id="Path-3" fill="url(#linearGradient-2)" opacity="0.099999994" points="101.428699 0 83.0667527 94.1480575 130.378721 47.0740288"></polygon>
                                                </g>
                                            </g>
                                        </g>
                                    </svg>
                                    <h3 class="text-primary font-weight-bold ml-1">
                                        {{ env("APP_NAME") }}
                                    </h3>
                                </div>
{{--                                <p class="mb-25">Office 149, 450 South Brand Brooklyn</p>--}}
{{--                                <p class="mb-25">San Diego County, CA 91905, USA</p>--}}
{{--                                <p class="mb-0">+1 (123) 456 7891, +44 (876) 543 2198</p>--}}
                            </div>
                            <div class="mt-md-0 mt-2">
                                <h4 class="invoice-title">
                                    Invoice
                                    <span class="invoice-number">
                                                #{{ $order->order_no }}
                                            </span>
                                </h4>
                                <div class="invoice-date-wrapper">
                                    <p class="invoice-date-title">Order Date:</p>
                                    <p class="invoice-date">
                                        {{ date('d / m / Y h:i A',strtotime($order->order_date)) }}
                                    </p>
                                </div>
                                <div class="invoice-date-wrapper">
                                    <p class="invoice-date-title">Order Status:</p>
                                    @if($order->status == "completed")
                                        <p class="invoice-date badge badge-pill text-capitalized btn btn-sm rounded-pill btn-success">{{ strtoupper($order->status) }}</p>
                                    @else
                                        <p class="invoice-date badge badge-pill text-capitalized btn btn-sm rounded-pill btn-warning">{{ strtoupper($order->status) }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <hr class="my-2" />

                        <div class="row pb-2">
                            <div class="col-sm-6">
                                <h6 class="mb-1">Customer Note:</h6>
                                <p class="mb-25">{{ ucwords($order->customer_note) }}</p>
                                {{--                        <p class="mb-25">Shelby Company Limited</p>--}}
                                {{--                        <p class="mb-25">Small Heath, B10 0HF, UK</p>--}}
                                {{--                        <p class="mb-25">718-986-6062</p>--}}
                                {{--                        <p class="mb-0">peakyFBlinders@gmail.com</p>--}}
                            </div>
                            {{--                    <div class="col-sm-6 mt-sm-0 mt-2">--}}
                            {{--                        <h6 class="mb-1">Payment Details:</h6>--}}
                            {{--                        <table>--}}
                            {{--                            <tbody>--}}
                            {{--                            <tr>--}}
                            {{--                                <td class="pr-1">Total Due:</td>--}}
                            {{--                                <td><strong>$12,110.55</strong></td>--}}
                            {{--                            </tr>--}}
                            {{--                            <tr>--}}
                            {{--                                <td class="pr-1">Bank name:</td>--}}
                            {{--                                <td>American Bank</td>--}}
                            {{--                            </tr>--}}
                            {{--                            <tr>--}}
                            {{--                                <td class="pr-1">Country:</td>--}}
                            {{--                                <td>United States</td>--}}
                            {{--                            </tr>--}}
                            {{--                            <tr>--}}
                            {{--                                <td class="pr-1">IBAN:</td>--}}
                            {{--                                <td>ETD95476213874685</td>--}}
                            {{--                            </tr>--}}
                            {{--                            <tr>--}}
                            {{--                                <td class="pr-1">SWIFT code:</td>--}}
                            {{--                                <td>BR91905</td>--}}
                            {{--                            </tr>--}}
                            {{--                            </tbody>--}}
                            {{--                        </table>--}}
                            {{--                    </div>--}}
                        </div>

                        <div class="table-responsive mt-2">
                            <table class="table m-0">
                                <thead>
                                <tr>
                                    <th class="py-1">Item</th>
                                    <th class="py-1 text-center">Rate</th>
                                    <th class="py-1 text-center">Quantity</th>
                                    <th class="py-1 text-center">Line Total</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($items as $item)
                                    <tr>
                                        <td class="py-1">
                                            <p class="card-text font-weight-bold mb-25">
                                                {!! htmlspecialchars_decode(ucwords($item['name'])) !!}
                                            </p>
                                            <p class="card-text text-nowrap">
                                                {{ ucwords($item['variant']) }}
                                            </p>
                                        </td>
                                        <td class="py-1 text-center">
                                                <span class="font-weight-bold">
                                                    {{ strtoupper($item['currency'])." ".$item['rate'] }}
                                                </span>
                                        </td>
                                        <td class="py-1 text-center">
                                                <span class="font-weight-bold">
                                                    {{ $item['quantity'] }}
                                                </span>
                                        </td>
                                        <td class="py-1 text-center">
                                                <span class="font-weight-bold">
                                                    {{ strtoupper($item['currency'])." ".$item['rate'] * $item['quantity'] }}
                                                </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4">
                                            No Item Found in Order
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="row invoice-sales-total-wrapper mt-3">

                            <div class="col-md-6 order-md-1 order-2 mt-md-0 mt-3">
                                <p class="card-text mb-0">
                                    @if(@$order->user)
                                        <span class="font-weight-bold">Salesperson:</span> <span class="ml-75">
                                                    {{ $order->user->name  }}
                                                </span>
                                    @endif
                                </p>
                            </div>

                            <div class="col-md-6 d-flex justify-content-end order-md-2 order-1">
                                <div class="invoice-total-wrapper">
                                    <div class="invoice-total-item">
                                        <p class="invoice-total-title">Subtotal:</p>
                                        <p class="invoice-total-amount">
                                            {{ $order->store_currency." ".$order->sub_total }}
                                        </p>
                                    </div>
                                    <div class="invoice-total-item">
                                        <p class="invoice-total-title">Discount:</p>
                                        <p class="invoice-total-amount">
                                            {{ $order->store_currency." ".$order->discount }}
                                        </p>
                                    </div>
                                    <div class="invoice-total-item">
                                        <p class="invoice-total-title">Tax:</p>
                                        <p class="invoice-total-amount">
                                            {{ $order->store_currency." ".$order->tax }}
                                        </p>
                                    </div>
                                    <hr class="my-50" />
                                    <div class="invoice-total-item">
                                        <p class="invoice-total-title">Grand Total:</p>
                                        <p class="invoice-total-amount">
                                            {{ $order->store_currency." ".($order->total) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-2" />

                        <div class="row">
                            <div class="col-12">
                                <span class="font-weight-bold">Note:</span>
                                <span>Thanks for the Shopping</span>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
    <!-- END: Content-->
@endforeach

<!-- BEGIN: Vendor JS-->
<script src="{{asset('app-assets/vendors/js/vendors.min.js')}}"></script>
<!-- BEGIN Vendor JS-->

<!-- BEGIN: Page Vendor JS-->
<!-- END: Page Vendor JS-->

<!-- BEGIN: Theme JS-->
<script src="{{asset('app-assets/js/core/app-menu.js')}}"></script>
<script src="{{asset('app-assets/js/core/app.js')}}"></script>
<!-- END: Theme JS-->

<!-- BEGIN: Page JS-->
{{--<script src="{{asset('}}app-assets/js/scripts/pages/app-invoice-print.js"></script>--}}
<!-- END: Page JS-->

<script>
    $(window).on('load', function() {
        if (feather) {
            feather.replace({
                width: 14,
                height: 14
            });
        }

    })

</script>
</body>
<!-- END: Body-->
<script>
$(document).ready(function () {
    setTimeout(function () {
        window.print();
        window.onafterprint = function () {
            window.close()
        }
    },2000)
})
</script>
</html>
