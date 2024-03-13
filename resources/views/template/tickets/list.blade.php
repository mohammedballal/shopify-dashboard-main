@extends("template.layouts.master",['page_title'=>"Tickets List"])
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
    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/css/plugins/forms/form-wizard.min.css")}}">
    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/css/pages/app-user.css")}}">
    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/css/pages/modal-create-app.min.css")}}">
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

    <style>
        .btn-close {
            text-shadow: none;
            background-color: #283046!important;
            color: #ffffff;
            box-shadow: 0 3px 8px 0 rgba(11,10,25,.49)!important;
            background: url("data:image/svg+xml,%3csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 16 16\' fill=\'%23b4b7bd\'%3e%3cpath d=\'M.293.293a1 1 0 011.414 0L8 6.586 14.293.293a1 1 0 111.414 1.414L9.414 8l6.293 6.293a1 1 0 01-1.414 1.414L8 9.414l-6.293 6.293a1 1 0 01-1.414-1.414L6.586 8 .293 1.707a1 1 0 010-1.414z\'/%3e%3c/svg%3e") center/0.75rem auto no-repeat;
            padding: 0.8rem!important;
            border-radius: 0.557rem;
            opacity: 1;
            -webkit-transition: all .23s ease .1s;
            transition: all .23s ease .1s;
            position: absolute;
            z-index: 1;
            -webkit-transform: translate(18px,-10px);
            -ms-transform: translate(18px,-10px);
            transform: translate(18px,-10px);
            box-sizing: content-box;
            width: 0.75rem;
            height: 0.75rem;
            top: 0;
            right: 0;
        }
        .btn-close:hover{
            opacity: 1;
            outline: 0;
            -webkit-transform: translate(15px,-2px);
            -ms-transform: translate(15px,-2px);
            transform: translate(15px,-2px);
            box-shadow: none;
        }
    </style>
    <!-- END: Page CSS-->
@endsection
@section("main-content")
    <div class="content-header row">
    </div>
    <div class="content-body">
        <!-- tickets list start -->
        <section class="app-ticket-list">
            <div class="row">
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-header">
                            <div>
                                <h2 class="fw-bolder mb-0">{{$counts['all']}}</h2>
                                <p class="card-text">Total Tickets</p>
                            </div>
                            <div class="avatar bg-light-primary p-50 m-0">
                                <div class="avatar-content">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-cpu font-medium-5">
                                        <rect x="4" y="4" width="16" height="16" rx="2" ry="2"></rect>
                                        <rect x="9" y="9" width="6" height="6"></rect>
                                        <line x1="9" y1="1" x2="9" y2="4"></line>
                                        <line x1="15" y1="1" x2="15" y2="4"></line>
                                        <line x1="9" y1="20" x2="9" y2="23"></line>
                                        <line x1="15" y1="20" x2="15" y2="23"></line>
                                        <line x1="20" y1="9" x2="23" y2="9"></line>
                                        <line x1="20" y1="14" x2="23" y2="14"></line>
                                        <line x1="1" y1="9" x2="4" y2="9"></line>
                                        <line x1="1" y1="14" x2="4" y2="14"></line>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-header">
                            <div>
                                <h2 class="fw-bolder mb-0">{{$counts['open']}}</h2>
                                <p class="card-text">Open Tickets</p>
                            </div>
                            <div class="avatar bg-light-danger p-50 m-0">
                                <div class="avatar-content">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-octagon font-medium-5">
                                        <polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon>
                                        <line x1="12" y1="8" x2="12" y2="12"></line>
                                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-header">
                            <div>
                                <h2 class="fw-bolder mb-0">{{$counts['inProgress']}}</h2>
                                <p class="card-text">In Progress</p>
                            </div>
                            <div class="avatar bg-light-warning p-50 m-0">
                                <div class="avatar-content">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-activity font-medium-5">
                                        <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-header">
                            <div>
                                <h2 class="fw-bolder mb-0">{{$counts['closed']}}</h2>
                                <p class="card-text">Closed Tickets</p>
                            </div>
                            <div class="avatar bg-light-success p-50 m-0">
                                <div class="avatar-content">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-server font-medium-5">
                                        <rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect>
                                        <rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect>
                                        <line x1="6" y1="6" x2="6.01" y2="6"></line
                                        ><line x1="6" y1="18" x2="6.01" y2="18"></line>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <h5 class="card-header">Search Filter</h5>
                <div class="d-flex justify-content-between align-items-center mx-50 row pt-0 pb-2">
                    <div class="col-md-4 ticket_status"></div>
                </div>
            </div>
            <!-- list section start -->
            <div class="card p-2">
                <div class="card-datatable table-responsive pt-0">
                    <table class="ticket-list-table table">
                        <thead class="thead-light">
                        <tr>
                            <th></th>
                            <th>#</th>
                            @if(Auth::user()->hasRole("Admin") || Auth::user()->hasRole("Super Admin"))
                                <th>User</th>
                            @endif
                            <th>Status</th>
                            <th>Priority</th>
                            <th>Title</th>
                            <th>Ticket</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                    </table>
                </div>
                <!-- Modal to add new ticket starts-->
                    @include('template.layouts.views.addTicket')
                <!-- Modal to add new ticket Ends-->
            </div>
            <!-- list section end -->
        </section>
        <!-- tickets list ends -->

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
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js" type="text/javascript"></script>
    <!-- END: Page Vendor JS-->
@endsection
@section("pageJsLower")
    <!-- BEGIN: Page JS-->
    <script>
        var toolbarOptions = [['bold', 'italic','underline','link']];
        var quill = new Quill('#editor', {
            modules: {
                toolbar: toolbarOptions
            },
            placeholder: 'Description',
            theme: 'snow'
        });

        $("#add-new-ticket-form").on("submit",function(){
            $("#description").val($(".ql-editor").html());
        });
    </script>
    <!-- END: Page JS-->
    <script>
        $(function () {
            'use strict';

            var dtticketTable = $('.ticket-list-table'),
                newTicketSidebar = $('.new-ticket-modal'),
                newTicketForm = $('.add-new-ticket'),
                statusObj = {
                    'Open': { title: 'Open', class: 'badge-light-danger' },
                    'In Progress': { title: 'In Progress', class: 'badge-light-secondary' },
                    'Closed': { title: 'Closed', class: 'badge-light-success' }
                },
                priorityObj = {
                    'Low': { title: 'Low', class: 'badge-light-info' },
                    'Medium': { title: 'Medium', class: 'badge-light-warning' },
                    'High': { title: 'High', class: 'badge-light-danger' }
                };

            // Tickets List datatable
            if (dtticketTable.length) {
                dtticketTable.DataTable({
                    ajax: "{{ route("ticket.index") }}", // JSON file to add data
                    columns: [
                        // columns according to JSON
                        { data: 'responsive_id' },
                        { data: 'num' },
                        @if(Auth::user()->hasRole("Admin") || Auth::user()->hasRole("Super Admin"))
                            { data: 'user' },
                         @endif
                        { data: 'status' },
                        { data: 'priority' },
                        { data: 'title' },
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
                            // Ticket Status
                            targets: {{Auth::user()->hasRole("Admin") || Auth::user()->hasRole("Super Admin")?'3':'2'}},
                            render: function (data, type, full, meta) {
                                var $status = full['status'];

                                return (
                                    '<span class="badge badge-pill ' +
                                    statusObj[$status].class +
                                    '" text-capitalized>' +
                                    statusObj[$status].title +
                                    '</span>'
                                );
                            }
                        },
                        {
                            // Ticket Priority
                            targets: {{Auth::user()->hasRole("Admin") || Auth::user()->hasRole("Super Admin")?'4':'3'}},
                            render: function (data, type, full, meta) {
                                var $priority = full['priority'];

                                return (
                                    '<span class="badge badge-pill ' +
                                    priorityObj[$priority].class +
                                    '" text-capitalized>' +
                                    priorityObj[$priority].title +
                                    '</span>'
                                );
                            }
                        },
                        {
                            // Actions
                            targets: -1,
                            title: 'Actions',
                            orderable: false,
                            render: function (data, type, full, meta) {
                                var show = window.location.origin+'/ticket/'+full['id'];
                                return (
                                    '<div class="btn-group">' +
                                    '<a href="'+show+'" class="dropdown-item text-info">' +
                                    feather.icons['eye'].toSvg({ class: 'font-small-4 mr-50' }) +
                                    'Show</a></div>' +
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
                            text: 'Open ticket',
                            className: 'add-new btn btn-primary mt-50 {{Auth::user()->hasRole('User')?'':'d-none'}}',
                            attr: {
                                'data-toggle': 'modal',
                                'data-target': '#new-ticket-modal'
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
                                    return 'Details of ticket';
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
                            @if(Auth::user()->hasRole("Admin") || Auth::user()->hasRole("Super Admin"))
                            .columns(3)
                            @else
                            .columns(2)
                            @endif
                            .every(function () {
                                var column = this;
                                var select = $(
                                    '<select id="ticketStatus" class="form-control text-capitalize mb-md-0 mb-2"><option value=""> Select Status </option></select>'
                                )
                                    .appendTo('.ticket_status')
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
