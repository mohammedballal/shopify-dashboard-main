<div class="modal modal-slide-in label-sidebar fade" id="add-new-label-sidebar">
    <div class="modal-dialog sidebar-lg">
        <div class="modal-content p-0">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
            <div class="modal-header mb-1">
                <h5 class="modal-title">Add Label</h5>
            </div>
            <div class="modal-body flex-grow-1 pb-sm-0 pb-3">
                <form class="event-forms needs-validation" method="POST" action="{{route('label.store')}}" data-ajax="false">
                    @csrf
                    <div class="form-group">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="name" placeholder="Label Title" required />
                    </div>
                    <div class="form-group">
                        <label for="color" class="form-label">Color</label>
                        <input type="color" class="form-control" id="color" style="padding: 5px;width: 50px;border-radius: 16px;height: 50px;" name="color" required />
                    </div>
                    <div class="form-group d-flex">
                        <button type="submit" class="btn btn-primary add-event-btn mr-1">Add</button>
                        <button type="button" class="btn btn-outline-secondary btn-cancel" data-dismiss="modal">Cancel</button>
                    </div>
                </form>
                <hr>
                <h2>Labels</h2>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Title</th>
                        <th>Color</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <style>
                        .box {
                            float: left;
                            height: 20px;
                            width: 100%;
                            border: 1px solid black;
                            clear: both;
                        }
                    </style>
                        @forelse($labels as $label)
                            <tr>
                                <td>{{$label->name}}</td>
                                <td>
                                   <div class='box' style="background-color:{{$label->color}}!important;"></div>
                                </td>
                                <td>
                                    <a href="{{route('label.delete',$label->id)}}" class="btn btn-sm btn-danger rounded-pill">Delete</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">No Labels Yet!</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
