<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
<meta name="description" content="Vuexy admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
<meta name="keywords" content="admin template, Vuexy admin template, dashboard template, flat admin template, responsive admin template, web app">
<meta name="author" content="PIXINVENT">
<title>{{ $page_title ?? "" }}</title>
<link rel="apple-touch-icon" href="{{ asset("app-assets/images/ico/apple-icon-120.png")}}">
<link rel="shortcut icon" type="image/x-icon" href="{{ asset("app-assets/images/ico/favicon.ico")}}">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600" rel="stylesheet">

<!-- BEGIN: Vendor CSS-->
<link rel="stylesheet" type="text/css" href="{{ asset("app-assets/vendors/css/vendors.min.css")}}">
<link rel="stylesheet" type="text/css" href="{{ asset("app-assets/vendors/css/charts/apexcharts.css")}}">
<link rel="stylesheet" type="text/css" href="{{ asset("app-assets/vendors/css/extensions/toastr.min.css")}}">
<!-- END: Vendor CSS-->
@section("vendorCss")
@show
<!-- BEGIN: Theme CSS-->
<link rel="stylesheet" type="text/css" href="{{ asset("app-assets/css/bootstrap.css")}}">
<link rel="stylesheet" type="text/css" href="{{ asset("app-assets/css/bootstrap-extended.css")}}">
<link rel="stylesheet" type="text/css" href="{{ asset("app-assets/css/colors.css")}}">
<link rel="stylesheet" type="text/css" href="{{ asset("app-assets/css/components.css")}}">
<link rel="stylesheet" type="text/css" href="{{ asset("app-assets/css/themes/dark-layout.css")}}">
<link rel="stylesheet" type="text/css" href="{{ asset("app-assets/css/themes/bordered-layout.css")}}">
<link rel="stylesheet" type="text/css" href="{{ asset("app-assets/css/themes/semi-dark-layout.css")}}">

<link rel="stylesheet" type="text/css" href="{{ asset("app-assets/vendors/css/extensions/toastr.min.css")}}">
<link rel="stylesheet" type="text/css" href="{{ asset("app-assets/css/plugins/extensions/ext-component-toastr.css")}}">
@section("pageCss")
    @show

<!-- BEGIN: Custom CSS-->
<link rel="stylesheet" type="text/css" href="{{ asset("assets/css/style.css")}}">
<!-- END: Custom CSS-->

<style>
    .dropdown-shop{
        border: 2px solid #3b4253;
        border-radius: 15px;
        padding: 5px;
    }
    .ms-75 {
        margin-left: 0.75rem!important;
    }
    .p-75 {
        padding: 1.0rem!important;
    }

    /* custom css */


    /* width */
    ::-webkit-scrollbar {
        width: 10px;
    }

    /* Track */
    ::-webkit-scrollbar-track {
        box-shadow: inset 0 0 5px #02c6de;
        border-radius: 10px;
    }

    /* Handle */
    ::-webkit-scrollbar-thumb {
        background: {{Auth::user()->layout?'#283046':'#615ac8'}};
        border-radius: 10px;
    }

    /* Handle on hover */
    ::-webkit-scrollbar-thumb:hover {
        background: {{Auth::user()->layout?'#615ac8':'#283046'}};
    }
</style>
