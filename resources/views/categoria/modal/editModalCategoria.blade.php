   <div class="modal fade" id="editCategoria" tabindex="-1" role="dialog">
       <div class="modal-dialog" role="document">
           <div class="modal-content">
               <div class="modal-header">
                   <h5 class="modal-title">ALTERAR CATEGORIA</h5>
                   <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                       <span>&times;</span>
                   </button>
               </div>
               <div class="modal-body">
                   <form name="editFormCategoria" id='editFormCategoria' method="POST"
                       action="{{ url('/categoria/update') }}">
                       @method('POST')
                       @csrf

                       <!-- Numero da Categoria -->
                       <div class="form-group">
                           <label for="categoria_id">Cod Categoria</label>
                           <strong>
                               <span id="M_Idmodal-category_name"></span>
                           </strong>

                           <input type="text" class="form-control" name="categoria_id" id="categoria_id"
                               maxlength="5" readonly>


                       </div>

                       <!-- Categoria PAI -->
                       <div class="form-group">
                           <label>Categoria Pai</label> <strong> <span id="M_Idmodal-category_name"></span>
                           </strong>
                           <!-- Formated number to user view only -->
                           <input type="hidden" class="form-control" name="categoria_pai" id="categoria_pai">
                           <!-- Hidden number to save to database -->
                           <input type="text" class="form-control" name="categoriaPai_legenda"
                               id="categoriaPai_legenda" maxlength="7" readonly>
                       </div>

                       <!--  descrição da categoria -->
                       <div class="form-group">
                           <label>Nome da Categoria</label> <strong> <span id="M_Idmodal-category_name"></span>
                           </strong>
                           <input type="text" class="form-control" name="categoria_nome" id="nome"
                               autocomplete="off" style="text-transform: uppercase;" required>
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
                   <input type="submit" class="btn btn-success" value="Salvar Alterações">
               </div>
               </form>
           </div>
       </div>
   </div>
