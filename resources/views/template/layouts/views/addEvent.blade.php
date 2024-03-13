<div class="modal modal-slide-in event-sidebar fade" id="add-new-event-sidebar">
    <div class="modal-dialog sidebar-lg">
        <div class="modal-content p-0">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
            <div class="modal-header mb-1">
                <h5 class="modal-title">Add Event</h5>
            </div>
            <div class="modal-body flex-grow-1 pb-sm-0 pb-3">
                <form class="event-forms needs-validation" method="POST" action="#" data-ajax="false" novalidate>
                    @csrf
                    <div class="form-group">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" placeholder="Event Title" required />
                    </div>
                    <div class="form-group">
                        <label for="select-label" class="form-label">Label</label>
                        <select class="select2 select-label form-control w-100" id="select-label" required name="label">
                            <option disabled selected>Select Label</option>
                            @forelse($labels as $label)
                                <option data-label="{{$label->color}}" value="{{$label->id}}">{{$label->name}}</option>
                            @empty
                                <option data-label="red" disabled selected>Add Labels</option>
                            @endforelse
                        </select>
                    </div>
                    <div class="form-group position-relative">
                        <label for="start-date" class="form-label">Start Date</label>
                        <input type="text" class="form-control" id="start-date" name="start_date" required placeholder="Start Date" />
                    </div>
                    <div class="form-group position-relative">
                        <label for="end-date" class="form-label">End Date</label>
                        <input type="text" class="form-control" id="end-date" name="end_date" required placeholder="End Date" />
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" name="all_day" class="custom-control-input allDay-switch" id="customSwitch3" />
                            <label class="custom-control-label" for="customSwitch3">All Day</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="event-url" class="form-label">Event URL</label>
                        <input type="url" name="url" class="form-control" id="event-url" placeholder="https://www.google.com" />
                    </div>
                    <div class="form-group select2-primary">
                        <label for="event-guests" class="form-label">Add Guests</label>
                        <select class="select2 select-add-guests form-control w-100" name="event_guests[]" id="event-guests" multiple>
                            @forelse($users as $user)
                                <option data-avatar="{{@$user->avatar?asset('media/users/avatar/'.$user->avatar):'app-assets/images/avatars/'.rand(1,12).'-small.png'}}" value="{{$user->id}}">{{$user->name}}</option>
                            @empty
                                <option data-avatar="{{asset('app-assets/images/avatars/'.rand(1,12).'-small.png')}}" disabled>Add Users</option>
                            @endforelse
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="event-location" class="form-label">Location</label>
                        <input type="text" class="form-control" id="event-location"  name="location" placeholder="Enter Location" />
                    </div>
                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea name="description" id="event-description-editor" class="form-control"></textarea>
                    </div>
                    <div class="form-group d-flex">
                        <button type="submit" class="btn btn-primary add-event-btn mr-1">Add</button>
                        <button type="button" class="btn btn-outline-secondary btn-cancel" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary update-event-btn d-none mr-1">Update</button>
                        <button class="btn btn-outline-danger btn-delete-event d-none">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
