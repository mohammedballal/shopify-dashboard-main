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
    <!-- Change Password -->
    <div class="card">
        <h4 class="card-header">Change Password</h4>
        <div class="card-body">
            <form id="formChangePassword" method="POST" onsubmit="return false">
                <div class="alert alert-warning mb-2" role="alert">
                    <h6 class="alert-heading">Ensure that these requirements are met</h6>
                    <div class="alert-body fw-normal">Minimum 8 characters long</div>
                </div>

                <div class="row">
                    <div class="mb-2 col-md-6 form-password-toggle">
                        <label class="form-label" for="newPassword">New Password</label>
                        <div class="input-group input-group-merge form-password-toggle">
                            <input class="form-control" type="password" id="newPassword" name="newPassword" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                            <span class="input-group-text cursor-pointer">
                                                        <i data-feather="eye" class="icon"></i>
                                                    </span>
                        </div>
                    </div>

                    <div class="mb-2 col-md-6 form-password-toggle">
                        <label class="form-label" for="confirmPassword">Confirm New Password</label>
                        <div class="input-group input-group-merge">
                            <input class="form-control" type="password" name="confirmPassword" id="confirmPassword" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                            <span class="input-group-text cursor-pointer"><i data-feather="eye" class="icon"></i></span>
                        </div>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary me-2">Change Password</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!--/ Change Password -->

    <!-- Two-steps verification -->
    <div class="card">
        <div class="card-body">
            <h4 class="card-title mb-50">2FA Verification</h4>
            <span>Keep your account secure with 2FA authentication step.</span>
            <h6 class="fw-bolder mt-2 {{ empty($user->google2fa_secret)?"text-danger":"text-success" }}">Google Authenticator 2FA Verification
                <i data-feather="{{ empty($user->google2fa_secret)?"unlock":"lock" }}" class="font-medium-5 icon"></i>
                @if(empty($user->google2fa_secret))
                    <small>disabled</small>
                @else
                    <small>enabled</small>
                @endif
                <a type="button" class="text-body me-50" data-target="#setup_google2fa" data-toggle="modal">
                    <i data-feather="settings" class="font-medium-3 icon"></i>
                </a>
                @if($user->google2fa_secret)
                    <a type="button" class="text-body me-50" data-target="#remove_google2fa" data-toggle="modal">
                        <i data-feather="trash" class="font-medium-3 icon"></i>
                    </a>
                @endif
            </h6>
            <div class="d-flex justify-content-between border-bottom mb-1 pb-1">
                @if($user->google2fa_secret)
                <span>Current Code:</span><span>{{ $user->google2fa_secret }}</span>
                @endif
            </div>

        </div>
    </div>

@endsection
@section('user-security')

    <div class="modal fade text-left" id="setup_google2fa" tabindex="-1" role="dialog" aria-labelledby="myModalLabel18" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form action="{{ route("user.store.security") }}" method="post">
                @csrf
                <input type="hidden" name="user_id" value="{{ $user->id }}">
                <input type="hidden" name="google2fa_secret" value="{{ $qr_data['google2fa_secret'] }}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel18">Set up Google Authenticator</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default">

                                    <div class="panel-body" style="text-align: center;">
                                        <p>Set up your two factor authentication by scanning the QR Code below. Alternatively, you can use the code <strong>{{ $qr_data['google2fa_secret'] }}</strong></p>
                                        <div>
                                            {!! $QR_Image !!}
                                        </div>
                                        <p>You must set up your Google Authenticator app before continuing. You will be unable to login otherwise</p>
                                        <p>On every Login your will be asked to enter 6 Digit code from your already setup Google Authenticator App</p>
                                        <div>
                                            <div class="form-group">
                                                <label for="authenticator_code">Authenticator Code</label>
                                                <input type="text" id="authenticator_code" required class="form-control offset-4 col-md-4" name="authenticator_code">
                                            </div>
                                            {{--                            <a href="/complete-registration"><button class="btn-primary">Complete Registration</button></a>--}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Enable & Continue</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="modal fade text-left" id="remove_google2fa" tabindex="-1" role="dialog" aria-labelledby="myModalLabel18" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form action="{{ route("user.store.security") }}" method="post">
                @csrf
                <input type="hidden" name="user_id" value="{{ $user->id }}">
                <input type="hidden" name="google2fa_secret" value="">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel18">Remove Google Authenticator</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default">

                                    <div class="panel-body" style="text-align: center;">
                                        <p>You are about to Remove Google 2FA authentication. <br> Your current code is <strong>{{ $user->google2fa_secret }}</strong></p>
                                        <p class="text-info">After your Remove 2FA you will not be asked to enter 6 Digit code from your Google Authenticator App</p>
                                        <div>
                                            <div class="form-group">
                                                <label for="authenticator_code">Authenticator Code</label>
                                                <input type="text" id="authenticator_code" required class="form-control offset-4 col-md-4" name="authenticator_code">
                                            </div>
                                            {{--                            <a href="/complete-registration"><button class="btn-primary">Complete Registration</button></a>--}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Remove & Continue</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
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
    <!-- BEGIN: Page JS-->
    <script src="{{ asset("app-assets/js/scripts/components/components-modals.js") }}"></script>

    {{--    <script src="{{ asset("app-assets/vendors/js/forms/cleave/cleave.min.js") }}"></script>--}}
{{--    <script src="{{ asset("app-assets/vendors/js/forms/cleave/addons/cleave-phone.us.js") }}"></script>--}}
{{--    <script src="{{ asset("app-assets/vendors/js/forms/validation/jquery.validate.min.js") }}"></script>--}}
{{--    <script src="{{ asset("app-assets/js/scripts/pages/modal-two-factor-auth.js") }}"></script>--}}
{{--    <script src="{{ asset("app-assets/js/scripts/pages/modal-edit-user.js") }}"></script>--}}
{{--    <script src="{{ asset("app-assets/js/scripts/pages/app-user-view-security.js") }}"></script>--}}
{{--    <script src="{{ asset("app-assets/js/scripts/pages/app-user-view.js") }}"></script>--}}
    <!-- END: Page JS-->
@endsection
