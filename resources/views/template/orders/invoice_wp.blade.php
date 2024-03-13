@extends("template.layouts.master",['page_title'=>($invoice_route?'Order Invoice':'Order').$order->order_no])
@section("vendorCss")
    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/vendors/css/vendors.min.css") }}">
    <!-- END: Vendor CSS-->

@endsection
@section("pageCss")
    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/css/core/menu/menu-types/vertical-menu.css")}}">
    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/css/plugins/forms/form-validation.css")}}">
    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/css/pages/app-user.css")}}">
    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/vendors/css/forms/select/select2.min.css")}}">

    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/css-rtl/core/menu/menu-types/vertical-menu.css")}}">
    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/css-rtl/plugins/forms/pickers/form-flat-pickr.css")}}">
    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/css-rtl/pages/app-invoice.css")}}">
    <!-- END: Page CSS-->
@endsection
@section("main-content")
    <div class="content-header row">
    </div>
    <div class="content-body">

        <section class="invoice-preview-wrapper">
            <div class="row invoice-preview">
                <!-- Invoice -->
                <div class="col-xl-{{ $invoice_route?'9':'12' }} col-md-{{ $invoice_route?'8':'12' }} col-12">
                    <div class="card invoice-preview-card">
                        <div class="card-body invoice-padding pb-0">
                            <!-- Header starts -->
                            <div class="d-flex justify-content-between flex-md-row flex-column invoice-spacing mt-0">
                                <div>
                                    <div class="logo-wrapper">
                                        <svg viewBox="0 0 139 95" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" height="24">
                                            <defs>
                                                <linearGradient id="invoice-linearGradient-1" x1="100%" y1="10.5120544%" x2="50%" y2="89.4879456%">
                                                    <stop stop-color="#000000" offset="0%"></stop>
                                                    <stop stop-color="#FFFFFF" offset="100%"></stop>
                                                </linearGradient>
                                                <linearGradient id="invoice-linearGradient-2" x1="64.0437835%" y1="46.3276743%" x2="37.373316%" y2="100%">
                                                    <stop stop-color="#EEEEEE" stop-opacity="0" offset="0%"></stop>
                                                    <stop stop-color="#FFFFFF" offset="100%"></stop>
                                                </linearGradient>
                                            </defs>
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <g transform="translate(-400.000000, -178.000000)">
                                                    <g transform="translate(400.000000, 178.000000)">
                                                        <path class="text-primary" d="M-5.68434189e-14,2.84217094e-14 L39.1816085,2.84217094e-14 L69.3453773,32.2519224 L101.428699,2.84217094e-14 L138.784583,2.84217094e-14 L138.784199,29.8015838 C137.958931,37.3510206 135.784352,42.5567762 132.260463,45.4188507 C128.736573,48.2809251 112.33867,64.5239941 83.0667527,94.1480575 L56.2750821,94.1480575 L6.71554594,44.4188507 C2.46876683,39.9813776 0.345377275,35.1089553 0.345377275,29.8015838 C0.345377275,24.4942122 0.230251516,14.560351 -5.68434189e-14,2.84217094e-14 Z" style="fill: currentColor"></path>
                                                        <path d="M69.3453773,32.2519224 L101.428699,1.42108547e-14 L138.784583,1.42108547e-14 L138.784199,29.8015838 C137.958931,37.3510206 135.784352,42.5567762 132.260463,45.4188507 C128.736573,48.2809251 112.33867,64.5239941 83.0667527,94.1480575 L56.2750821,94.1480575 L32.8435758,70.5039241 L69.3453773,32.2519224 Z" fill="url(#invoice-linearGradient-1)" opacity="0.2"></path>
                                                        <polygon fill="#000000" opacity="0.049999997" points="69.3922914 32.4202615 32.8435758 70.5039241 54.0490008 16.1851325"></polygon>
                                                        <polygon fill="#000000" opacity="0.099999994" points="69.3922914 32.4202615 32.8435758 70.5039241 58.3683556 20.7402338"></polygon>
                                                        <polygon fill="url(#invoice-linearGradient-2)" opacity="0.099999994" points="101.428699 0 83.0667527 94.1480575 130.378721 47.0740288"></polygon>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg>
                                        <h3 class="text-primary invoice-logo">
                                            {{ env("APP_NAME") }}
                                        </h3>
                                    </div>
{{--                                    <p class="card-text mb-25">Office 149, 450 South Brand Brooklyn</p>--}}
{{--                                    <p class="card-text mb-25">San Diego County, CA 91905, USA</p>--}}
{{--                                    <p class="card-text mb-0">+1 (123) 456 7891, +44 (876) 543 2198</p>--}}
                                </div>
                                <div class="mt-md-0 mt-2">
                                    <h4 class="invoice-title">
                                        {{ $invoice_route?'Invoice':'Order' }}
                                        <span class="invoice-number">
                                            #{{ $order->order_no }}
                                        </span>
                                    </h4>
                                    <div class="invoice-date-wrapper">
                                        <p class="invoice-date-title">Order Date:</p>
                                        <p class="invoice-date">
                                            {{ date('d / m / Y h:i A',strtotime($order->date_created)) }}
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
                            <!-- Header ends -->
                        </div>

                        <hr class="invoice-spacing" />

                        <!-- Address and Contact starts -->
                        <div class="card-body invoice-padding pt-0">
                            <div class="row invoice-spacing">
                                <div class="col-xl-8 p-0">
                                    <h6 class="mb-2">Customer Note:</h6>
                                    <h6 class="mb-25">
                                        {{ ucwords($order->customer_note) ?: "N/A" }}
                                    </h6>
{{--                                    <p class="card-text mb-25">Shelby Company Limited</p>--}}
{{--                                    <p class="card-text mb-25">Small Heath, B10 0HF, UK</p>--}}
{{--                                    <p class="card-text mb-25">718-986-6062</p>--}}
{{--                                    <p class="card-text mb-0">peakyFBlinders@gmail.com</p>--}}
                                </div>
{{--                                <div class="col-xl-4 p-0 mt-xl-0 mt-2">--}}
{{--                                    <h6 class="mb-2">Payment Details:</h6>--}}
{{--                                    <table>--}}
{{--                                        <tbody>--}}
{{--                                        <tr>--}}
{{--                                            <td class="pr-1">Total Due:</td>--}}
{{--                                            <td><span class="font-weight-bold">$12,110.55</span></td>--}}
{{--                                        </tr>--}}
{{--                                        <tr>--}}
{{--                                            <td class="pr-1">Bank name:</td>--}}
{{--                                            <td>American Bank</td>--}}
{{--                                        </tr>--}}
{{--                                        <tr>--}}
{{--                                            <td class="pr-1">Country:</td>--}}
{{--                                            <td>United States</td>--}}
{{--                                        </tr>--}}
{{--                                        <tr>--}}
{{--                                            <td class="pr-1">IBAN:</td>--}}
{{--                                            <td>ETD95476213874685</td>--}}
{{--                                        </tr>--}}
{{--                                        <tr>--}}
{{--                                            <td class="pr-1">SWIFT code:</td>--}}
{{--                                            <td>BR91905</td>--}}
{{--                                        </tr>--}}
{{--                                        </tbody>--}}
{{--                                    </table>--}}
{{--                                </div>--}}
                            </div>
                        </div>
                        <!-- Address and Contact ends -->

                        <!-- Invoice Description starts -->
                        <div class="table-responsive">
                            <table class="table">
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

                        <div class="card-body invoice-padding pb-0">
                            <div class="row invoice-sales-total-wrapper">
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
                        </div>
                        <!-- Invoice Description ends -->

                        <hr class="invoice-spacing" />

                        <!-- Invoice Note starts -->
                        <div class="card-body invoice-padding pt-0">
                            <div class="row">
                                <div class="col-12">
                                    <span class="font-weight-bold">Note:</span>
                                    <span>Thanks for the Shopping</span>
                                </div>
                            </div>
                        </div>
                        <!-- Invoice Note ends -->
                    </div>
                </div>
                <!-- /Invoice -->

                <!-- Invoice Actions -->
                <div class="{{ $invoice_route?'':'d-none' }} col-xl-3 col-md-4 col-12 invoice-actions mt-md-0 mt-2">
                    <div class="card">
                        <div class="card-body">
                            <button class="btn btn-primary btn-block mb-75" data-toggle="modal" data-target="#send-invoice-sidebar">
                                Send Invoice
                            </button>
                            <a href="{{route('order.invoice.download.wp',$order->id)}}" class="btn btn-outline-secondary btn-block btn-download-invoice mb-75">Download</a>
                            <a class="btn btn-outline-secondary btn-block mb-75" {{--id="printInvoice"--}} href="{{ route("order.invoice.print.wp",$order->id) }}" target="_blank">
                                Print
                            </a>
                        </div>
                    </div>
                </div>
                <!-- /Invoice Actions -->
            </div>
        </section>

        <!-- Send Invoice Sidebar -->
            @include('template.layouts.views.sendInvoiceWp')
        <!-- /Send Invoice Sidebar -->

        <!-- Add Payment Sidebar -->
        <div class="modal modal-slide-in fade" id="add-payment-sidebar" aria-hidden="true">
            <div class="modal-dialog sidebar-lg">
                <div class="modal-content p-0">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                    <div class="modal-header mb-1">
                        <h5 class="modal-title">
                            <span class="align-middle">Add Payment</span>
                        </h5>
                    </div>
                    <div class="modal-body flex-grow-1">
                        <form>
                            <div class="form-group">
                                <input id="balance" class="form-control" type="text" value="Invoice Balance: 5000.00" disabled />
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="amount">Payment Amount</label>
                                <input id="amount" class="form-control" type="number" placeholder="$1000" />
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="payment-date">Payment Date</label>
                                <input id="payment-date" class="form-control date-picker" type="text" />
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="payment-method">Payment Method</label>
                                <select class="form-control" id="payment-method">
                                    <option value="" selected disabled>Select payment method</option>
                                    <option value="Cash">Cash</option>
                                    <option value="Bank Transfer">Bank Transfer</option>
                                    <option value="Debit">Debit</option>
                                    <option value="Credit">Credit</option>
                                    <option value="Paypal">Paypal</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="payment-note">Internal Payment Note</label>
                                <textarea class="form-control" id="payment-note" rows="5" placeholder="Internal Payment Note"></textarea>
                            </div>
                            <div class="form-group d-flex flex-wrap mb-0">
                                <button type="button" class="btn btn-primary mr-1" data-dismiss="modal">Send</button>
                                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Add Payment Sidebar -->
    </div>
@endsection
@section("pageVendorJs")
    <!-- BEGIN: Page Vendor JS-->
    <script src="{{ asset("app-assets/vendors/js/forms/validation/jquery.validate.min.js")}}"></script>
    <script src="{{ asset("app-assets/js/scripts/components/components-navs.js") }}"></script>
    <script src="{{ asset("app-assets/vendors/js/forms/select/select2.full.min.js")}}"></script>

    <script src="{{ asset("app-assets/js/scripts/pages/app-invoice.js")}}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery.print/1.6.2/jQuery.print.min.js"></script>
    <!-- END: Page Vendor JS-->
@endsection
@section("pageJsLower")
    <!-- BEGIN: Page JS-->
    {{--    <script src="{{ asset("app-assets/js/scripts/pages/app-user-list.js")}}"></script>--}}
    <!-- END: Page JS-->
    <script>
        $(document).ready(function (){
            $("#tags").select2({
                tags: true,
                tokenSeparators: [',', ' ']
            })
        })
    </script>
    <script>
        $('#printInvoice').on('click', function() {
            $.ajaxSetup({
                url: '{{ route("order.invoice.print",$order->id) }}',
                type: 'GET',
                beforeSend: function() {
                    console.log('printing ...');
                },
                complete: function() {
                    console.log('Sending Data....');
                }
            });

            $.ajax({
                success: function(viewContent) {
                    console.log('printed!');
                    $.print(viewContent); // This is where the script calls the printer to print the view's content.
                }
            });
        });
    </script>
@endsection
