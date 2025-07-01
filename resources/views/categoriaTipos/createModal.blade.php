        {{-- ############################################################################################# --}}
        <!-- Create Modal HTML -->
        <div id="addTipoCategoriaModal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form name="createTipoCategoria" method="POST" action="{{ url('/categoria/tipos/store') }}">
                        @method('post')
                        @csrf

                        <div class="modal-header">
                            <div class="col-md-6">
                                <h5 class="modal-title">TIPO CATEGORIA -Incluir</h5>
                            </div>
                            <div class="col-md-6">
                                <h1 id="message"></h1>
                            </div>
                        </div>

                        <!-- Nome da Empresa -->
                        <div class="modal-body">
                            <input type="hidden" name="id" id="id" />
                            <div class="form-group">
                                <label>Nome do Tipo de Categoria:</label>
                                <input type="text" class="form-control" oninput="capitalizeInput()" name="create_nome"
                                    id="create_nome" value="" maxlength="25" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancelar">
                            <input type="submit" class="btn btn-success" value="Gravar">
                        </div>
                    </form>
                </div>
            </div>
        </div>