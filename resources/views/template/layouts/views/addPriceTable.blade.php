<div class="modal modal-slide-in new-shop-modal fade" id="modals-slide-in">
    <div class="modal-dialog">
        <form class="add-new-shop modal-content pt-0" id="addShop" method="POST" action="{{route('price.store')}}" enctype="multipart/form-data">
            @csrf
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
            <div class="modal-header mb-1">
                <h5 class="modal-title" id="shopTitle">New Price Table</h5>
            </div>
            <div class="modal-body flex-grow-1">
                <div class="form-group">
                    <label class="form-label" for="basic-icon-default-shopName">Product Name<small>(Max 255 Characters)</small></label>
                    <input type="text" maxlength="255" class="form-control dt-full-name" id="product_name" autocomplete="off"  value="{{old('product_name')}}" required placeholder="Name" name="product_name" aria-label="Store Name" aria-describedby="basic-icon-default-fullname2" />
                    @error('product_name')
                        <span class="error">{{ucwords($message)}}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="form-label" for="basic-icon-default-shopName">Cost USD</label>
                    <input type="number" readonly step="0.01" class="form-control dt-full-name" id="cost_usd" autocomplete="off"  value="{{old('cost_usd')}}" required placeholder="Name" name="cost_usd" aria-label="Store Name" aria-describedby="basic-icon-default-fullname2" />
                    @error('cost_usd')
                    <span class="error">{{ucwords($message)}}</span>
                    @enderror
                    <p>Exchange Rate: <br>{{ $string }}</p>
                    <input type="hidden" id="current_rate" value="{{ $rate }}">
                </div>
                <div class="form-group">
                    <label class="form-label" for="basic-icon-default-shopName">Cost BRL</label>
                    <input type="number" step="0.01" class="form-control dt-full-name" id="cost_brl" autocomplete="off"  value="{{old('cost_brl')}}" required placeholder="Name" name="cost_brl" aria-label="Store Name" aria-describedby="basic-icon-default-fullname2" />
                    @error('cost_brl')
                    <span class="error">{{ucwords($message)}}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="form-label" for="basic-icon-default-shopName">Mark UP</label>
                    <input type="number" step="0.01" class="form-control dt-full-name" id="mark_up" autocomplete="off"  value="{{old('mark_up')}}" required placeholder="Name" name="mark_up" aria-label="Store Name" aria-describedby="basic-icon-default-fullname2" />
                    @error('mark_up')
                    <span class="error">{{ucwords($message)}}</span>
                    @enderror
                </div>

                <button type="submit" id="sub_btn_shop_save_form" class="btn btn-primary mr-1 data-submit">Submit</button>
                <button type="reset" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </form>
    </div>
</div>
