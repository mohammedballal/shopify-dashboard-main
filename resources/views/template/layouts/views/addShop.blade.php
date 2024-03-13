<div class="modal modal-slide-in new-shop-modal fade" id="modals-slide-in">
    <div class="modal-dialog">
        <form class="add-new-shop modal-content pt-0" id="addShop" method="POST" action="{{route('shop.store')}}" enctype="multipart/form-data">
            @csrf
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
            <div class="modal-header mb-1">
                <h5 class="modal-title" id="shopTitle">New Shop</h5>
            </div>
            <div class="modal-body flex-grow-1">
                <div class="form-group">
                    <label class="form-label" for="basic-icon-default-shopName">Shop Name</label>
                    <input type="text" class="form-control dt-full-name" id="shopName" autocomplete="off"  value="{{old('name')}}" required placeholder="abc-store" name="name" aria-label="Store Name" aria-describedby="basic-icon-default-fullname2" />
                    @error('name')
                        <span class="error">{{ucwords($message)}}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="form-label" for="basic-icon-default-apiKey">API KEY</label>
                    <input type="text" class="form-control dt-full-name" id="apiKey" autocomplete="off"  placeholder="{{generateRandomString()}}" value="{{old('api_key')}}" required name="api_key" aria-label="key" aria-describedby="basic-icon-default-fullname2" />
                    @error('api_key')
                        <span class="error">{{ucwords($message)}}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="form-label" for="basic-icon-default-api_pass">API PASS</label>
                    <input type="text" id="api_pass" class="form-control dt-email" autocomplete="off" required placeholder="{{'shppa_'.generateRandomString()}}" value="{{old('api_pass')}}" name="api_pass" />
                    @error('api_pass')
                        <span class="error">{{ucwords($message)}}</span>
                    @enderror
                    <small class="form-text text-muted">Secret Key</small>
                </div>
                <div class="form-group">
                    <div class="d-flex justify-content-between">
                        <label for="api_version">Api Version</label>
                    </div>
                    <div class="input-group input-group-merge form-password-toggle">
                        <input type="text" required class="form-control form-control-merge" id="api_version" name="api_version" tabindex="2" placeholder="2021-10" aria-describedby="api_version" />
                    </div>
                    @error('api_version')
                        <span class="error">{{ucwords($message)}}</span>
                    @enderror
                </div>
                <button type="submit" id="sub_btn_shop_save_form" class="btn btn-primary mr-1 data-submit">Submit</button>
                <button type="reset" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </form>
    </div>
</div>
