        <!-- EDIT Modal HTML -->
        <div id="editTiposModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="editTiposModalLabel">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form name="editTipoCategoria" method="POST" action="{{ url('/categoria/tipos/update') }}">
                        @method('post')
                        @csrf

                        <div class="modal-header">
                            <div class="col">
                                <h5 class="modal-title">TIPO CATEGORIA - Alterar</h5>
                            </div>
                        </div>

                        <!-- Nome da Empresa -->
                        <div class="modal-body">
                            <input type="hidden" name="edit_id" id="id" />
                            <div class="form-group">
                                <label>Nome do Tipo de Categoria:</label>
                                <input type="text" class="form-control" oninput="capitalizeInput()" name="edit_nome"
                                    id="edit_nome" value="" maxlength="25" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancelar">
                            <input type="submit" class="btn btn-success" value="Alterar">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script>
            //-- Altera texto para Primeira letra maiscula --//
            function capitalizeInput() {
                const input = document.getElementById("nome");
                input.value = input.value.toLowerCase().split(' ').map(s => s.charAt(0).toUpperCase() + s.substring(1)).join(
                    ' ');
            }

    </script>
