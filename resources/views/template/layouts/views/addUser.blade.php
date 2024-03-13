<div class="modal modal-slide-in new-user-modal fade" id="modals-slide-in">
    <div class="modal-dialog">
        <form class="add-new-user modal-content pt-0" method="POST" action="{{route('user.store')}}" enctype="multipart/form-data">
            @csrf
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
            <div class="modal-header mb-1">
                <h5 class="modal-title" id="exampleModalLabel">New User</h5>
            </div>
            <div class="modal-body flex-grow-1">
                <div class="form-group">
                    <label class="form-label" for="basic-icon-default-firstname">First Name</label>
                    <input type="text" class="form-control dt-full-name" id="basic-icon-default-firstname" value="{{old('first_name')}}" required placeholder="John" name="first_name" aria-label="John" aria-describedby="basic-icon-default-fullname2" />
                    @error('first_name')
                        <span class="error">{{ucwords($message)}}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="form-label" for="basic-icon-default-lastname">Last Name</label>
                    <input type="text" class="form-control dt-full-name" id="basic-icon-default-lastname" placeholder="Doe" value="{{old('last_name')}}" required name="last_name" aria-label="Doe" aria-describedby="basic-icon-default-fullname2" />
                    @error('last_name')
                        <span class="error">{{ucwords($message)}}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="form-label" for="basic-icon-default-email">Email</label>
                    <input type="text" id="basic-icon-default-email" class="form-control dt-email" required placeholder="john.doe@example.com" aria-label="john.doe@example.com" aria-describedby="basic-icon-default-email2" value="{{old('email')}}" name="email" />
                    @error('email')
                        <span class="error">{{ucwords($message)}}</span>
                    @enderror
                    <small class="form-text text-muted"> You can use letters, numbers & periods </small>
                </div>
                <div class="form-group">
                    <div class="d-flex justify-content-between">
                        <label for="login-password">Password</label>
                    </div>
                    <div class="input-group input-group-merge form-password-toggle">
                        <input type="password" required class="form-control form-control-merge" id="login-password" name="password" tabindex="2" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="login-password" />
                        <div class="input-group-append">
                            <span class="input-group-text cursor-pointer"><i class="icon" data-feather="eye"></i></span>
                        </div>
                    </div>
                    @error('password')
                        <span class="error">{{ucwords($message)}}</span>
                    @enderror
                    <small class="form-text text-muted"> Make a strong password mixed of uppercase & lowercase letters with numbers</small>
                </div>
                <div class="form-group">
                    <label class="form-label" for="basic-icon-default-avatar">Avatar</label>
                    <input type="file" accept="image/*" id="basic-icon-default-avatar" class="form-control" name="avatar" />
                    @error('avatar')
                        <span class="error">{{ucwords($message)}}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="form-label" for="basic-icon-default-phone">Phone</label>
                    <input type="tel" id="basic-icon-default-phone" class="form-control dt-uname" placeholder="+1-224-3238312" aria-label="+1-224-3238312" aria-describedby="basic-icon-default-uname2" value="{{old('phone')}}" name="phone" />
                    @error('phone')
                         <span class="error">{{ucwords($message)}}</span>
                    @enderror
                </div>
                @if(Auth::user()->hasRole("Super Admin"))
                <div class="form-group">
                    <label class="form-label" for="user-role">User Role</label>
                    <select id="user-role" required name="role" onchange="showTag(this.value)" class="form-control">
                        <option selected disabled>Select Role</option>
                        @foreach(getRoles() as  $role)
                            <option value="{{$role->id}}">{{ucwords($role->name)}}</option>
                        @endforeach
                    </select>
                    @error('role')
                        <span class="error">{{ucwords($message)}}</span>
                    @enderror
                </div>
                @else
                    @php
                        $role = \Spatie\Permission\Models\Role::where('name','User')->first();
                    @endphp
                    <input type="hidden" name="role" value="{{$role->id}}">
                @endif
                <div class="form-group" id="comm">
                    <label class="form-label" for="basic-icon-default-commission">Commission in %</label>
                    <input type="number" min="1" max="100" id="basic-icon-default-commission" class="form-control dt-uname" placeholder="25%" value="{{old('commission')}}" name="commission" />
                    @error('commission')
                    <span class="error">{{ucwords($message)}}</span>
                    @enderror
                </div>
                <div class="form-group userSec d-none" id="tagIdSection">
                    <label class="form-label" for="tags">Tag ID</label>
                    <select multiple name="tags[]" id="tags">
                        <option disabled>Add Tags</option>
                    </select>
                    @error('tag_id')
                        <span class="error">{{ucwords($message)}}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="form-label" for="user-status">Status</label>
                    <select id="user-status" required name="status" class="form-control">
                        <option selected disabled>Select Status</option>
                        <option value="0">Inactive</option>
                        <option value="1">Active</option>
                    </select>
                    @error('status')
                        <span class="error">{{ucwords($message)}}</span>
                    @enderror
                </div>
                <div class="form-group d-none userSec" id="shopSelect">
                    <label>Shops</label>
                    <select id="shops" class="select2 form-control" name="shops[]" multiple required>
                        <optgroup label="Shops">
                            @foreach(getShops() as $shop)
                                <option value="{{$shop->id}}">{{ucwords(str_ireplace('-',' ',$shop->name))}}</option>
                            @endforeach
                        </optgroup>
                    </select>
                    @error('shops')
                    <span class="error">{{ucwords($message)}}</span>
                    @enderror
                </div>
                <button type="submit" id="sub_btn_user_save_form" class="btn btn-primary mr-1 data-submit">Submit</button>
                <button type="reset" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </form>
    </div>
</div>
