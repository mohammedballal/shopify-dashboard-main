@extends("template.layouts.master",['page_title'=>"Tickets List"])
@section("vendorCss")
    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/vendors/css/vendors.min.css") }}">
    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/vendors/css/calendars/fullcalendar.min.css") }}">
    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/vendors/css/forms/select/select2.min.css") }}">
    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css") }}">
    <!-- END: Vendor CSS-->

@endsection
@section("pageCss")
    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/css/core/menu/menu-types/vertical-menu.css") }}">
    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/css/plugins/forms/pickers/form-flat-pickr.css") }}">
    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/css/pages/app-calendar.css") }}">
    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/css/plugins/forms/form-validation.css") }}">
    <!-- END: Page CSS-->
@endsection
@section("main-content")
    <div class="content-header row">
    </div>
    <div class="content-body">
        <!-- Full calendar start -->
        <section>
            <div class="app-calendar overflow-hidden border">
                <div class="row no-gutters">
                    <!-- Sidebar -->
                    <div class="col app-calendar-sidebar flex-grow-0 overflow-hidden d-flex flex-column" id="app-calendar-sidebar">
                        <div class="sidebar-wrapper">
                            <div class="card-body justify-content-center">
                                @if(auth()->user()->hasRole('Super Admin'))
                                    <button class="btn btn-info btn-toggle-sidebar m-25 w-100" data-toggle="modal" data-target="#add-new-label-sidebar">
                                        <span class="align-middle">Add Label</span>
                                    </button>
                                @endif
                                <button class="btn btn-primary btn-toggle-sidebar m-25 w-100" data-toggle="modal" data-target="#add-new-event-sidebar">
                                    <span class="align-middle">Add Event</span>
                                </button>
                            </div>
                            <div class="card-body pb-0">
                                <h5 class="section-label mb-1">
                                    <span class="align-middle">Filter</span>
                                </h5>
                                <div class="custom-control custom-checkbox mb-1">
                                    <input type="checkbox" class="custom-control-input select-all" id="select-all" checked />
                                    <label class="custom-control-label" for="select-all">View All</label>
                                </div>
                                <div class="calendar-events-filter">
                                    @forelse($labels as $label)
                                        <style>
                                            .custom-control-dangers-{{$label->id}} .custom-control-input:checked ~ .custom-control-label::before, .custom-control-dangers-{{$label->id}} .custom-control-input:active ~ .custom-control-label::before {
                                                border-color: {{$label->color}}!important;
                                                background-color: {{$label->color}}!important;
                                            }
                                            .custom-control-dangers-{{$label->id}} .custom-control-input:disabled:checked ~ .custom-control-label::before {
                                                background-color: {{$label->color}} !important;
                                            }
                                        </style>
                                        <div class="custom-control custom-control-dangers-{{$label->id}} custom-checkbox mb-1">
                                            <input type="checkbox" class="custom-control-input input-filter" id="{{strtolower(str_replace(' ','-',$label->name))}}" data-value="{{strtolower(str_replace(' ','-',$label->name))}}" checked />
                                            <label class="custom-control-label" for="{{strtolower(str_replace(' ','-',$label->name))}}">{{$label->name}}</label>
                                        </div>
                                    @empty
                                        <div class="custom-control custom-control-danger custom-checkbox mb-1">
                                            <input type="checkbox" class="custom-control-input input-filter" id="no" checked />
                                            <label class="custom-control-label" for="no">No Labels Added</label>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                        <div class="mt-auto">
                            <img src="{{asset('app-assets/images/pages/calendar-illustration.png')}}" alt="Calendar illustration" class="img-fluid" />
                        </div>
                    </div>
                    <!-- /Sidebar -->

                    <!-- Calendar -->
                    <div class="col position-relative">
                        <div class="card shadow-none border-0 mb-0 rounded-0">
                            <div class="card-body pb-0">
                                <div id="calendar"></div>
                            </div>
                        </div>
                    </div>
                    <!-- /Calendar -->
                    <div class="body-content-overlay"></div>
                </div>
            </div>
            <!-- Calendar Add/Update/Delete event modal-->
        @include('template.layouts.views.addEvent')
        @include('template.layouts.views.addLabel')
        <!--/ Calendar Add/Update/Delete event modal-->
        </section>
        <!-- Full calendar end -->

    </div>
@endsection
@section("pageVendorJs")
    <!-- BEGIN: Page Vendor JS-->
    <script src="{{asset('app-assets/vendors/js/calendar/fullcalendar.min.js')}}"></script>
    <script src="{{asset('app-assets/vendors/js/extensions/moment.min.js')}}"></script>
    <script src="{{asset('app-assets/vendors/js/forms/select/select2.full.min.js')}}"></script>
    <script src="{{asset('app-assets/vendors/js/forms/validation/jquery.validate.min.js')}}"></script>
    <script src="{{asset('app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js')}}"></script>
    <!-- END: Page Vendor JS-->
@endsection
@section("pageJsLower")
    <script src="{{asset('app-assets/js/core/app-menu.js')}}"></script>
    <script src="{{asset('app-assets/js/core/app.js')}}"></script>
    <!-- END: Theme JS-->
    <script>
        let calenderColors = {
            @foreach($labels as $label)
                '{{$label->name}}': '{{$label->color}}',
            @endforeach
        };
        let listUrl = '{{route('events.list')}}';
    </script>
    <!-- BEGIN: Page JS-->
    <script src="{{asset('app-assets/js/scripts/pages/app-calendar-events.js')}}"></script>
    <script src="{{asset('app-assets/js/scripts/pages/app-calendar.js')}}"></script>
    <!-- END: Page JS-->
    <script>
        $(document).ready(function () {
            $('.table-hover').removeClass('table-hover');
        });
    </script>
@endsection
