<div class="modal fade text-left" id="addModalGrupo" tabindex="-1" role="dialog" aria-labelledby="addModalGrupoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            {{-- REMOVED THE DUPLICATE 'action=""' HERE --}}
            <form method="POST" enctype="multipart/form-data" action="{{ route('grupoeconomico.store') }}">
                @csrf 

                <div class="modal-header">
                    <h5 class="modal-title" id="createModalGrupoLabel"><strong>Incluir Grupo Economico</strong></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        <label for="createGrupoNome">Nome Grupo Econômico:</label>
                        <input type="text" class="form-control" name="nome" id="createGrupoNome"
                               maxlength="30" oninput="capitalizeInput(this)" required>
                    </div>
                    <div class="form-group">
                        <label for="createLocalidade">Localidade:</label>
                        <input type="text" class="form-control" name="localidade" id="createLocalidade"
                               maxlength="30" oninput="capitalizeInput(this)" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-success">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>     