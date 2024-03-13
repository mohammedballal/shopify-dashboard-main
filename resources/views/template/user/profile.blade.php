@extends("template.layouts.master",['page_title'=>"Update Profile"])
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
@section("main-content")
    <div class="content-header row">
    </div>
    <div class="content-body">
        <!-- users edit start -->
        <section class="app-user-edit">
            <div class="card">
                <div class="card-body">
                    <div class="tab-content">
                        <!-- Account Tab starts -->
                        <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">
                            <!-- users edit media object start -->
                            <div class="media mb-2">
                                @if(isset($user->avatar))
                                    <img src="{{asset('media/users/avatar/'.$user->avatar)}}" alt="users avatar" class="user-avatar users-avatar-shadow rounded mr-2 my-25 cursor-pointer" height="90" width="90" />
                                @else
                                    <img src="{{asset('app-assets/images/avatars/'.rand(1,12).'.png')}}" alt="users avatar" class="user-avatar users-avatar-shadow rounded mr-2 my-25 cursor-pointer" height="90" width="90" />
                                @endif
                                <div class="media-body mt-50">
                                    <h4>{{ ucwords($user->name) }}</h4>
                                    <span>
                                        @foreach((json_decode($user->tag_id) ??  array()) as $tag)
                                            <span class="badge badge-light-info mt-25">{{ $tag }}</span>
                                            @if($loop->iteration % 2 == 0)
                                                <br>
                                            @endif
                                        @endforeach
                                    </span>
                                </div>
                            </div>
                            <!-- users edit media object ends -->
                            <!-- users edit account form start -->
                            <form class="" method="post" action="{{ route("users.profile.store") }}" id="user_edit_form" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="id" value="{{ $user->id }}">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="basic-icon-default-firstname">First Name</label>
                                            <input type="text" class="form-control dt-full-name" id="basic-icon-default-firstname" value="{{old('first_name')?old("first_name"):$user->first_name}}" required placeholder="John" name="first_name" aria-label="John" aria-describedby="basic-icon-default-fullname2" />
                                            @error('first_name')
                                                <span class="error">{{ucwords($message)}}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="basic-icon-default-lastname">Last Name</label>
                                            <input type="text" class="form-control dt-full-name" id="basic-icon-default-lastname" placeholder="Doe" value="{{old('last_name')?old("last_name"):$user->last_name}}" required name="last_name" aria-label="Doe" aria-describedby="basic-icon-default-fullname2" />
                                            @error('last_name')
                                            <span class="error">{{ucwords($message)}}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="basic-icon-default-email">Email</label>
                                            <input disabled type="text" id="basic-icon-default-email" class="form-control dt-email" required placeholder="john.doe@example.com" aria-label="john.doe@example.com" aria-describedby="basic-icon-default-email2" value="{{old('email')?old("email"):$user->email}}" name="email" />
                                            @error('email')
                                            <span class="error">{{ucwords($message)}}</span>
                                            @enderror
                                            <small class="form-text text-muted"> You can use letters, numbers & periods </small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="d-flex justify-content-between">
                                                <label for="login-password">Password <small>( Optional )</small></label>
                                            </div>
                                            <div class="input-group input-group-merge form-password-toggle">
                                                <input type="password" class="form-control form-control-merge" id="login-password" name="password" tabindex="2" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="login-password" />
                                                <div class="input-group-append">
                                                    <span class="input-group-text cursor-pointer"><i class="icon" data-feather="eye"></i></span>
                                                </div>
                                            </div>
                                            @error('password')
                                            <span class="error">{{ucwords($message)}}</span>
                                            @enderror
                                            <small class="form-text text-muted"> Make a strong password mixed of uppercase & lowercase letters with numbers</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="basic-icon-default-avatar">Avatar</label>
                                            <input type="file" accept="image/*" id="basic-icon-default-avatar" class="form-control" name="avatar" />
                                            @error('avatar')
                                            <span class="error">{{ucwords($message)}}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="basic-icon-default-phone">phone</label>
                                            <input type="tel" id="basic-icon-default-phone" class="form-control dt-uname" placeholder="+1-224-3238312" aria-label="+1-224-3238312" aria-describedby="basic-icon-default-uname2" value="{{old('phone')?old("phone"):$user->phone}}" name="phone" />
                                            @error('phone')
                                            <span class="error">{{ucwords($message)}}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12 d-flex flex-sm-row flex-column mt-2">
                                        <button type="submit" id="sub_btn_user_edit_form" class="btn btn-primary mb-1 mb-sm-0 mr-0 mr-sm-1">Save Changes</button>
                                        <button type="reset" class="btn btn-outline-secondary">Reset</button>
                                    </div>
                                </div>
                            </form>
                            <!-- users edit account form ends -->
                        </div>
                        <!-- Account Tab ends -->
                    </div>
                </div>
            </div>
        </section>
        <!-- users edit ends -->

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
    <script>
        function showTag(value) {
            let element = document.getElementById('tagIdSection');
            var input_id = "#basic-icon-default-tag_id";
            if(value === '1'){
                element.classList.add('d-none');
                $(input_id).attr("required","required")
            }else{
                element.classList.remove('d-none');
                $(input_id).removeAttr("required")
            }
        }
        $("#sub_btn_user_edit_form").on('click', function (e) {
            validate_form("#sub_btn_user_edit_form");
        });
    </script>
@endsection
