    {{-- ############################################################################################# --}}
    <!-- Delete Modal HTML -->
    <div class="modal fade" id="deleteCategory" data-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="deleteCategory" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <form name="deleteEmpresa" method="POST" action="{{ url('/parceiro/destroy') }}">
                @method('post')
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">EXCLUSÃO DE EMPRESA</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Confirma Exclusão: <strong> <span id="modal-category_name"></span> </strong>
                    <input type="hidden" id="category" name="category_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-white" data-dismiss="modal">Cancelar</button>
                    <button type="button" a href="{{ route('parceiro.destroy', $parceiro->id) }}"
                        class="btn btn-danger" id="modal-confirm_delete">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>