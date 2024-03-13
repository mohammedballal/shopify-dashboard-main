<div class="modal modal-slide-in new-shop-modal fade" id="modals-slide-in">
    <div class="modal-dialog">
        <form class="add-new-shop modal-content pt-0" id="addShop" method="POST" action="{{route('category.store')}}" enctype="multipart/form-data">
            @csrf
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
            <div class="modal-header mb-1">
                <h5 class="modal-title" id="shopTitle">New Category</h5>
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
                    <label class="form-label" for="basic-icon-default-shopName">Description</label>
                    <input type="text" class="form-control dt-full-name" id="description" autocomplete="off"  value="{{old('description')}}" required placeholder="Description" name="description" aria-label="Store Name" aria-describedby="basic-icon-default-fullname2" />
                    @error('description')
                    <span class="error">{{ucwords($message)}}</span>
                    @enderror
                </div>
                <div class="form-group d-none">
                    <label class="form-label" for="parent_id">Category</label>
                    <select class="form-control" name="parent_id" id="parent_id">
                        <option disabled selected>Select Category</option>
                        @foreach(\App\Models\Category::all() as $cat)
                            <option value="{{ $cat->id }}">{{ ucwords($cat->name) }}</option>
                        @endforeach
                    </select>
                    @error('parent_id')
                    <span class="error">{{ucwords($message)}}</span>
                    @enderror
                </div>
                <button type="submit" id="sub_btn_shop_save_form" class="btn btn-primary mr-1 data-submit">Submit</button>
                <button type="reset" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </form>
    </div>
</div>
