<div class="modal fade" id="crudModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="crudModalTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="crudForm" class="ajax-form">
                @csrf
                <div class="modal-body" id="crudModalBody"></div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="crudModalSubmit"></button>
                </div>
            </form>

        </div>
    </div>
</div>
