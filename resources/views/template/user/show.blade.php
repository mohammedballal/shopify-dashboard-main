@extends("template.layouts.master",['page_title'=>"Show System User Orders"])
@section("vendorCss")
    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/vendors/css/vendors.min.css") }}">
    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css") }}">
    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/vendors/css/tables/datatable/responsive.bootstrap4.min.css")}}">
    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/vendors/css/tables/datatable/buttons.bootstrap4.min.css")}}">
    <!-- END: Vendor CSS-->

@endsection
@section("pageCss")
    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/css/core/menu/menu-types/vertical-menu.css")}}">
    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/css/plugins/forms/form-validation.css")}}">
    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/css/pages/app-invoice-list.css") }}">

    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/css/pages/app-user.css")}}">
    <!-- END: Page CSS-->
@endsection
@section("main-content")
    <div class="content-header row">
    </div>
    <div class="content-body">
        <section class="app-user-view">
            <!-- User Card & Plan Starts -->
            <div class="row">
                <!-- User Card starts-->
                <div class="col-xl-12 col-lg-12 col-md-12">
                    <div class="card user-card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-6 col-lg-12 d-flex flex-column justify-content-between border-container-lg">
                                    <div class="user-avatar-section">
                                        <div class="d-flex justify-content-start">
                                            @if(isset($user->avatar))
                                                <img class="img-fluid rounded" src="{{asset('media/users/avatar/'.$user->avatar)}}" height="104" width="104" alt="{{$user->name}} - avatar" />
                                            @else
                                            <img class="img-fluid rounded" src="{{asset('app-assets/images/avatars/'.rand(1,12).'.png')}}" height="104" width="104" alt="{{$user->name}} - avatar" />
                                            @endif
                                            <div class="d-flex flex-column ml-1">
                                                <div class="user-info mb-1">
                                                    <h4 class="mb-0">{{ ucwords($user->first_name." ".$user->last_name) }}</h4>
                                                    <span class="card-text">{{ ($user->email) }}</span>
                                                </div>
{{--                                                <div class="d-flex flex-wrap">--}}
{{--                                                    <a href="./app-user-edit.html" class="btn btn-primary">Edit</a>--}}
{{--                                                    <button class="btn btn-outline-danger ml-1">Delete</button>--}}
{{--                                                </div>--}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center user-total-numbers">
                                        <div class="d-flex align-items-center mr-2">
                                            <div class="color-box bg-light-primary">
                                                <i class="icon" data-feather="dollar-sign" class="text-primary"></i>
                                            </div>
                                            <div class="ml-1">
                                                <h5 class="mb-0">23.3k</h5>
                                                <small>Monthly Sales</small>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <div class="color-box bg-light-success">
                                                <i class="icon" data-feather="trending-up" class="text-success"></i>
                                            </div>
                                            <div class="ml-1">
                                                <h5 class="mb-0">$99.87K</h5>
                                                <small>Annual Profit</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-12 mt-2 mt-xl-0">
                                    <div class="user-info-wrapper">
                                        <div class="d-flex flex-wrap">
                                            <div class="user-info-title">
                                                <i data-feather="user" class="mr-1 icon"></i>
                                                <span class="card-text user-info-title font-weight-bold mb-0">Full Name</span>
                                            </div>
                                            <p class="card-text mb-0">{{ ucwords($user->name) }}</p>
                                        </div>
                                        <div class="d-flex flex-wrap my-50">
                                            <div class="user-info-title">
                                                <i data-feather="check" class="mr-1 icon"></i>
                                                <span class="card-text user-info-title font-weight-bold mb-0">Tag ID</span>
                                            </div>
                                            <p class="card-text mb-0">
                                                @forelse(json_decode($user->tag_id)??array() as $tag)
                                                    <button class="btn btn-sm btn-info rounded-pill">{{$tag}}</button>
                                                @empty
                                                    N/A
                                                @endforelse
                                            </p>
                                        </div>
                                        <div class="d-flex flex-wrap my-50">
                                            <div class="user-info-title">
                                                <i data-feather="check" class="mr-1 icon"></i>
                                                <span class="card-text user-info-title font-weight-bold mb-0">Status</span>
                                            </div>
                                            <p class="card-text mb-0">{{ $user->status?"Active":"Inactive" }}</p>
                                        </div>
                                        <div class="d-flex flex-wrap my-50">
                                            <div class="user-info-title">
                                                <i data-feather="star" class="mr-1 icon"></i>
                                                <span class="card-text user-info-title font-weight-bold mb-0">Role</span>
                                            </div>
                                            <p class="card-text mb-0">{{ ucwords($user->roles->first()->name) }}</p>
                                        </div>
                                        <div class="d-flex flex-wrap">
                                            <div class="user-info-title">
                                                <i data-feather="phone" class="mr-1 icon"></i>
                                                <span class="card-text user-info-title font-weight-bold mb-0">Contact</span>
                                            </div>
                                            <p class="card-text mb-0">(123) 456-7890</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /User Card Ends-->
            </div>
            <!-- User Card & Plan Ends -->
        </section>

        <!-- users list start -->
        <section class="app-user-list">
            <!-- users filter start -->
            <div class="card">
                <h5 class="card-header">Search Filter</h5>
                <div class="d-flex justify-content-between align-items-center mx-50 row pt-0 pb-2">
                    <div class="col-md-4 user_role"></div>
                    <div class="col-md-4 user_plan"></div>
                    <div class="col-md-4 user_status"></div>
                </div>
            </div>
            <!-- users filter end -->
            <!-- list section start -->
            <div class="card">
                <div class="card-datatable table-responsive pt-0">
                    <table class="user-list-table table">
                        <thead class="thead-light">
                        <tr>
                            <th></th>
                            <th>Order</th>
                            <th>Order Date</th>
                            <th>Customer</th>
                            {{--                            <th>Currency</th>--}}
                            <th>Total</th>
                            <th>Total USD</th>
                            <th>Payment</th>
                            <th>Fulfillment</th>
                            <th>Items</th>
                            <th>Delivery Method</th>
                            <th>Tags</th>
                            {{--                            <th>Actions</th>--}}
                        </tr>
                        </thead>
                    </table>
                </div>
                <!-- Modal to add new user starts-->
            @include('template.layouts.views.addUser')
            <!-- Modal to add new user Ends-->
            </div>
            <!-- list section end -->
        </section>
        <!-- users list ends -->

    </div>
@endsection
@section("pageVendorJs")
    <!-- BEGIN: Page Vendor JS-->
    <script src="{{ asset("app-assets/vendors/js/extensions/moment.min.js") }}"></script>
    <script src="{{ asset("app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js")}}"></script>
    <script src="{{ asset("app-assets/vendors/js/tables/datatable/datatables.bootstrap4.min.js")}}"></script>
    <script src="{{ asset("app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js")}}"></script>
    <script src="{{ asset("app-assets/vendors/js/tables/datatable/responsive.bootstrap4.js")}}"></script>
    <script src="{{ asset("app-assets/vendors/js/tables/datatable/datatables.buttons.min.js")}}"></script>
    <script src="{{ asset("app-assets/vendors/js/tables/datatable/buttons.bootstrap4.min.js")}}"></script>
    <script src="{{ asset("app-assets/vendors/js/forms/validation/jquery.validate.min.js")}}"></script>
    <!-- END: Page Vendor JS-->
@endsection
@section("pageJsLower")
    <!-- BEGIN: Page JS-->
    {{--    <script src="{{ asset("app-assets/js/scripts/pages/app-user-list.js")}}"></script>--}}
    <!-- END: Page JS-->
    <script>
        $(function () {
            'use strict';

            var dtUserTable = $('.user-list-table'),
                newUserSidebar = $('.new-user-modal'),
                newUserForm = $('.add-new-user'),
                statusObj = {
                    "Unfulfilled": { title: 'Unfulfilled', class: 'badge-light-warning' },
                    "Fulfilled": { title: 'Fulfilled', class: 'badge-light-secondary' }
                };

            var assetPath = '{{ asset("app-assets")."/" }}',
                userView = '{{ route("users.show") }}',
                userEdit = 'app-user-edit.html';
            console.log(assetPath)
            if ($('body').attr('data-framework') === 'laravel') {
                assetPath = $('body').attr('data-asset-path');
                userView = "{{ route("users.show") }}";
                userEdit = assetPath + 'app/user/edit';
            }

            // Users List datatable
            if (dtUserTable.length) {
                dtUserTable.DataTable({
                    ajax: "{{ route("orders.list") }}?user_id={{ request()->get("user_id") }}", // JSON file to add data
                    columns: [
                        // columns according to JSON
                        { data: 'responsive_id' },
                        { data: 'order_no' },
                        { data: 'order_date' },
                        { data: 'customer' },
                        // { data: 'store_currency' },
                        { data: 'total' },
                        { data: 'total_usd' },
                        { data: 'payment_status' },
                        { data: 'fulfillment_status' },
                        { data: 'items' },
                        { data: 'delivery_method' },
                        { data: 'tags' },
                        // { data: '' }
                    ],
                    columnDefs: [
                        {
                            // For Responsive
                            className: 'control',
                            orderable: false,
                            responsivePriority: 2,
                            targets: 0
                        },
                        {
                            // User full name and username
                            targets: 1,
                            responsivePriority: 4,
                            render: function (data, type, full, meta) {

                                var $name = full['order_no'],
                                    $uname = full['store_name'],
                                    $image = full['avatar'];
                                if ($image) {
                                    // For Avatar image
                                    var $output =
                                        '<img src="' + assetPath + 'images/avatars/' + $image + '" alt="Avatar" height="32" width="32">';
                                } else {
                                    // For Avatar badge
                                    var stateNum = Math.floor(Math.random() * 6) + 1;
                                    var states = ['success', 'danger', 'warning', 'info', 'dark', 'primary', 'secondary'];
                                    var $state = states[stateNum],
                                        // $name = full['full_name'],
                                        // $initials = $name.match(/\b\w/g) || [];
                                        $initials = full["customer"];
                                    // $initials = (($initials.shift() || '') + ($initials.pop() || '')).toUpperCase();
                                    // $output = '<span class="avatar-content">' + $initials + '</span>';
                                }
                                var colorClass = $image === '' ? ' bg-light-' + $state + ' ' : '';
                                // Creates full output for row
                                var $row_output =
                                    '<div class="d-flex justify-content-left align-items-center">' +
                                    // '<div class="avatar-wrapper">' +
                                    // '<div class="avatar ' +
                                    // colorClass +
                                    // ' mr-1">' +
                                    // $output +
                                    // '</div>' +
                                    // '</div>' +
                                    '<div class="d-flex flex-column">' +
                                    '<a href="' +
                                    "#" +
                                    '" class="user_name text-truncate"><span class="font-weight-bold">' +
                                    $name +
                                    '</span></a>' +
                                    '<small class="emp_post text-muted">@' +
                                    $uname +
                                    '</small>' +
                                    '</div>' +
                                    '</div>';
                                return $row_output;
                            }
                        },
                        {
                            // User Role
                            targets: 4,
                            render: function (data, type, full, meta) {
                                var $role = full['total'];
                                var roleBadgeObj = {
                                    User: feather.icons['user'].toSvg({ class: 'font-medium-3 text-primary mr-50' }),
                                    // Author: feather.icons['settings'].toSvg({ class: 'font-medium-3 text-warning mr-50' }),
                                    // Maintainer: feather.icons['database'].toSvg({ class: 'font-medium-3 text-success mr-50' }),
                                    // Editor: feather.icons['edit-2'].toSvg({ class: 'font-medium-3 text-info mr-50' }),
                                    Admin: feather.icons['slack'].toSvg({ class: 'font-medium-3 text-danger mr-50' })
                                };
                                return "<span class='text-truncate align-middle'>" + $role +" "+ full["store_currency"]+ '</span>';
                            }
                        },
                        {
                            // User Role
                            targets: 5,
                            render: function (data, type, full, meta) {
                                var $role = full['total_usd'];
                                return "<span class='text-truncate align-middle'>" + $role +" $"+ '</span>';
                            }
                        },
                        {
                            // User Status
                            targets: 7,
                            render: function (data, type, full, meta) {
                                var $status = full['fulfillment_status'];

                                return (
                                    '<span class="badge badge-pill ' +
                                    statusObj[$status].class +
                                    '" text-capitalized>' +
                                    statusObj[$status].title +
                                    '</span>'
                                );
                            }
                        },
                        // {
                        //     // Actions
                        //     targets: -1,
                        //     title: 'Actions',
                        //     orderable: false,
                        //     render: function (data, type, full, meta) {
                        //         var show = "#";
                        //         var destroy = "#";
                        //         var edit = "#";
                        //         console.log(show)
                        //         return (
                        //             '<div class="btn-group">' +
                        //             '<a class="btn btn-sm dropdown-toggle hide-arrow" data-toggle="dropdown">' +
                        //             feather.icons['more-vertical'].toSvg({ class: 'font-small-4' }) +
                        //             '</a>' +
                        //             '<div class="dropdown-menu dropdown-menu-right">' +
                        //             '<a href="' +
                        //             show +
                        //             '" class="dropdown-item">' +
                        //             feather.icons['file-text'].toSvg({ class: 'font-small-4 mr-50' }) +
                        //             'Details</a>' +
                        //             '<a href="' +
                        //             edit +
                        //             '" class="dropdown-item">' +
                        //             feather.icons['archive'].toSvg({ class: 'font-small-4 mr-50' }) +
                        //             'Edit</a>' +
                        //             '<a href="'+destroy+'" class="dropdown-item delete-record">' +
                        //             feather.icons['trash-2'].toSvg({ class: 'font-small-4 mr-50' }) +
                        //             'Delete</a></div>' +
                        //             '</div>' +
                        //             '</div>'
                        //         );
                        //     }
                        // }
                    ],
                    order: [[2, 'desc']],
                    // dom:
                    //     '<"d-flex justify-content-between align-items-center header-actions mx-1 row mt-75"' +
                    //     '<"col-lg-12 col-xl-6" l>' +
                    //     '<"col-lg-12 col-xl-6 pl-xl-75 pl-0"<"dt-action-buttons text-xl-right text-lg-left text-md-right text-left d-flex align-items-center justify-content-lg-end align-items-center flex-sm-nowrap flex-wrap mr-1"<"mr-1"f>B>>' +
                    //     '>t' +
                    //     '<"d-flex justify-content-between mx-2 row mb-1"' +
                    //     '<"col-sm-12 col-md-6"i>' +
                    //     '<"col-sm-12 col-md-6"p>' +
                    //     '>',
                    language: {
                        paginate: {
                            // remove previous & next text from pagination
                            previous: '&nbsp;',
                            next: '&nbsp;'
                        },
                        sLengthMenu: 'Show _MENU_',
                        search: 'Search',
                        searchPlaceholder: 'Search..'
                    },
                    // Buttons with Dropdown
                    // buttons: [
                    //     {
                    //         text: 'Add New User',
                    //         className: 'add-new btn btn-primary mt-50',
                    //         attr: {
                    //             'data-toggle': 'modal',
                    //             'data-target': '#modals-slide-in'
                    //         },
                    //         init: function (api, node, config) {
                    //             $(node).removeClass('btn-secondary');
                    //         }
                    //     }
                    // ],
                    // For responsive popup
                    responsive: {
                        details: {
                            display: $.fn.dataTable.Responsive.display.modal({
                                header: function (row) {
                                    var data = row.data();
                                    return 'Details of ' + data['full_name'];
                                }
                            }),
                            type: 'column',
                            renderer: $.fn.dataTable.Responsive.renderer.tableAll({
                                tableClass: 'table',
                                columnDefs: [
                                    {
                                        targets: 2,
                                        visible: false
                                    },
                                    {
                                        targets: 3,
                                        visible: false
                                    }
                                ]
                            })
                        }
                    },
                    initComplete: function () {
                        // Adding payment status filter once table initialized
                        this.api()
                            .columns(6)
                            .every(function () {
                                var column = this;
                                var select = $(
                                    '<select id="UserRole" class="form-control text-capitalize mb-md-0 mb-2"><option value=""> Select Payment Status </option></select>'
                                )
                                    .appendTo('.user_role')
                                    .on('change', function () {
                                        var val = $.fn.dataTable.util.escapeRegex($(this).val());
                                        column.search(val ? '^' + val + '$' : '', true, false).draw();
                                    });

                                column
                                    .data()
                                    .unique()
                                    .sort()
                                    .each(function (d, j) {
                                        select.append('<option value="' + d + '" class="text-capitalize">' + d + '</option>');
                                    });
                            });
                        // Adding customer filter once table initialized
                        this.api()
                            .columns(3)
                            .every(function () {
                                var column = this;
                                var select = $(
                                    '<select id="UserPlan" class="form-control text-capitalize mb-md-0 mb-2"><option value=""> Select Customer </option></select>'
                                )
                                    .appendTo('.user_plan')
                                    .on('change', function () {
                                        var val = $.fn.dataTable.util.escapeRegex($(this).val());
                                        column.search(val ? '^' + val + '$' : '', true, false).draw();
                                    });

                                column
                                    .data()
                                    .unique()
                                    .sort()
                                    .each(function (d, j) {
                                        select.append('<option value="' + d + '" class="text-capitalize">' + d + '</option>');
                                    });
                            });
                        // Adding fulfillment status filter once table initialized
                        this.api()
                            .columns(7)
                            .every(function () {
                                var column = this;
                                var select = $(
                                    '<select id="FilterTransaction" class="form-control text-capitalize mb-md-0 mb-2xx"><option value=""> Select Fulfillment Status </option></select>'
                                )
                                    .appendTo('.user_status')
                                    .on('change', function () {
                                        var val = $.fn.dataTable.util.escapeRegex($(this).val());
                                        column.search(val ? '^' + val + '$' : '', true, false).draw();
                                    });

                                column
                                    .data()
                                    .unique()
                                    .sort()
                                    .each(function (d, j) {
                                        select.append(
                                            '<option value="' +
                                            statusObj[d].title +
                                            '" class="text-capitalize">' +
                                            statusObj[d].title +
                                            '</option>'
                                        );
                                    });
                            });
                    }
                });
            }

            // Check Validity
            function checkValidity(el) {
                if (el.validate().checkForm()) {
                    submitBtn.attr('disabled', false);
                } else {
                    submitBtn.attr('disabled', true);
                }
            }

            // Form Validation
            if (newUserForm.length) {
                // newUserForm.validate({
                //     errorClass: 'error',
                //     rules: {
                //         'first_name': {
                //             required: true
                //         },
                //         'last_name': {
                //             required: true
                //         },
                //         'email': {
                //             required: true
                //         },
                //         'role': {
                //             required: true
                //         },
                //         'password': {
                //             required: true
                //         }
                //     }
                // });

                $("#sub_btn_user_save_form").on('click', function (e) {
                    validate_form(newUserForm);
                });
            }

            // To initialize tooltip with body container
            $('body').tooltip({
                selector: '[data-toggle="tooltip"]',
                container: 'body'
            });
        });
    </script>
@endsection
