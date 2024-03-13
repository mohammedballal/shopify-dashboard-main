<div class="modal modal-slide-in fade" id="send-invoice-sidebar" aria-hidden="true">
    <div class="modal-dialog sidebar-lg">
        <div class="modal-content p-0">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
            <div class="modal-header mb-1">
                <h5 class="modal-title">
                    <span class="align-middle">Send Invoice</span>
                </h5>
            </div>
            <div class="modal-body flex-grow-1">
                <form action="{{route('order.invoice.send',$order->id)}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="invoice-to" class="form-label">To</label>
                        <select multiple name="to[]" id="tags" required>
                            <option disabled>Send to</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="invoice-subject" class="form-label">Subject</label>
                        <input type="text" class="form-control" name="subject" id="invoice-subject" value="{{old('subject')}}" placeholder="Invoice regarding goods" />
                    </div>
                    <div class="form-group">
                        <label for="invoice-message" class="form-label">Message</label>
                        <textarea class="form-control" name="message" id="invoice-message" cols="3" rows="11" placeholder="Dear Queen Consolidated,

Thank you for your business, always a pleasure to work with you!

We have generated a new invoice in the amount of $95.59

We would appreciate payment of this invoice by 05/11/2019"></textarea>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="to_customer" id="to_customer">
                        <label class="custom-control-label" for="to_customer"> Send to Customer </label>
                    </div>
                    <div class="form-group mt-2">
                        <span class="badge badge-light-primary">
                            <i data-feather="link" class="mr-25 icon"></i>
                            <span class="align-middle">Invoice Attached</span>
                        </span>
                    </div>
                    <div class="form-group d-flex flex-wrap mt-2">
                        <button type="submit" class="btn btn-primary mr-1">Send</button>
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
