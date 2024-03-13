@extends("template.layouts.master",['page_title'=>"Tickets List"])
@section("vendorCss")
    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/vendors/css/vendors.min.css") }}">
    <!-- END: Vendor CSS-->
    <link href="{{asset('assets/charts/chartist.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/charts/chartist-plugin-tooltip.css')}}" rel="stylesheet">
    <link href="{{asset('assets/charts/c3.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/charts/style.min.css')}}" rel="stylesheet">
@endsection
@section("pageCss")
    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/css/core/menu/menu-types/vertical-menu.css")}}">
    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/css/pages/app-user.css")}}">
    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/css/pages/modal-create-app.min.css")}}">
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <style>
        .avatar-contents{
                width: 52px;
                height: 52px;
                display: flex;
                justify-content: center;
                align-items: center;
                border-radius: 50%;
                font-size: 0.857rem;
        }
    </style>
    <!-- END: Page CSS-->
@endsection
@section("main-content")
    <div class="content-header row">
    </div>
    <div class="content-body">
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">{{$ticket->title}} - {{sprintf('%06d', $ticket->id)}}</h4>
                        {!! $ticket->description !!}
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Ticket Replies</h4>
                        <ul class="list-unstyled mt-5">
                            @forelse($ticket->replies as $reply)
                                <li class="media">
                                    @if($reply->user_id == Auth::user()->id)
                                        <div class="media-body">
                                            <h5 class="mt-0 mb-1">{{$reply->user->name}}</h5>
                                            {!! $reply->reply !!}
                                        </div>
                                        @if(isset($reply->user->avatar))
                                        <img class="mr-3" src="{{asset('media/users/avatar/'.$reply->user->avatar)}}" width="60" alt="{{$reply->user->name.'- Avatar'}}">
                                        @else
                                            <span class="avatar">
                                                <span class="avatar-contents">{{substr($reply->user->name,0,2)}}</span>
                                            </span>
                                        @endif
                                    @else
                                    @if(isset($reply->user->avatar))
                                        <img class="mr-3" src="{{asset('media/users/avatar/'.$reply->user->avatar)}}" width="60" alt="{{$reply->user->name.'- Avatar'}}">
                                        @else
                                        <span class="avatar">
                                            <span class="avatar-contents">{{substr($reply->user->name,0,2)}}</span>
                                        </span>
                                        @endif
                                        <div class="media-body">
                                            <h5 class="mt-0 mb-1">{{$reply->user->name}}</h5>
                                            {!! $reply->reply !!}
                                        </div>
                                    @endif
                                </li>
                                @if(!$loop->last)
                                    <hr>
                                @endif
                            @empty
                                <li class="media">
                                    <div class="media-body">
                                        <h5 class="mt-0 mb-1">{{Auth::user()->hasRole('User')?'We will get back to you soon':'OOOps!No reply yet'}}</h5>
                                    </div>
                                </li>
                                <hr>
                            @endforelse
                        </ul>
                    </div>
                </div>
                    <div class="card">
                        <div class="card-body">
                            <h4 class="mb-3">Write a reply</h4>
                            <form method="post" action="{{route('ticket.update',$ticket->id)}}" id="replyForm">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="reply" id="reply">
                                <input type="hidden" name="status" value="0" id="status">
                                <div id="toolbar-container"></div>
                                <div id="editor"></div>
                                <button type="submit" class="mt-3 btn waves-effect waves-light btn-success">Reply</button>
                                <button type="button" id="rac" class="mt-3 btn waves-effect waves-light btn-info">Reply & close</button>
                            </form>
                        </div>
                    </div>
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Ticket Info</h4>
                    </div>
                    <div class="card-body {{Auth::user()->layout?'bg-gradient-dark':'bg-light'}}">
                        <div class="row text-center">
                            <div class="col-6 mt-2 mb-2">
                                @php
                                    if ($ticket->status == '0'){
                                        $status ='Open';
                                        $color ='danger';
                                    }elseif ($ticket->status == '1'){
                                        $status ='In Progress';
                                        $color ='warning';
                                    }else{
                                        $status ='Closed';
                                        $color ='success';
                                    }
                                @endphp
                                <span class="text-{{$color}}">{{$status}}</span>
                            </div>
                            <div class="col-6 mt-2 mb-2">
                                {{date('M d, Y h:i',strtotime($ticket->created_at))}}
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <h5 class="pt-2">Ticket Creator</h5>
                                <span>{{$ticket->user->name}}</span>
                                <h5 class="mt-2">Support Staff</h5>
                                <span>{{$ticket->agent?$ticket->agent->name:'Agent Name'}}</span>
                            </div>
                            <div class="col-6">
                                <h5 class="pt-2">Status</h5>
                                @if(auth()->user()->hasRole('User'))
                                    <a href="{{route('ticket.updateStatus',[$ticket->id,2])}}" class="btn btn-success rounded-pill">Mark as Closed</a>
                                @else
                                    <select id="updateStatus" class="form-control" onchange="updateTicketStatus(this.value)">
                                        <option disabled>Select Status</option>
                                        <option {{$ticket->status == '0'?'selected':''}} value="0">Open</option>
                                        <option {{$ticket->status == '1'?'selected':''}} value="1">In Progress</option>
                                        <option {{$ticket->status == '2'?'selected':''}} value="2">Closed</option>
                                    </select>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @if(!Auth::user()->hasRole('User'))
                    <div class="card">
                        <div class="card-body text-center">
                            <h4 class="card-title">User Info</h4>
                            <div class="profile-pic mb-3 mt-3">
                                <img src="{{asset('media/users/avatar/'.$ticket->user->avatar)}}" width="150" class="rounded-circle" alt="{{$ticket->user->name.'- Avatar'}}">
                                <h4 class="mt-3 mb-0">{{$ticket->user->name}}</h4>
                                <a href="mailto:{{$ticket->user->email}}">{{$ticket->user->email}}</a>
                            </div>
                            <div class="row text-center mt-5">
                                <div class="col-3">
                                    <h3 class="font-bold">{{$counts['all']}}</h3>
                                    <h6>Total</h6></div>
                                <div class="col-2" style="padding: 0px 5px 0px 5px!important;">
                                    <h3 class="font-bold">{{$counts['open']}}</h3>
                                    <h6>Open</h6></div>
                                <div class="col-4">
                                    <h3 class="font-bold">{{$counts['inProgress']}}</h3>
                                    <h6>In Progress</h6>
                                </div>
                                <div class="col-3">
                                    <h3 class="font-bold">{{$counts['closed']}}</h3>
                                    <h6>Closed</h6>
                                </div>
                            </div>
                        </div>
                            <div class="row text-center">
                                <div class="col-12">
                                    <a href="{{route('users.show',['user_id'=>$ticket->user_id])}}" class="badge rounded-pill bg-gradient-primary d-flex align-items-center justify-content-center">
                                        User Profile</a>
                                </div>
                            </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Ticket Statistics</h4>
                            <div id="visitor" style="height:290px; width:100%;" class="mt-3"></div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
@section("pageVendorJs")
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js" type="text/javascript"></script>

    <script src="{{ asset("app-assets/vendors/js/charts/apexcharts.min.js") }}"></script>
    <script src="{{asset('assets/charts/chartist.min.js')}}"></script>
    <script src="{{asset('assets/charts/chartist-plugin-tooltip.min.js')}}"></script>
    <script src="{{asset('assets/charts/d3.min.js')}}"></script>
    <script src="{{asset('assets/charts/c3.min.js')}}"></script>
@endsection
@section("pageJsLower")
    <script>
        function updateTicketStatus(status) {
           window.location = window.location.origin+'/ticket/{{$ticket->id}}/update/'+status+'/status';
        }

        var toolbarOptions = [['bold', 'italic','underline','link']];
        var quill = new Quill('#editor', {
            modules: {
                toolbar: toolbarOptions
            },
            placeholder: 'Reply',
            theme: 'snow'
        });

        $("#rac").on("click",function(){
            $('#status').val(1);
            $("#replyForm").submit();
        });
        $("#replyForm").on("submit",function(){
            $("#reply").val($(".ql-editor").html());
        });
        var chart = c3.generate({
            bindto: '#visitor',
            data: {
                columns: [
                    ['All', {{$counts['all']}}],
                    ['Open', {{$counts['open']}}],
                    ['In progress', {{$counts['inProgress']}}],
                    ['Closed', {{$counts['closed']}}],
                ],

                type: 'donut',
                tooltip: {
                    show: true
                }
            },
            donut: {
                label: {
                    show: false
                },
                title: "Tickets",
                width: 35,

            },

            legend: {
                hide: true
            },
            color: {
                pattern: ['#7166eb', '#ca4e52', '#715644', '#28a064']
            }
        });
    </script>
@endsection
