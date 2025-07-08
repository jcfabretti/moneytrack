   {{-- ############################################################################################# --}}
   <!-- EDIT Modal HTML -->
   <div class="modal fade" id="editModal" tabindex="-1" role="dialog">
       <div class="modal-dialog" role="document">
           <div class="modal-content">
               <div class="modal-header">
                   <h5 class="modal-title">ALTERAR Parceiro</h5>
                   <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                       <span>&times;</span>
                   </button>
               </div>
               <div class="modal-body">
                   <form name="formEditParceiro" id='editFormParceiro' method="POST"
                       action="{{ url('/parceiro/update', $parceiro->id) }}">
                       @method('POST')
                       @csrf

                       <!-- Id -->
                       <input type="hidden" class="form-control" name="id" id="id">

                       <!-- Nome -->
                       <div class="form-group">
                           <label>Nome da Empresa</label> <strong> <span id="M_Idmodal-category_name"></span>
                           </strong>
                           <input type="text" class="form-control" name="nome" id="nome" required autofocus>
                       </div>

                       <!--  Natureza Juridica -->
                       <div class="form-group">
                           <label for="entries">Natureza Juridica:</label><br>
                           <select class="form-select w-100" name="nat_jur" id="nat_jur">
                               <option value="P.Juridica" {{ $selectedValue == 'P.Juridica' ? 'selected' : '' }}>
                                   P.Juridica
                               </option>
                               <option value="P.Fisica" {{ $selectedValue == 'P.Fisica' ? 'selected' : '' }}>P.Fisica
                               </option>
                           </select>
                       </div>
                       <!-- Tipo de Cliente -->
                       <div class="form-group">
                           <label for="tipo_cliente">Tipo de Cliente:</label><br>
                           <select class="form-select w-100" name="tipo_cliente" id="tipo_cliente" required>
                               <option value="Banco" {{ $selectedValue == 'Banco' ? 'selected' : '' }}>Banco
                               </option>
                               <option value="Cliente" {{ $selectedValue == 'Cliente' ? 'selected' : '' }}>Cliente
                               </option>
                               <option value="Fornecedor" {{ $selectedValue == 'Fornecedor' ? 'selected' : '' }}>
                                   Fornecedor</option>
                           </select>
                       </div>

                       <!--Codigo Fiscal -->
                       <div class="form-group">
                           <label>CNPJ/CPF</label>
                           <input type="text" class="form-control" name="cod_fiscal" id="edit_cod_fiscal" required>
                       </div>
                       <!--Localidade -->
                       <div class="form-group">
                           <label>Localidade</label>
                           <input type="text" class="form-control" name="localidade" id="localidade" required>
                       </div>

                       <!--  STATUS ativo/inativo -->
                       <div>
                           <div class="form-group">
                               <label for="entries">Status:</label><br>
                               <select class="form-select w-100 mySelect" data-ranges='[[1-1,"yellow"], [0-0, "blue"]]'
                                   name="status" id="status">
                                   <option value="1" {{ $selectedValue == 'ativo' ? 'selected' : '' }}>
                                       Ativo
                                   </option>
                                   <option value="0" {{ $selectedValue == '0' ? 'selected' : '' }}>
                                       Desativado
                                   </option>
                               </select>
                           </div>

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