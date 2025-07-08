       {{-- ############################################################################################# --}}
       <!-- Edit Modal HTML -->
       <div id="editEmpresaModal" class="modal fade">
           <div class="modal-dialog">
               <div class="modal-content">

                   <form name="formEdit" id='editFormID' method="Post"
                       action="{{ route('empresa.update', $empresa->id) }}">
                       @csrf
                       @method('put')

                       <div class="modal-header">
                           <h4 class="modal-title">Alterar Empresa</h4>
                           <button type="button" class="close" data-dismiss="modal">&times;</button>
                       </div>

                       <div class="modal-body">
                           <input type="hidden" id="id" name="id"/>
                           <div class="modal-body">
                               <div class="form-group">
                                   <label>Nome da Empresa</label> <strong> <span id="M_Idmodal-category_name"></span>
                                   </strong>
                                   <input type="text" class="form-control" name="nome" id="nome" required>
                               </div>
                               <div class="form-group">
                                   <label>Grupo Economico:</label>
                                   <select class="form-control valid" name="grupoEcon" required data-val="true"
                                       data-val-required="Selecione Grupo Economico" id="grupoEcon">
                                       <option selected="selected">Selecione</option>
                                       @foreach ($grupoEcons as $grupoEcon)
                                           <option value="{{ $grupoEcon->id }}">{{ $grupoEcon->nome }}</option>
                                       @endforeach
                                   </select>
                               </div>
                               <div class="form-group">
                                   <label>CNPJ/CPF</label>
                                   <input type="text" class="form-control" name="cod_fiscal" id="cod_fiscal"
                                       required>
                               </div>
                               <div class="form-group">
                                   <label>Localidade</label>
                                   <input type="text" class="form-control" name="localidade" id="localidade"
                                       required>
                               </div>
                           </div>
                       </div>
                       <div class="modal-footer">
                           <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancelar">
                           <input type="submit" class="btn btn-info" value="Alterar">
                       </div>
                   </form>
               </div>
           </div>
       </div>
