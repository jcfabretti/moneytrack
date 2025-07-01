 {{-- addCategoriaModal Modal (Removido <html> e <meta>) --}}
<div class="modal fade" id="addCategoriaModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header flex-column position-relative">
                <h5 class="modal-title">INCLUIR CATEGORIA</h5>
                <h6 class="modal-subtitle"></h6> {{-- Mantenha aqui para depuração inicial --}}
            </div>

            <div class="modal-body">
                <form name="createFormCategoria" id='createFormCategoria' method="POST"
                    action="{{ url('/categoria/store') }}">
                    @method('POST')
                    @csrf

                    <!-- Numero da Categoria -->
                    <div class="form-group">
                        <label for="numero_categoria">Cod Categoria</label>
                        <strong>
                            {{-- Renomeie este ID para algo como M_numero_categoria_span ou remova se não for necessário --}}
                            <span id="M_Idmodal-category_name_1"></span> 
                        </strong>

                        <!-- Numero da categoria somente para o usuario ver formatado -->
                        <!-- a função js_formatCategoria ao completar 5 digitos checa se a categoria do nivel superior-->
                        <!-- existe, mostra o nome ou mensagem de erro em #categoriaPai_legenda-->
                        <input type="text" class="form-control" name="numero_categoria" id="numero_categoria"
                            onkeypress="return(js_formatCategoria(this,'.','.',event))" maxlength="5"
                            autocomplete="off" required>
                    </div>

                    <!-- Numero da categoria sem formato que será enviado ao banco de dados-->
                    <input type="hidden" class="form-control" name="categoria_id" id="categoria_id">

                    <!-- Categoria PAI -->
                    <div class="form-group">
                        <label>Categoria Pai</label> <strong> 
                            {{-- Renomeie este ID para algo como M_categoria_pai_span ou remova se não for necessário --}}
                            <span id="M_Idmodal-category_name_2"></span> 
                        </strong>
                        <!-- Formated number to user view only -->
                        <input type="hidden" class="form-control" name="categoria_pai" id="categoria_pai">
                        <!-- Hidden number to save to database -->
                        <input type="text" class="form-control" name="categoriaPai_legenda"
                            id="categoriaPai_legenda" maxlength="7" readonly>
                    </div>

                    <!--  descrição da categoria -->
                    <div class="form-group">
                        <label>Nome da Categoria</label> <strong> 
                            {{-- Renomeie este ID para algo como M_nome_categoria_span ou remova se não for necessário --}}
                            <span id="M_Idmodal-category_name_3"></span> 
                        </strong>
                        <input type="text" class="form-control" name="nome" id="nome" autocomplete="off"
                            style="text-transform: uppercase;" required>
                    </div>

                    <!--Nivel da conta -->
                    <div class="form-group">
                        <input type="hidden" class="form-control" name="nivel" id="nivel" required>

                        <!-- Hidden codigo do TIPO de categoria -->
                        <input type="hidden" class="form-control" name="tipo_categoria" id="tipo_categoria">
                    </div>

            </div>
            <div class="modal-footer">
                <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancelar">
                <input type="submit" class="btn btn-success" value="Salvar">
            </div>
            </form>
        </div>
    </div>
</div>