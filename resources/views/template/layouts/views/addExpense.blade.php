<div class="modal modal-slide-in new-shop-modal fade" id="modals-slide-in">
    <div class="modal-dialog">
        <form class="add-new-shop modal-content pt-0" id="addShop" method="POST" action="{{route('expense.store')}}" enctype="multipart/form-data">
            @csrf
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
            <div class="modal-header mb-1">
                <h5 class="modal-title" id="shopTitle">New Expense</h5>
            </div>
            <div class="modal-body flex-grow-1">
                <div class="form-group">
                    <label class="form-label" for="basic-icon-default-shopName">Name</label>
                    <input type="text" class="form-control dt-full-name" id="name" autocomplete="off"  value="{{old('name')}}" required placeholder="Name" name="name" aria-label="Store Name" aria-describedby="basic-icon-default-fullname2" />
                    @error('name')
                        <span class="error">{{ucwords($message)}}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="form-label" for="category_id">Category</label>
                    <select class="form-control" name="category_id" id="category_id">
                        <option disabled selected>Select Category</option>
                        @foreach(\App\Models\Category::all() as $cat)
                            <option value="{{ $cat->id }}">{{ ucwords($cat->name) }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                    <span class="error">{{ucwords($message)}}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="form-label" for="recurring_type">Recurring Type</label>
                    <select class="form-control" name="recurring_type" id="recurring_type">
                        <option disabled selected>Select Recurring Type</option>
                        <option value="no_repeat">Doesn't Repeat</option>
                        <option value="daily">Daily</option>
                        <option value="weekly">Weekly</option>
                        <option value="monthly">Monthly</option>
                        <option value="yearly">Annually</option>
                    </select>
                    @error('recurring_type')
                    <span class="error">{{ucwords($message)}}</span>
                    @enderror
                </div>
                <div class="form-group d-none" id="repeat_date_area">
                    <label class="form-label" for="basic-icon-default-apiKey">Repeat Date
                        <span title="Selected Date will be used to Repeat Respectively with Selected Recurring Type"><i class="icon" data-feather='alert-circle'></i></span>
                    </label>
                    <input type="date" min="01" step=".01" class="form-control dt-full-name" id="repeat_date" autocomplete="off"  placeholder="Expense Repeat Date" value="{{old('repeat_date')}}" name="repeat_date" aria-label="key" aria-describedby="basic-icon-default-fullname2" />
                    @error('repeat_date')
                    <span class="error">{{ucwords($message)}}</span>
                    @enderror
                </div>
                <div class="form-group d-none" id="repeat_day_area">
                    <label class="form-label" for="basic-icon-default-apiKey">Repeat Day
                        {{--                        <small>( Optional )</small>--}}
                    </label>
                    <select class="form-control dt-full-name" id="repeat_day" name="repeat_day">
                        <option disabled selected>Select Day</option>
                        <option {{old('repeat_day') == 'monday'?'selected':''}} value="monday">Monday</option>
                        <option {{old('repeat_day') == 'tuesday'?'selected':''}} value="tuesday">Tuesday</option>
                        <option {{old('repeat_day') == 'wednesday'?'selected':''}} value="wednesday">Wednesday</option>
                        <option {{old('repeat_day') == 'thursday'?'selected':''}} value="thursday">Thursday</option>
                        <option {{old('repeat_day') == 'friday'?'selected':''}} value="friday">Friday</option>
                        <option {{old('repeat_day') == 'saturday'?'selected':''}} value="saturday">Saturday</option>
                        <option {{old('repeat_day') == 'sunday'?'selected':''}} value="sunday">Sunday</option>
                    </select>
                    @error('repeat_day')
                    <span class="error">{{ucwords($message)}}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="form-label" for="basic-icon-default-apiKey">Expense Date</label>
                    <input type="date" step=".01" class="form-control dt-full-name" id="date" autocomplete="off"  placeholder="Expense Date" value="{{old('date')}}" required name="date" aria-label="key" aria-describedby="basic-icon-default-fullname2" />
                    @error('date')
                    <span class="error">{{ucwords($message)}}</span>
                    @enderror
                    <input type="checkbox" class="mt-2 ml-2" id="notify_me" {{old('notify_me')?"checked":""}} name="notify_me" />
                    <label for="notify_me">Notify Me</label>

                    <div id="notification_date_area">
                        <label class="form-label" for="basic-icon-default-apiKey">Notification Date</label>
                        <input type="date" class="form-control dt-full-name" id="notification_date" autocomplete="off"  placeholder="Expense Date" value="{{old('notification_date')}}" name="notification_date" aria-label="key" aria-describedby="basic-icon-default-fullname2" />
                        @error('notification_date')
                        <span class="error">{{ucwords($message)}}</span>
                        @enderror
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label" for="basic-icon-default-apiKey">Amount</label>
                    <input type="number" step=".01" class="form-control dt-full-name" id="amount" autocomplete="off"  placeholder="Expense Amount" value="{{old('amount')}}" required name="amount" aria-label="key" aria-describedby="basic-icon-default-fullname2" />
                    @error('amount')
                        <span class="error">{{ucwords($message)}}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="form-label" for="basic-icon-default-apiKey">Description</label>

                    <textarea class="form-control dt-full-name" id="description" autocomplete="off"  required name="description" aria-label="key" aria-describedby="basic-icon-default-fullname2" cols="30" rows="10">{{old('description')}}</textarea>
                    @error('description')
                    <span class="error">{{ucwords($message)}}</span>
                    @enderror
                </div>
                <button type="submit" id="sub_btn_shop_save_form" class="btn btn-primary mr-1 data-submit">Submit</button>
                <button type="reset" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </form>
    </div>
</div>
<div class="modal modal-slide-in new-shop-modal fade" id="modals-slide-in-show">
    <div class="modal-dialog">
        <form class="add-new-shop modal-content pt-0" id="addShop" method="POST" action="{{route('expense.store')}}" enctype="multipart/form-data">
            @csrf
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
            <div class="modal-header mb-1">
                <h5 class="modal-title" id="shopTitle">Expense Detail</h5>
            </div>
            <div class="modal-body flex-grow-1">
                <div class="form-group">
                    <label class="form-label" for="basic-icon-default-shopName">Name</label>
                    <p id="name-show"></p>
                </div>
                <div class="form-group">
                    <label class="form-label" for="category_id">Category</label>
                    <p id="category_id_show"></p>
                </div>
                <div class="form-group">
                    <label class="form-label" for="recurring_type">Recurring Type</label>
                    <p id="recurring_type_show"></p>
                </div>
                <div class="form-group">
                    <label class="form-label" for="basic-icon-default-apiKey">Repeat Date / Day</label>
                    <p id="repeat_date_show"></p>
                </div>
                <div class="form-group">
                    <label class="form-label" for="basic-icon-default-apiKey">Expense Date</label>
                    <p id="date_show"></p>
                </div>
                <div class="form-group">
                    <label class="form-label" for="basic-icon-default-apiKey">Amount</label>
                    <p id="amount_show"></p>
                </div>
                <div class="form-group">
                    <label class="form-label" for="basic-icon-default-apiKey">Description</label>
                    <p id="description_show"></p>
                </div>
{{--                <button type="submit" id="sub_btn_shop_save_form" class="btn btn-primary mr-1 data-submit">Submit</button>--}}
                <button type="reset" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
            </div>
        </form>
    </div>
</div>
