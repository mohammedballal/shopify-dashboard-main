<div class="modal modal-slide-in new-shop-modal fade" id="modals-slide-in">
    <div class="modal-dialog">
        <form class="add-new-shop modal-content pt-0" id="addShop" method="POST" action="{{route('costtariff.store')}}" enctype="multipart/form-data">
            @csrf
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
            <div class="modal-header mb-1">
                <h5 class="modal-title" id="shopTitle">New Cost & Tariff</h5>
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
                    <label class="form-label" for="frequency">Frequency</label>
                    <select class="form-control" name="frequency" id="frequency">
                        <option disabled selected>Select Frequency</option>
                        <option value="monthly">Monthly</option>
                        <option value="yearly">Yearly</option>
                        <option value="%_total_sales">% Total Sales</option>
                        <option value="%_sales_with_tag">% Sales with Tag</option>
                    </select>
                    @error('frequency')
                    <span class="error">{{ucwords($message)}}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="form-label" for="basic-icon-default-apiKey">Value</label>
                    <input type="text" class="form-control dt-full-name" id="value" autocomplete="off"  placeholder="Value" value="{{old('value')}}" required name="value" aria-label="key" aria-describedby="basic-icon-default-fullname2" />
                    @error('value')
                        <span class="error">{{ucwords($message)}}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="form-label" for="basic-icon-default-api_pass">Total</label>
                    <input type="text" id="total" class="form-control dt-email" autocomplete="off" required placeholder="Total" value="{{old('total')}}" name="total" />
                    @error('total')
                        <span class="error">{{ucwords($message)}}</span>
                    @enderror
                </div>
                <button type="submit" id="sub_btn_shop_save_form" class="btn btn-primary mr-1 data-submit">Submit</button>
                <button type="reset" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </form>
    </div>
</div>
