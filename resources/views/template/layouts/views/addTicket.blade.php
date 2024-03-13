<div class="modal fade" id="new-ticket-modal" tabindex="-1" aria-labelledby="new-ticket-modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-sm-5 mx-50 pb-5">
                <h1 class="text-center mb-1" id="addNewTicketTitle">Add New Ticket</h1>
                <p class="text-center">Add Ticket for support</p>

                <!-- form -->
                <form id="add-new-ticket-form" class="row gy-1 gx-2 mt-75" action="{{route('ticket.store')}}" method="post">
                    @csrf
                    <div class="col-12">
                        <label class="form-label" for="tTitle">Title</label>
                        <input type="text" name="title" value="{{old('title')}}" placeholder="Title" required class="form-control">
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="priority">Priority</label>
                        <select class="form-control" name="priority" required>
                            <option disabled>Set priority</option>
                            <option selected value="0">Low</option>
                            <option value="1">Medium</option>
                            <option value="2">High</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <input type="hidden" name="description" id="description">
                        <label class="form-label" for="description">Description</label>
                        <div id="content-container">
                            <div id="toolbar-container">
                            </div>
                            <div id="editor">
                            </div>
                        </div>
                    </div>

                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary me-1 mt-1">Submit</button>
                        <button type="reset" class="btn btn-outline-secondary mt-1" data-dismiss="modal" aria-label="Close">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
