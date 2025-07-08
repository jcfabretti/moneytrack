{{-- ############################################################################################# --}}
    <!-- Delete Modal HTML -->
    <div class="modal fade" id="deleteCategoriaTipos" data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="deleteCategorTipos">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <form name="deleteCategoriaTipos" method="POST"
                    action="{{ route('categoria.tipos.destroy', $tipo->id) }}">
                    @method('POST')
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">EXCLUSÃO TIPO DE CATEGORIA</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Confirma Exclusão:
                        <span>
                            <input type = "text" id="nome" name="nome" readonly
                                style="border:none;font-weight:bold;">
                        </span>
                        <input type = "hidden" id="id" name="id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn bg-white" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger" id="modal-confirm_delete">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
