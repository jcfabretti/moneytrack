<div class="modal fade" id="deleteModalLancamento" data-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="deleteCategory" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <form name="deleteLancamento" method="POST" action="{{ route('lancamento.destroy') }}">
                {{-- Ensure the route is correct and matches your route definition for deletion --}}
                @method('delete')
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">EXCLUSÃO DE LANÇAMENTO</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Confirma Exclusão: <strong> <span id="lancamento_nrdocto"></span> - <span
                            id="lancamento_partida_nome"></span> </strong>
                    {{-- The {{ $lancamento->id }} here is likely for display purposes, but ensure it's correct contextually. --}}
                    <input type="hidden" id="delete_lacto_id" name="delete_lacto_id" value="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-white" id="cancelar-btn" data-dismiss="modal">Cancelar</button>
                    {{-- Corrected: This button will now submit the form --}}
                    <button type="submit" class="btn btn-danger" id="modal-confirm_delete">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>
