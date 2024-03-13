@extends("template.layouts.views.profile",['page_title'=>"User Account"])
@section("vendorCss")
    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/vendors/css/vendors.min.css") }}">
    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/vendors/css/forms/select/select2.min.css") }}">
    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css") }}">


    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css") }}">
    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/vendors/css/tables/datatable/responsive.bootstrap4.min.css")}}">
    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/vendors/css/tables/datatable/buttons.bootstrap4.min.css")}}">
    <!-- END: Vendor CSS-->
@endsection
@section("pageCss")
    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/css/core/menu/menu-types/vertical-menu.css")}}">
    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/css/plugins/forms/pickers/form-flat-pickr.css") }}">

    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/css/plugins/forms/form-validation.css")}}">
    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/css/pages/app-user.css")}}">
    <!-- END: Page CSS-->
@endsection
@section("user-content")
    <div class="card">
        <h5 class="card-header">Search Filter</h5>
        <div class="d-flex justify-content-between align-items-center mx-50 row pt-0 pb-2">
            <div class="col-md-4 coupon_status"></div>
        </div>
    </div>
    <div class="card">
        <h4 class="card-header">User's Coupons List</h4>
        <div class="card-datatable table-responsive p-2">
            <table class="datatable-coupons table text-center">
                <thead>
                <tr>
                    <th></th>
                    <th class="text-nowrap">Coupons</th>
                    <th>Products Sold</th>
                    <th>Total Sales</th>
                    <th>Commission</th>
                    <th>% on Sales</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
@endsection

@section("pageVendorJs")
    <!-- BEGIN: Page Vendor JS-->
    <script src="{{ asset("app-assets/vendors/js/forms/select/select2.full.min.js") }}"></script>
    <script src="{{ asset("app-assets/vendors/js/forms/validation/jquery.validate.min.js") }}"></script>
    <script src="{{ asset("app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js") }}"></script>

    <script src="{{ asset("app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js")}}"></script>
    <script src="{{ asset("app-assets/vendors/js/tables/datatable/datatables.bootstrap4.min.js")}}"></script>
    <script src="{{ asset("app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js")}}"></script>
    <script src="{{ asset("app-assets/vendors/js/tables/datatable/responsive.bootstrap4.js")}}"></script>
    <script src="{{ asset("app-assets/vendors/js/tables/datatable/datatables.buttons.min.js")}}"></script>
    <script src="{{ asset("app-assets/vendors/js/tables/datatable/buttons.bootstrap4.min.js")}}"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Page JS-->
    <script src="{{ asset("app-assets/js/scripts/pages/app-user-edit.js") }}"></script>
    <script src="{{ asset("app-assets/js/scripts/components/components-navs.js") }}"></script>
    <!-- END: Page JS-->
@endsection
@section("pageJsLower")
    <script>


        $(function () {
            'use strict';
            var dtCouponTable = $('.datatable-coupons');
            if (dtCouponTable.length) {
                dtCouponTable.DataTable(
                    {
                        ajax: "{{ route("profile.index") }}", // JSON file to add data
                        columns: [
                            // columns according to JSON
                            { data: 'responsive_id' },
                            { data: 'coupon' },
                            { data: 'products' },
                            { data: 'sales' },
                            { data: 'commission' },
                            { data: 'percent' }
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
                                // User Role
                                targets: 5,
                                render: function (data, type, full, meta) {
                                    var percent = full['percent'];
                                    return percent+"%"+"<div class='progress progress-bar-success'><div class='progress-bar' role='progressbar' aria-valuenow='"+percent+"' aria-valuemin='"+percent+"' aria-valuemax='100' style='width: "+percent+"%'></div></div>";
                                    // return "<span class='text-truncate align-middle'>" + $role +" "+ full["store_currency"]+ '</span>';
                                }
                            },
                        ],
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
                        // For responsive popup
                        responsive: {
                            details: {
                                display: $.fn.dataTable.Responsive.display.modal({
                                    header: function (row) {
                                        var data = row.data();
                                        return 'Details of coupon';
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
                            // Adding status filter once table initialized
                            this.api()
                                .columns(1)
                                .every(function () {
                                    var column = this;
                                    var select = $(
                                        '<select id="couponStatus" class="form-control text-capitalize mb-md-0 mb-2"><option value=""> Select Coupon </option></select>'
                                    )
                                        .appendTo('.coupon_status')
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
                        }
                    }
                );
            }
            // To initialize tooltip with body container
            $('body').tooltip({
                selector: '[data-toggle="tooltip"]',
                container: 'body'
            });
        });
        $(document).ready(function () {
            // coupons List datatable

        });
    </script>
@endsection
