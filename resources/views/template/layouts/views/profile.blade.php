@extends("template.layouts.master")
@section("main-content")
    <div class="content-header row">
    </div>
    <div class="content-body">
        <section class="app-user-view-security">
            <div class="row">
                <!-- User Sidebar -->
                <div class="col-xl-4 col-lg-5 col-md-5 order-1 order-md-0">
                    <!-- User Card -->
                    <div class="card">
                        <div class="card-body">
                            <div class="user-avatar-section">
                                <div class="d-flex align-items-center flex-column">
                                    @if(isset($user->avatar))
                                        <img
                                            class="img-fluid rounded mt-3 mb-2"
                                            src="{{asset('media/users/avatar/'.$user->avatar)}}"
                                            height="110"
                                            width="110"
                                            alt="{{$user->name}}-avatar"
                                        />
                                    @else
                                        <img
                                            class="img-fluid rounded mt-3 mb-2"
                                            src="{{asset('media/users/avatar/'.$user->avatar)}}"
                                            height="110"
                                            width="110"
                                            alt="{{$user->name}}-avatar"
                                        />
                                    @endif
                                    <div class="user-info text-center">
                                        <h4>{{$user->name}}</h4>
                                        <span class="badge bg-light-secondary">{{$user->roles->first()->name}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-around my-2 pt-75">
                                <div class="d-flex align-items-start me-2">
                                  <span class="badge bg-light-primary p-75 rounded">
                                    <i data-feather="dollar-sign" class="icon"></i>
                                  </span>
                                    <div class="ms-75">
                                        <h4 class="mb-0">{{$user->sales()}}</h4>
                                        <small>Total Sales</small>
                                    </div>
                                </div>
                                <div class="d-flex align-items-start">
                                  <span class="badge bg-light-primary p-75 rounded">
                                    <i data-feather="database" class="icon"></i>
                                  </span>
                                    <div class="ms-75">
                                        <h4 class="mb-0">{{$user->products()}}</h4>
                                        <small>Products Sold</small>
                                    </div>
                                </div>
                            </div>
                            <h4 class="fw-bolder border-bottom pb-50 mb-1">Details</h4>
                            <div class="info-container">
                                <ul class="list-unstyled">
                                    <li class="mb-75">
                                        <span class="fw-bolder me-25">First Name:</span>
                                        <span>{{$user->first_name}}</span>
                                    </li>
                                    <li class="mb-75">
                                        <span class="fw-bolder me-25">Last Name:</span>
                                        <span>{{$user->last_name}}</span>
                                    </li>
                                    <li class="mb-75">
                                        <span class="fw-bolder me-25">Email:</span>
                                        <span>{{$user->email}}</span>
                                    </li>
                                    <li class="mb-75">
                                        <span class="fw-bolder me-25">Commission:</span>
                                        <span>{{$user->commission}}</span>
                                    </li>
                                    <li class="mb-75">
                                        <span class="fw-bolder me-25">Tags:</span>
                                        @foreach((json_decode($user->tag_id) ??  array()) as $tag)
                                            <span class="badge badge-light-info mt-25">{{ $tag }}</span>
                                            @if($loop->iteration % 3 == 0)
                                                <br>
                                            @endif
                                        @endforeach
                                    </li>
                                    <li class="mb-75">
                                        <span class="fw-bolder me-25">Status:</span>
                                        <span class="badge bg-light-{{$user->status?'success':'danger'}}">{{$user->status?'Active':'InActive'}}</span>
                                    </li>
                                    <li class="mb-75">
                                        <span class="fw-bolder me-25">Role:</span>
                                        <span>{{$user->roles->first()->name}}</span>
                                    </li>
                                    <li class="mb-75">
                                        <span class="fw-bolder me-25">Contact:</span>
                                        <span>{{$user->phone}}</span>
                                    </li>
                                </ul>
                                <div class="d-flex justify-content-center pt-2">
                                    <a href="{{route('user.profile.edit','user_id='.$user->id)}}" class="btn btn-primary me-1" data-bs-target="#editUser" data-bs-toggle="modal">
                                        Edit
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /User Card -->
                </div>
                <!--/ User Sidebar -->

                <!-- User Content -->
                <div class="col-xl-8 col-lg-7 col-md-7 order-0 order-md-1">
                    <!-- User Pills -->
                    <ul class="nav nav-pills mb-2">
                        <li class="nav-item">
                            <a class="nav-link {{ ((preg_match("/(user.account)/",\Illuminate\Support\Facades\Route::currentRouteName())))?"active":"" }}" href="{{route('user.account')}}">
                                <i data-feather="user" class="font-medium-3 me-50 icon"></i>
                                <span class="fw-bold">Account</span></a
                            >
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ ((preg_match("/(user.wallet)/",\Illuminate\Support\Facades\Route::currentRouteName())))?"active":"" }}" href="#">
                                <i data-feather="bookmark" class="font-medium-3 me-50 icon"></i>
                                <span class="fw-bold">My Wallet</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ ((preg_match("/(user.notifications)/",\Illuminate\Support\Facades\Route::currentRouteName())))?"active":"" }}" href="#">
                                <i data-feather="bell" class="font-medium-3 me-50 icon"></i><span class="fw-bold">Notifications</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ ((preg_match("/(user.security)/",\Illuminate\Support\Facades\Route::currentRouteName())))?"active":"" }}" href="{{route('user.security')}}">
                                <i data-feather="lock" class="font-medium-3 me-50 icon"></i>
                                <span class="fw-bold">Security</span>
                            </a>
                        </li>
                    </ul>
                    <!--/ User Pills -->

                    @section("user-content")
                        @show
                </div>
                <!--/ User Content -->
            </div>
        </section>
        @section('user-security')
            @show
    </div>
@endsection
