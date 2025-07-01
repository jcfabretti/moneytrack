<!-- Delete Modal -->
<div class="modal fade" id="deleteCategory" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="deleteCategory">
    <div class="modal-dialog" style="width: 40%; max-width: 400px;" role="document">
        <div class="modal-content">
            <form name="deleteCategoria" method="POST" action="{{ url('/categoria/destroy') }}">
                @method('post')
                @csrf
                <div class="modal-header flex-column position-relative">
                    <h5 class="modal-title">EXCLUSÃO DE CATEGORIA</h5>
                </div>
                <div class="modal-body">
                    Confirma Exclusão: <strong><span id="modal-category_name"></span></strong>
                    <input type="hidden" id="category" name="category_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-white" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="modal-confirm_delete">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- end of Delete Modal -->
