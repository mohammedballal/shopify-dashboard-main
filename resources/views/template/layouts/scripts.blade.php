<!-- BEGIN: Vendor JS-->
<script src="{{ asset("app-assets/vendors/js/vendors.min.js") }}"></script>
<!-- BEGIN Vendor JS-->
@section("pageVendorJs")
@show
@section("pageJs")
@show
<!-- BEGIN: Theme JS-->
<script src="{{ asset("app-assets/js/core/app-menu.min.js") }}"></script>
<script src="{{ asset("app-assets/js/core/app.js") }}"></script>

<script src="{{ asset("app-assets/vendors/js/extensions/toastr.min.js") }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
<script>
    function validate_form(form){
        var validator = $(form).validate();
        if ($(form).valid()){
            $(form).submit();
        }
    }
    $(document).ready(function (){
        $('table').addClass('table-hover');
        $('.nav-link-style').find('.ficon').replaceWith(feather.icons['{{Auth::user()->layout?'sun':'moon'}}'].toSvg({ class: 'ficon' }));
        $( ".icon" ).each(function( index ) {
            var name = $(this).attr('data-feather');
            $(this).replaceWith(feather.icons[name].toSvg({ class: 'icon' }));
        });
    });
</script>
<!-- END: Theme JS-->
@section("pageJsLower")
@show

