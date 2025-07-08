<div class="container border">
    <div class="row align-items-start" style="height: 100%;">
        <!-- Left Column: Table -->
        <div class="col-md-6 mt-2 border listagem">
            <table id="ajaxList" class="table table-bordered">
                <thead>
                    <tr>
                        <!--  <th>Data</th> -->
                        <th>Doc</th>
                        <th>Histórico</th>
                        <th>Valor</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Rows will be dynamically added here -->
                </tbody>
            </table>
        </div>

        <!-- Right Column: Modal Form -->
        <div class="col-md-6  border formulario">
            <form id="ajaxForm" method="POST">
                @csrf

                <!-- Start of first column -->
                <div class="card-body p-0 mt-0">
                    <!-- ########## DATA LCTO ########## -->
                    <div class="input-group col-xs-4 mt-2">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Data:</span>
                        </div>
                        <input type="date" class="form-control col-sm-3" name="data_lcto" id="data_lcto"
                            maxlength="6" value="{{ session('app.dataLcto') }}" data-date-format="dd-mm-YYYY" required
                            autofocus>

                        <!-- ########## NUMERO DOCUMENTO ########## -->
                        <div class="input-group-prepend  ml-2">
                            <span class="input-group-text">Documento:</span>
                        </div>
                        <input type="text" class="form-control col-sm-2" name="numero_docto" id="numero_docto"
                            maxlength="6" onchange="jsCheckDocto(this)" value="" style="width: 50px;"
                            autocomplete="off" required>
                    </div>
                    <!-- ########## TIPO DE CONTA ########## -->
                    <div class="input-group mb-3 mt-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="inputGroupSelect01">Tipo de Conta</label>
                        </div>
                        <select class="custom-select form-control" name="tipo_conta=" placeholder="Selecione Empresa"
                            data-val-required="Selecione Empresa">

                            <option value="Banco">1-Banco</option>
                            <option value="Cliente">2-Cliente</option>
                            <option value="Fornecedor">3-Fornecedor</option>
                        </select>
                    </div>

                    <!-- ########## PARCEIRO ########## -->
                    <div class="input-group mt-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Nº da Conta</span>
                        </div>
                        <input type="numeric" class="form-control col-md-2" name="conta_partida" id="conta_partida"
                            onchange="jsGetParceiro(this)" aria-label="conta_partida" maxlength="4" value=""
                            required>
                        <input type="text" class="form-control" id="nomePartida" name="nomePartida" value=""
                            style="font-weight: bold;" readonly>
                    </div>

                    <!-- ########## GRUPO DE CONTASE ########## -->
                    <div class="input-group mt-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Categoria:</span>
                        </div>
                        <input type="text" class="form-control col-md-2" name="plano_contas_conta"
                            id="plano_contas_conta" onchange="jsGetConta(this)" maxlength="5" required>
                        <input type="text" class="form-control" name="nomeConta" id="nomeConta" maxlength="6"
                            readonly style="font-weight: bold;">

                        <!-- INPUT HIDDEN - passa codigo do plano de contas para pesquisar conta -->
                        <input type="text" hidden class="form-control" name="codPlanoConta" id="codPlanoConta"
                            value={{ session('app.empresaCodPlanoConta') }} id="codPlanoConta">
                    </div>

                    <!-- ########## CONTRA-PARTIDA ########## -->
                    <div class="input-group mt-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Contrapartida:</span>
                        </div>
                        <input type="text" class="form-control col-md-2" name="conta_contrapartida"
                            id="conta_contrapartida" onchange="jsGetContraPartida(this)" maxlength="4" required>

                        <input type="text" class="form-control " name="nomeContraPartida" id="nomeContraPartida"
                            style="font-weight: bold;" readonly>
                    </div>
                    <!-- End of first column -->

                        <!-- Start of second column -->
                    <!-- ########## HISTORICO / UNIDADE / QUANTIDAE ########## -->
                    <div class="input-group mt-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Histórico:</span>
                        </div>
                        <input type="text" aria-label="historico"
                            class="form-control form-control-alternative col-6" name="historico" id="historico"
                            style="text-transform:uppercase" required>

                        <div class="input-group col-xs-3 mt-3">
                            <input type="text" class="form-control form-control-alternative col-6 W-10"
                                id="unidade" name="unidade" placeholder="Unidade" value="UND">
                            <input type="numeric" class="form-control" nome="quantidade" id="quantidade"
                                placeholder="Quantidade" maxlength="8" value="0,00"
                                onkeypress="return(moeda(this,'.',',',event))">
                        </div>
                    </div>

                    <!-- ########## CODIGO CONTABIL ########## -->
                    <div class="input-group col-xs-3 mt-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Contábil:</span>
                        </div>
                        <input type="text" class="form-control col-sm-4" name="codigo_contabil"
                            id="codigo_contabil" maxlength="20" value="00000000000000000000" autocomplete="off">
                    </div>

                    <!-- ############### VALOR  ####################### -->
                    <div class="input-group mt-3 mb-3 w-55">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Valor:</span>
                        </div>
                        <select class="custom-select form-control" name="deb_cred=">
                            data-val-required="Selecione Empresa">
                            <option value="Banco">1-Deb</option>
                            <option value="Cliente">2-Cred</option>
                        </select>

                        <input type="text" class="form-control" maxlength="15" name="valor" id="valor"
                            autocomplete="off" aria-label="Valor" onkeypress="return(moeda(this,'.',',',event))"
                            style="width: 180px; direction: rtl;" required>
                    </div>
                </div>
                    <!-- End of first column -->

                <!-- ############### FOOTER  ####################### -->
                <div class="modal-footer border">
                    <x-adminlte-button class="btn-flat" type="submit" label="Gravar" theme="success"
                        icon="fas fa-lg fa-save" />
                    <a href="{{ url('/home/showlancamento') }}" class="btn btn-primary"><i
                            class="bi bi-arrow-return-left"></i>Listar Lçtos</a>
                </div>
            </form>
        </div>
    </div>

</div>