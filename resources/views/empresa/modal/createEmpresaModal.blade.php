        {{-- ############################################################################################# --}}
        <!-- Create Modal HTML -->
        <div id="addEmpresaModal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form name="createEmpresa" method="POST" action="{{ url('/empresa/store') }}">
                        @method('post')
                        @csrf

                        <div class="modal-header">
                            <div class="col-md-6">
                            <h4 class="modal-title">Incluir Empresa</h4>
                            </div>
                            <div class="col-md-6">
                                <h1 id="message"></h1>
                            </div>                            
                        </div>
                            
                        <!-- Nome da Empresa -->                        
                        <div class="modal-body">
                            <input type="hidden" name="id" id="id" />
                            <div class="form-group">
                                <label>Nome</label>
                                <input type="text" class="form-control" name="nome" id="nome" value="" required>
                            </div>

                            <!-- Grupo Economico -->                            
                            <div class="form-group">
                                <label>Grupo Economico:</label>
                                <select class="form-control valid" name="grupo_economico_id" required data-val="true"
                                    data-val-required="Selecione Grupo Economico" id="tipos_planocontas_idEgrupoEcon" name="grupoEcon">
                                    @foreach ($grupoEcons as $grupoEcon)
                                    <option value="{{ $grupoEcon->id }}">{{ $grupoEcon->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- Codigo Fiscal -->                            
                            <div class="form-group">
                                <label>CNPJ</label>
                                <input type="text" class="form-control" name="cod_fiscal" id="cod_fiscal" max="14" required>
                            </div>
                            
                            <!-- Localidade -->
                            <div class="form-group">
                                <label>Localidade</label>
                                <input type="text" class="form-control" name="localidade" id="localidade" required>
                            </div>

                            <!-- Tipos de Plano de Categorias -->
                            <div class="form-group">
                                <label>Tipo de Plano de Categorias:</label>
                                <select class="form-control valid" name="tipos_planocontas_id" required data-val="true"
                                    data-val-required="Selecione Plano de Contas" id="tipos_planocontas_id"
                                    name="tipos_planocontas_id">
                                    @foreach ($tiposPlanos as $tiposPlano)
                                    <option value="{{ $tiposPlano->id }}">{{ $tiposPlano->nome }}</option>
                                    @endforeach
                                </select>
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