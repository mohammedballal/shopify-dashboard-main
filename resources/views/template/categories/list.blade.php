@extends("template.layouts.master",['page_title'=>"Categories List"])
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
    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/css/pages/app-user.css")}}">
    <!-- END: Page CSS-->
@endsection
@section("main-content")
    <div class="content-header row">
    </div>
    <div class="content-body">
        <!-- shops list start -->
        <section class="app-shop-list">
            <!-- list section start -->
            <div class="card p-2">
                <div class="card-datatable table-responsive pt-0">
                    <table class="shop-list-table table">
                        <thead class="thead-light">
                        <tr>
                            <th></th>
                            <th>Name</th>
{{--                            <th>Parent</th>--}}
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                    </table>
                </div>
                <!-- Modal to add new shop starts-->
                    @include('template.layouts.views.addCategory')
                <!-- Modal to add new shop Ends-->
            </div>
            <!-- list section end -->
        </section>
        <!-- shops list ends -->

    </div>
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
    <!-- END: Page Vendor JS-->
@endsection
@section("pageJsLower")
    <!-- BEGIN: Page JS-->
{{--    <script src="{{ asset("app-assets/js/scripts/pages/app-user-list.js")}}"></script>--}}
    <!-- END: Page JS-->
    <script>
        function editShop(id) {
            let url = window.location.origin+'/category/'+id+'/edit';
            fetch(url)
                .then(response => response.json())
                .then(json =>{
                    console.log(json);
                    url = window.location.origin+'/category/update/'+json.id;
                    $('#shopTitle').text('Edit Category');
                    $('#sub_btn_shop_save_form').text('Update');
                    $('#addShop').attr('action',url);
                    $('#name').val(json.name);
                    $('#parent_id').val(json.parent_id);
                    $('.dtr-bs-modal').modal('hide');
                    $('#modals-slide-in').modal('show');
                })
                .catch(err => {
                    console.log(err);
                });
        }

        $(function () {
            'use strict';

            var dtshopTable = $('.shop-list-table'),
                newshopSidebar = $('.new-shop-modal'),
                newshopForm = $('.add-new-shop'),
                statusObj = {
                    1: { title: 'Active', class: 'badge-light-success' },
                    0: { title: 'Inactive', class: 'badge-light-secondary' }
                };

            var assetPath = '{{ asset("app-assets")."/" }}';
                // shopEdit = editShop(full['id']);
                console.log(assetPath)
            if ($('body').attr('data-framework') === 'laravel') {
                assetPath = $('body').attr('data-asset-path');
                // shopEdit = editShop(full['id']);
            }

            // shops List datatable
            if (dtshopTable.length) {
                dtshopTable.DataTable({
                    ajax: "{{ route("category.index") }}", // JSON file to add data
                    columns: [
                        // columns according to JSON
                        { data: 'responsive_id' },
                        { data: 'name' },
                        // { data: 'parent' },
                        { data: 'description' },
                        { data: '' }
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
                            // Actions
                            targets: -1,
                            title: 'Actions',
                            orderable: false,
                            render: function (data, type, full, meta) {
                                var destroy = "/category/destroy/"+full['id'];
                                var edit = full['id'];
                                return (
                                    '<div class="btn-group">' +
                                    '<a onclick="editShop('+edit+')" class="dropdown-item text-warning">' +
                                    feather.icons['archive'].toSvg({ class: 'font-small-4 mr-50' }) +
                                    'Edit</a>' +
                                    '<a href="'+destroy+'" class="dropdown-item delete-record text-danger">' +
                                    feather.icons['trash-2'].toSvg({ class: 'font-small-4 mr-50' }) +
                                    'Delete</a></div>' +
                                    '</div>' +
                                    '</div>'
                                );
                            }
                        }
                    ],
                    order: [[2, 'desc']],
                    dom:
                        '<"d-flex justify-content-between align-items-center header-actions mx-1 row mt-75"' +
                        '<"col-lg-12 col-xl-6" l>' +
                        '<"col-lg-12 col-xl-6 pl-xl-75 pl-0"<"dt-action-buttons text-xl-right text-lg-left text-md-right text-left d-flex align-items-center justify-content-lg-end align-items-center flex-sm-nowrap flex-wrap mr-1"<"mr-1"f>B>>' +
                        '>t' +
                        '<"d-flex justify-content-between mx-2 row mb-1"' +
                        '<"col-sm-12 col-md-6"i>' +
                        '<"col-sm-12 col-md-6"p>' +
                        '>',
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
                    buttons: [
                        {
                            text: 'New Entry',
                            className: 'add-new btn btn-primary mt-50',
                            attr: {
                                'data-toggle': 'modal',
                                'data-target': '#modals-slide-in'
                            },
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
                    // initComplete: function () {
                    //     // Adding role filter once table initialized
                    //     this.api()
                    //         .columns(3)
                    //         .every(function () {
                    //             var column = this;
                    //             var select = $(
                    //                 '<select id="shopRole" class="form-control text-capitalize mb-md-0 mb-2"><option value=""> Select Role </option></select>'
                    //             )
                    //                 .appendTo('.shop_role')
                    //                 .on('change', function () {
                    //                     var val = $.fn.dataTable.util.escapeRegex($(this).val());
                    //                     column.search(val ? '^' + val + '$' : '', true, false).draw();
                    //                 });
                    //
                    //             column
                    //                 .data()
                    //                 .unique()
                    //                 .sort()
                    //                 .each(function (d, j) {
                    //                     select.append('<option value="' + d + '" class="text-capitalize">' + d + '</option>');
                    //                 });
                    //         });
                    // }
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

            // To initialize tooltip with body container
            $('body').tooltip({
                selector: '[data-toggle="tooltip"]',
                container: 'body'
            });
        });
    </script>

@endsection
