@extends("template.layouts.master",['page_title'=>"Invoices List"])
@section("vendorCss")
    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/vendors/css/vendors.min.css") }}">
    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css") }}">
    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/vendors/css/tables/datatable/responsive.bootstrap4.min.css")}}">
    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/vendors/css/tables/datatable/buttons.bootstrap4.min.css")}}">
    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css")}}">
    <!-- END: Vendor CSS-->

@endsection
@section("pageCss")
    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/css/core/menu/menu-types/vertical-menu.css")}}">
    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/css/plugins/forms/form-validation.css")}}">
    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/css/pages/app-user.css")}}">
    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/vendors/css/forms/select/select2.min.css")}}">

    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/css/plugins/forms/pickers/form-flat-pickr.css")}}">
    <!-- END: Page CSS-->
@endsection
@section("main-content")
    <div class="content-header row">
    </div>
    <div class="content-body">
        <!-- users list start -->
        <section class="app-user-list">
            <!-- users filter start -->
            <div class="card">
                <h5 class="card-header"><span class="menu-title text-truncate" data-i18n="Search Filter">Search Filter</span></h5>
                <div class="d-flex justify-content-between align-items-center mx-50 row pt-0 pb-2">
                    <div class="col-md-4 user_role"></div>
                    <div class="col-md-4 user_plan">
                        <input type="text" class="form-control dt-date flatpickr-range dt-input" data-column="3" placeholder="StartDate to EndDate" data-column-index="4" name="dt_date" />
                        <input type="hidden" class="form-control dt-date start_date dt-input" data-column="3" id="min" data-column-index="3" name="value_from_start_date" />
                        <input type="hidden" class="form-control dt-date end_date dt-input" name="value_from_end_date" id="max" data-column="3" data-column-index="3" />
                    </div>
                    <div class="col-md-4 user_status"></div>
                </div>
                <button id="printInvoice" class="btn btn-sm btn-success badge d-none">Print</button>
            </div>
            <!-- users filter end -->
            <!-- list section start -->
            <div class="card p-2">
                <div class="card-datatable table-responsive pt-0">
                    <table class="invoices-list-table table">
                        <thead class="thead-light">
                        <tr>
                            <th></th>
                            <th></th>
                            <th data-i18n="Invoice #">Invoice #</th>
                            <th>Order Date</th>
                            <th>Customer</th>
{{--                            <th>Currency</th>--}}
                            <th>Total</th>
                            <th>Total USD</th>
                            <th>Payment</th>
                            <th>Fulfillment</th>
                            <th>Items</th>
{{--                            <th>Delivery Method</th>--}}
{{--                            <th>Tags</th>--}}
{{--                            <th>Actions</th>--}}
                        </tr>
                        </thead>
                    </table>
                </div>
                <!-- Modal to add new user starts-->
{{--            @include('template.layouts.views.addUser')--}}
            <!-- Modal to add new user Ends-->
            </div>
            <!-- list section end -->
        </section>
        <!-- users list ends -->

    </div>
    <form action="{{ route("order.invoices.print") }}" method="post" target="_blank" id="printInvoices">
        @csrf
        <input type="hidden" name="order_ids" id="order_ids">
    </form>
@endsection
@section("pageVendorJs")
    <!-- BEGIN: Page Vendor JS-->
    <script src="{{ asset("app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js")}}"></script>
    <script src="{{ asset("app-assets/vendors/js/tables/datatable/datatables.bootstrap4.min.js")}}"></script>
    <script src="{{ asset("app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js")}}"></script>
    <script src="{{ asset("app-assets/vendors/js/tables/datatable/responsive.bootstrap4.js")}}"></script>
    <script src="{{ asset("app-assets/vendors/js/tables/datatable/datatables.buttons.min.js")}}"></script>
    <script src="{{ asset("app-assets/vendors/js/tables/datatable/buttons.bootstrap4.min.js")}}"></script>
    <script src="{{ asset("app-assets/vendors/js/forms/validation/jquery.validate.min.js")}}"></script>
    <script src="{{ asset("app-assets/js/scripts/components/components-navs.js") }}"></script>
    <script src="{{ asset("app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js") }}"></script>
    <script src="{{ asset("app-assets/vendors/js/forms/select/select2.full.min.js")}}"></script>
    <script src="{{ asset("app-assets/vendors/js/tables/datatable/datatables.checkboxes.min.js") }}"></script>
    <script src="{{ asset("app-assets/js/scripts/tables/table-datatables-advanced.js") }}"></script>

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
        });
    </script>
    <script>
        $(function () {
            'use strict';

            var dtUserTable = $('.invoices-list-table'),
                newUserForm = $('.add-new-user'),
                statusObj = {
                    "Unfulfilled": { title: 'Unfulfilled', class: 'badge-light-warning' },
                    "fulfilled": { title: 'Fulfilled', class: 'badge-light-secondary' }
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
                    ajax: "{{ route("orders.list") }}", // JSON file to add data
                    columns: [
                        // columns according to JSON
                        { data: 'id' },
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
                        // { data: 'delivery_method' },
                        // { data: 'tags' },
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
                            // For Checkboxes
                            targets: 1,
                            orderable: false,
                            render: function (data, type, full, meta) {
                                return (
                                    '<div class="custom-control custom-checkbox"> <input class="invoice_checkbox custom-control-input dt-checkboxes" type="checkbox" value="'+`${full['id']}`+'" id="checkbox' +
                                    +`${full['id']}` +
                                    '" /><label class="custom-control-label" for="checkbox' +
                                    +`${full['id']}` +
                                    '"></label></div>'
                                );
                            },
                            checkboxes: {
                                selectAllRender:
                                    '<div class="custom-control custom-checkbox"> <input class="custom-control-input" type="checkbox" value="" id="checkboxSelectAll" /><label class="custom-control-label" for="checkboxSelectAll"></label></div>'
                            }
                        },
                        {
                            type: 'date',
                            targets: 3,
                            width : '75px'
                        },
                        {
                            // User full name and username
                            targets: 2,
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
                            targets: 5,
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
                            targets: 6,
                            render: function (data, type, full, meta) {
                                var $role = full['total_usd'];
                                return "<span class='text-truncate align-middle'>" + $role +" $"+ '</span>';
                            }
                        },
                        {
                            // User Status
                            targets: 8,
                            render: function (data, type, full, meta) {
                                var $status = full['fulfillment_status'];
                                let html = null;
                                if($status === 'fulfilled'){
                                   html = '<span class="badge badge-pill text-capitalized btn btn-sm rounded-pill btn-success" title="'+$status+'">' +$status +'</span>';
                                }else {
                                    html = '<span class="badge badge-pill text-capitalized btn btn-sm rounded-pill btn-warning" title="'+$status+'">' +$status +'</span>';
                                }
                                return (
                                    html
                                );
                            }
                        }
                        ,
                        {
                            // Actions
                            targets: -1,
                            title: 'Actions',
                            orderable: false,
                            render: function (data, type, full, meta) {
                                var show = "#";
                                var destroy = "#";
                                var edit = "#";
                                var invoice = "{{ url('/orders/invoice') }}/"+`${full['id']}`;
                                return (
                                    '<a href="' +
                                    invoice +
                                    '" class="badge badge-light-info">' +
                                    feather.icons['eye'].toSvg({ class: 'font-small-4 mr-50' }) +
                                    'Show</a>'
                                    // '<div class="">' +
                                    // '<a class="btn btn-sm dropdown-toggle hide-arrow" data-toggle="dropdown">' +
                                    // feather.icons['more-vertical'].toSvg({ class: 'font-small-4' }) +
                                    // '</a>' +
                                    // '<div class="dropdown-menu dropdown-menu-right">' +
                                    // '<a href="' +
                                    // invoice +
                                    // '" class="dropdown-item">' +
                                    // feather.icons['file-text'].toSvg({ class: 'font-small-4 mr-50' }) +
                                    // 'Invoice</a>' +
                                    // // '<a href="' +
                                    // // show +
                                    // // '" class="dropdown-item">' +
                                    // // feather.icons['file-text'].toSvg({ class: 'font-small-4 mr-50' }) +
                                    // // 'Details</a>' +
                                    // // '<a href="' +
                                    // // edit +
                                    // // '" class="dropdown-item">' +
                                    // // feather.icons['archive'].toSvg({ class: 'font-small-4 mr-50' }) +
                                    // // 'Edit</a>' +
                                    // // '<a href="'+destroy+'" class="dropdown-item delete-record">' +
                                    // // feather.icons['trash-2'].toSvg({ class: 'font-small-4 mr-50' }) +
                                    // // 'Delete</a>' +
                                    // '</div>' +
                                    // '</div>' +
                                    // '</div>'
                                );
                            }
                        }
                    ],
                    order: [[2, 'desc']],
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
                    lengthMenu: [10, 25, 50, 100,500,1000],
                    // Buttons with Dropdown
                    buttons: [
                        {
                            text: 'Print',
                            className: 'add-new btn btn-info mt-50',
                            init: function (api, node, config) {
                                $(node).removeClass('btn-secondary');
                            }
                        }
                    ],
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
                            .columns(7)
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
                        // Adding fulfillment status filter once table initialized
                        this.api()
                            .columns(8)
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
                        checkCheckBoxes();
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="https://cdn.datatables.net/datetime/1.1.1/js/dataTables.dateTime.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery.print/1.6.2/jQuery.print.min.js"></script>
    <script>
        function showTag(value) {
            let element = document.getElementById('tagIdSection');
            if(value === '1'){
                $("#basic-icon-default-tag_id").attr("required")
                element.classList.add('d-none');
            }else{
                $("#basic-icon-default-tag_id").removeAttr("required")
                element.classList.remove('d-none');
            }
        }
        const printBtn = $("#printInvoice");
        printBtn.on('click',function (){
            let checkbox = (function() {
                let a = [];
                $(".invoice_checkbox:checked").each(function() {
                    a.push($(this).val());
                });
                return a;
            })();
            $('#order_ids').val(checkbox);
            $('#printInvoices').submit();
            {{--$.ajaxSetup({--}}
            {{--    url: '{{ route("order.invoices.print") }}',--}}
            {{--    type: 'POST',--}}
            {{--    data:{--}}
            {{--      '_token':'{{ csrf_token() }}',--}}
            {{--      'order_ids':checkbox--}}
            {{--    },--}}
            {{--    beforeSend: function() {--}}
            {{--        printBtn.html('Printing .... </br> Please wait! This may Take a while');--}}
            {{--        printBtn.attr('disabled',true);--}}
            {{--        console.log('printing ...');--}}
            {{--    },--}}
            {{--    complete: function() {--}}
            {{--        printBtn.html('Print');--}}
            {{--        printBtn.attr('disabled',false);--}}
            {{--        console.log('printed!');--}}
            {{--    }--}}
            {{--});--}}

            {{--$.ajax({--}}
            {{--    // success: function(viewContent) {--}}
            {{--    //     $.print(viewContent); // This is where the script calls the printer to print the view's content.--}}
            {{--    //     console.log('Sending Data....');--}}
            {{--    // }--}}
            {{--    success: function(url) {--}}
            {{--        window.open(url,'_blank'); // This is where the script calls the printer to print the view's content.--}}
            {{--        console.log('Sending Data....');--}}
            {{--    }--}}
            {{--});--}}
        });
        function checkCheckBoxes() {
            $('.invoice_checkbox').on('change',function () {
                if ($('.invoice_checkbox:checkbox:checked').length > 0){
                    printBtn.removeClass('d-none');
                }else {
                    printBtn.addClass('d-none');
                }
            });
            $('#checkboxSelectAll').on('change',function () {
                if ($(this).is(':checked')){
                    printBtn.removeClass('d-none');
                }else {
                    printBtn.addClass('d-none');
                }
            });
        }
    </script>
{{--
<script>
       var minDate = $('#min'), maxDate = $('#max');
       const months = {
           0: 'January',
           1: 'February',
           2: 'March',
           3: 'April',
           4: 'May',
           5: 'June',
           6: 'July',
           7: 'August',
           8: 'September',
           9: 'October',
           10: 'November',
           11: 'December'
       }
       // Custom filtering function which will search data in column four between two values
       $.fn.dataTable.ext.search.push(
           function( settings, data, dataIndex ) {
               var min = (months[minDate.val().getMonth()] + ' ' + minDate.val().getDate() + ' ' + minDate.val().getFullYear()) ?? null;
               var max = (months[maxDate.val().getMonth()] + ' ' + maxDate.val().getDate() + ' ' + maxDate.val().getFullYear()) ?? null;
               var date = new Date( data[3] );
    console.log(data[3],(min === null && max === null) ||
   (min === null && date <= max) ||
   (min <= date && max === null) ||
   (min <= date && date <= max),min,max)
               return (min === null && max === null) ||
                   (min === null && date <= max) ||
                   (min <= date && max === null) ||
                   (min <= date && date <= max);

           }
       );

       $(document).ready(function() {
           // Create date inputs
           minDate = new DateTime($('#min'), {
               format: 'M D YYYY'
           });
           maxDate = new DateTime($('#max'), {
               format: 'M D YYYY'
           });

           // DataTables initialisation
           var table = $('.invoices-list-table').DataTable();

           // Refilter the table
           $('#min, #max').on('change', function () {
               table.draw();
           });
       });
   </script>
--}}
@endsection
