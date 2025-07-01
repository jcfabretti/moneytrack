<div class="modal fade text-left" id="editModalGrupo" tabindex="-1" role="dialog" aria-labelledby="EditModalGrupoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form method="post" enctype="multipart/form-data" action="{{ route('grupoeconomico.edit') }}">
                 @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="EditModalGrupoLabel"><strong>ALTERAR EMPRESA</strong></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="editGrupoEconomicoId" id="editGrupoEconomicoId" />
                    <div class="form-group">
                        <label for="editNomeGrupoEconomico">Nome Grupo Econômico:</label>
                        <input type="text" class="form-control" name="nome_grupo_economico" id="editNomeGrupoEconomico"
                                maxlength="30" oninput="capitalizeInput(this)"  required>
                    </div>
                    <div class="form-group">
                        <label for="editLocalidade">Localidade:</label>
                        <input type="text" class="form-control" name="editLocalidade" id="editLocalidade"
                               maxlength="30" oninput="capitalizeInput(this)" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-success">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>
</div>
