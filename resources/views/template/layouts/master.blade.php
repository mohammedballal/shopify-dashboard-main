<!DOCTYPE html>
<html class="loading {{Auth::user()->layout?'dark-layout':'light-layout'}}" lang="en" data-layout="{{Auth::user()->layout?'dark-layout':'light-layout'}}" data-textdirection="ltr">
<!-- BEGIN: Head-->
<head>
    @include("template.layouts.head")
    @section("customCss")
    @show
</head>

<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern  navbar-floating footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="">

<!-- BEGIN: Header-->
@include("template.layouts.header")
<!-- END: Header-->


<!-- BEGIN: Main Menu-->
@include("template.layouts.sidebar")
<!-- END: Main Menu-->

<!-- BEGIN: Content-->
<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        @section("main-content")
            @show
    </div>
</div>
<!-- END: Content-->

<div class="sidenav-overlay"></div>
<div class="drag-target"></div>

<!-- BEGIN: Footer-->
@include("template.layouts.footer")
<!-- END: Footer-->

@include("template.layouts.scripts")
@section("customJs")
    @show
<script>
    // On load Toast
    @if(Session::has('success'))
    setTimeout(function () {
        toastr['success'](
            "Success",
            "{{ Session::get('success') }}",
            {
                closeButton: true,
                tapToDismiss: true
            }
        );
    }, 2000);
    @elseif(Session::has('error'))
    setTimeout(function () {
        toastr['error'](
            "Error",
            "{{ Session::get('error') }}",
            {
                closeButton: true,
                tapToDismiss: true
            }
        );
    }, 2000);
    @endif
    @if(count($errors) > 0)
    @foreach($errors->all() as $error)
    setTimeout(function () {
        toastr['error'](
            'Error',
            '{{ ucwords($error) }}',
            {
                closeButton: true,
                tapToDismiss: false
            }
        );
    }, 5000);
    @endforeach
    @endif
</script>
</body>
<!-- END: Body-->

</html>
