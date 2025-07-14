<!-- SEU HTML DO MODAL DE EXCLUSÃO -->
<div class="modal fade" id="deleteModalLancamento" data-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="deleteCategory" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <form name="deleteLancamento" method="POST" action="{{ route('lancamento.destroy') }}">
                @method('delete')
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">EXCLUSÃO DE LANÇAMENTO</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Confirma Exclusão: <br><strong>Lançamento:<span id="lcto_nrDocto_delete_display"></span>&nbsp;&nbsp;Valor:<span
                            id="lcto_valor_delete_display"></span> </strong>
                    {{-- O ID do input hidden foi corrigido para 'lcto_id_delete' --}}
                    <input type="hidden" id="lctoId_delete" name="lctoId_delete" value="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-white" id="cancelar-btn" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger" id="modal-confirm_delete">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>
