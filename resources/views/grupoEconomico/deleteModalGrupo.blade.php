<div class="modal fade" id="deleteGrupoEconomico" data-backdrop="static" tabindex="-1" role="dialog" 
    aria-labelledby="deleteGrupoEconomico" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <form name="deleteGrupoEconomico" method="Post" action="{{ url('/grupoeconomico/destroy') }}">
                @method('Post')
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">EXCLUSÃO DE GRUPO ECONOMICO</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Confirma Exclusão: <strong> <span id="deleteGrupo_nome_display" name="deleteGrupo_nome_display"></span> </strong>
                    <input type="hidden" id="deleteGrupo_id" name="deleteGrupo_id">
                    <input type="hidden" id="deleteGrupo_nome" name="deleteGrupo_nome">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger" id="button-confirm_delete-grupo">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>