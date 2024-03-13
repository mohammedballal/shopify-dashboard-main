@extends("template.layouts.master",['page_title'=>env("APP_STORE_NAME")." Campaigns List"])
@section("pageCss")
    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/css/core/menu/menu-types/vertical-menu.css")}}">
    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/css/pages/dashboard-ecommerce.css")}}">
    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/css/plugins/charts/chart-apex.css")}}">
    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/css/plugins/extensions/ext-component-toastr.css")}}">

    <link href="{{asset('assets/charts/chartist.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/charts/chartist-plugin-tooltip.css')}}" rel="stylesheet">
    <link href="{{asset('assets/charts/c3.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/charts/style.min.css')}}" rel="stylesheet">
    <!-- END: Page CSS-->
@endsection
@section("main-content")
    <div class="content-header row">
    </div>
    <div class="content-body">
        <div class="fb-login-button" data-width="" data-size="large" data-button-type="continue_with" data-layout="default" data-auto-logout-link="false" data-use-continue-as="false"></div>
    </div>
@endsection
@section("pageVendorJs")
    <!-- BEGIN: Page Vendor JS-->
{{--    <script src="{{ asset("app-assets/vendors/js/charts/apexcharts.min.js") }}"></script>--}}
{{--    <script src="{{ asset("app-assets/vendors/js/extensions/toastr.min.js") }}"></script>--}}
    <!-- END: Page Vendor JS-->
@endsection
@section("pageJs")
    <!-- BEGIN: Page JS-->
{{--    <script src="{{ asset("app-assets/js/scripts/pages/dashboard-ecommerce.js") }}"></script>--}}
{{--    <!-- END: Page JS-->--}}

{{--    <script src="{{asset('assets/charts/chartist.min.js')}}"></script>--}}
{{--    <script src="{{asset('assets/charts/chartist-plugin-tooltip.min.js')}}"></script>--}}

    <!--
    <script>
        var chart = new Chartist.Line('.campaign', {
            labels: [1, 2, 3, 4, 5, 6, 7, 8],
            series: [
                [0, 5, 6, 8, 25, 9, 8, 24],
                [0, 3, 1, 2, 8, 1, 5, 1]
            ]
        }, {
            low: 0,
            high: 28,

            showArea: true,
            fullWidth: true,
            plugins: [
                Chartist.plugins.tooltip()
            ],
            axisY: {
                onlyInteger: true,
                scaleMinSpace: 40,
                offset: 20,
                labelInterpolationFnc: function(value) {
                    return (value / 1) + 'k';
                }
            },

        });

        // Offset x1 a tiny amount so that the straight stroke gets a bounding box
        // Straight lines don't get a bounding box
        // Last remark on -> http://www.w3.org/TR/SVG11/coords.html#ObjectBoundingBox
        chart.on('draw', function(ctx) {
            if (ctx.type === 'area') {
                ctx.element.attr({
                    x1: ctx.x1 + 0.001
                });
            }
        });

        // Create the gradient definition on created event (always after chart re-render)
        chart.on('created', function(ctx) {
            var defs = ctx.svg.elem('defs');
            defs.elem('linearGradient', {
                id: 'gradient',
                x1: 0,
                y1: 1,
                x2: 0,
                y2: 0
            }).elem('stop', {
                offset: 0,
                'stop-color': 'rgba(255, 255, 255, 1)'
            }).parent().elem('stop', {
                offset: 1,
                'stop-color': 'rgba(64, 196, 255, 1)'
            });
        });


        var chart = [chart];

    </script>
-->

    <script>

        window.fbAsyncInit = function() {
            FB.init({
                appId      : '{{ env("FACEBOOK_CLIENT_ID") }}',
                cookie     : true,
                xfbml      : true,
                version    : 'v13.0'
            });

            FB.AppEvents.logPageView();

        };

        (function(d, s, id){
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) {return;}
            js = d.createElement(s); js.id = id;
            js.src = "https://connect.facebook.net/en_US/sdk.js";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
        function checkLoginState() {
            FB.getLoginStatus(function(response) {
                statusChangeCallback(response);
            });
        }
    </script>
@endsection
