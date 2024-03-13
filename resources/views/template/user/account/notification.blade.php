@extends("template.layouts.views.profile",['page_title'=>"User Account"])
@section("vendorCss")
    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/vendors/css/vendors.min.css") }}">
    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/vendors/css/forms/select/select2.min.css") }}">
    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css") }}">
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
    this is user section
@endsection

@section("pageVendorJs")
    <!-- BEGIN: Page Vendor JS-->
    <script src="{{ asset("app-assets/vendors/js/forms/select/select2.full.min.js") }}"></script>
    <script src="{{ asset("app-assets/vendors/js/forms/validation/jquery.validate.min.js") }}"></script>
    <script src="{{ asset("app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js") }}"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Page JS-->
    <script src="{{ asset("app-assets/js/scripts/pages/app-user-edit.js") }}"></script>
    <script src="{{ asset("app-assets/js/scripts/components/components-navs.js") }}"></script>
    <!-- END: Page JS-->
@endsection
@section("pageJsLower")
    <script>

    </script>
@endsection
