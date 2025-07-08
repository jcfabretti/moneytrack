        <!-- Create Modal HTML -->
        <div class="modal fade" id="addParceiroModal" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <h4 class="modal-title">Incluir Parceiro</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <div class="modal-body">
                        <form name="createEmpresa" method="POST" enctype="multipart/form-data"
                            action="{{ url('/parceiros') }}">
                            @method('post')
                            @csrf
                            <!-- Nome -->
                            <div class="form-group">
                                <label>Nome da Empresa</label> <strong> <span id="M_Idmodal-category_name"></span>
                                </strong>
                                <input type="text" class="form-control" name="nome" id="nome" oninput="capitalizeInput(this)" required autofocus>
                                @error('nome')
                                <p class="error-message">{{ $message }}</p>
                                @enderror
                            </div>

                            <!--  Natureza Juridica -->
                            <div class="form-group">
                                <label for="nat_jur">Natureza Juridica:</label><br>
                                <select class="form-select w-100" name="nat_jur" id="nat_jur" required>
                                    <option value="P.Juridica" {{ $selectedValue == 'P.Juridica' ? 'selected' : '' }}>
                                        P.Juridica
                                    </option>
                                    <option value="P.Fisica" {{ $selectedValue == 'P.Fisica' ? 'selected' : '' }}>
                                        P.Fisica
                                    </option>
                                </select>
                            </div>
                            <!-- Tipo de Cliente -->
                            <div class="form-group">
                                <label>Tipo de Cliente:</label><br>
                                <select class="form-select w-100" name="tipo_cliente" id="tipo_cliente" required>
                                    <option value="Cliente">Cliente</option>
                                    <option value="Banco">Banco</option>
                                    <option value="Fornecedor">Fornecedor</option>
                                </select>
                            </div>

                            <!--Codigo Fiscal -->
                            <div class="form-group">
                                <label>CNPJ/CPF</label>
                                <input type="text" class="form-control" name="cod_fiscal" id="cod_fiscal" required>
                            </div>
                            <!--Localidade -->
                            <div class="form-group">
                                <label>Localidade</label>
                                <input type="text" class="form-control" name="localidade" id="localidade" required>
                            </div>

                            <!--  STATUS ativo/inativo -->
                            <div>
                                <input type="hidden" value="1" name="status" id="status">
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
        </div>