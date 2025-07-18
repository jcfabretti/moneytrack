{{-- updateModalLancamento Modal --}}
<div class="modal fade" id="updateModalLancamento" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" style="max-width: 700px;" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #92AFC2; color: white;">
                <h5 class="modal-title" id="editModalLabel">ALTERAR Lançamento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card shadow-sm my-3" style="background-color: #92AFC2; ">
                    <div class="card-body">
                        <form id="updateLancamentoForm" method="POST" action="{{ route('lancamento.update') }}">
                            @csrf
                            @method('PUT') {{-- Adicionado para requisições PUT/PATCH --}}
                            <div class="form-row">
                                <input type="hidden" name="lcto_id_update" id="lcto_id_update">
                                {{-- Removidos os values do Blade para empresa_id e grupo_economico_id, serão preenchidos via JS --}}
                                <input type="hidden" name="empresa_id_update" id="empresa_id_update">
                                <input type="hidden" name="grupo_economico_id_update" id="grupo_economico_id_update">
                                <input type="hidden" class="form-control" name="origem_update" id="origem_update">

                                <div class="col-md-6 form-group">
                                    <label for="data_lcto_update">Data:</label>
                                    <input type="date" class="form-control" name="data_lcto_update"
                                        id="data_lcto_update" maxlength="10" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="tipo_docto_update">Tipo/Nº Documento:</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control col-md-4" name="tipo_docto_update"
                                            id="tipo_docto_update" maxlength="10" style="text-transform: uppercase;">
                                        <input type="text" class="form-control col-md-4" name="numero_docto_update"
                                            id="numero_docto_update" maxlength="6" onchange="jsCheckDocto(this)"
                                            autocomplete="off" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-4 form-group"> <label for="tipo_conta_update">Tipo de Conta</label>
                                    <select class="form-control" name="tipo_conta_update" id="tipo_conta_update"
                                        onchange="jsOcultaCategoriaContraPartida_update()" required>
                                        <option value="banco">1-Banco</option>
                                        <option value="fornecedor">2-Fornecedor</option>
                                        <option value="cliente">3-Cliente</option>
                                    </select>
                                </div>

                                <div class="col-md-8 form-group"> <label for="conta_partida_update">Nº da Conta</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control col-md-3" name="conta_partida_update"
                                            id="conta_partida_update" onchange="jsGetParceiro(this)" maxlength="4"
                                            required autocomplete="off">
                                        <input type="text" class="form-control" id="nomePartida"
                                            name="nomePartida"
                                            style="font-size: 0.9em; font-weight: bold;" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row grupo categoria-contrapartida">
                                {{-- Campo Categoria --}}
                                <div class="col-md-12 form-group">
                                    <label for="categorias_id_update">Categoria:</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control col-md-3" name="categorias_id"
                                            id="categorias_id" onchange="jsGetCategoria(this.value, $('#codPlanoCategoria_update').val())"
                                            maxlength="5" required>
                                        <input type="text" class="form-control" name="nomeConta_update" id="nomeConta"
                                            maxlength="6" readonly style="font-size: 0.9em; font-weight: bold;">
                                    </div>
                                    {{-- ID do campo hidden para codPlanoCategoria ajustado --}}
                                    <input type="hidden" name="codPlanoCategoria_update" id="codPlanoCategoria_update">
                                </div>

                                {{-- Campo Contrapartida --}}
                                <div class="col-md-12 form-group">
                                    <label for="conta_contrapartida_update">Contrapartida:</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control col-md-3"
                                            name="conta_contrapartida_update" id="conta_contrapartida_update"
                                            onchange="jsGetContraPartida(this)" maxlength="4" required>
                                        <input type="text" class="form-control" name="nomeContraPartida"
                                            id="nomeContraPartida" style="font-size: 0.9em; font-weight: bold;"
                                            readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-8 form-group">
                                    <label for="historico_update">Histórico:</label>
                                    <input type="text" class="form-control" name="historico_update"
                                        id="historico_update" style="text-transform:uppercase" maxlength="40"
                                        required>
                                </div>
                                <div class="col-md-2 form-group">
                                    <label>Unidade:</label>
                                    <input type="text" class="form-control" id="unidade_update"
                                        name="unidade_update" maxlength="10" style="text-transform: uppercase;">
                                </div>
                                <div class="col-md-2 form-group">
                                    <label>Qtde:</label>
                                    <input type="numeric" class="form-control" name="quantidade_update"
                                        id="quantidade_update" placeholder="0" maxlength="8">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-6 form-group">
                                    <label for="valor_update">Valor:</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control col-md-8" maxlength="15"
                                            name="valor_update" id="valor_update" autocomplete="off"
                                            onkeydown="handleDeleteClear(event)"
                                            oninput="formatValueOnInput(this)"
                                            onblur="formatAndValidateValor(this)"
                                            style="direction: rtl; font-weight: bold;" required>

                                        <span class="tooltip-container">
                                            <i class="fa fa-info-circle" style="font-size:24px;color:#138496"aria-hidden="true"></i>
                                            <span class="tooltip-text">1.Débito digite - 099 = 0,99-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br> 2.Credito digite sem sinal 999 = 9,99&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br> 3.Apagar valor DEL </span>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="centro_custo_update">Centro de Custo:</label>
                                    <input type="text" class="form-control" name="centro_custo_update"
                                        id="centro_custo_update" maxlength="20" autocomplete="off">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-6 form-group">
                                    <label for="vencimento_update">Vencimento:</label>
                                    <input type="date" class="form-control" name="vencimento_update"
                                        id="vencimento_update" autocomplete="off">
                                </div>
                                <div class="col-md-6 form-group"
                                    style="display: flex; justify-content: flex-end; align-items: center; gap: 10px;">
                                    <button type="submit" class="btn btn-success">Atualizar</button>
                                    <button type="button" class="btn btn-secondary"
                                        data-dismiss="modal">Cancelar</button>
                                </div>
                                <div class="col-9 message align-items-start">
                                    {{-- ID do elemento de mensagem ajustado para ser único no modal de update --}}
                                    <h4 id="message_update" style="background-color: rgb(224, 233, 121)"></h4>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Lidar com o envio do formulário de atualização via AJAX
    $('#updateAjaxForm').on('submit', function(e) {
        e.preventDefault();

        var lancamentoId = $('#lancamento_id_update').val();
        var formData = $(this).serialize(); // Pega todos os dados do formulário

        $.ajax({
            url: `/api/lancamentos/${lancamentoId}`, // Ajuste esta URL para sua rota de API
            method: 'PUT', // Método HTTP PUT
            data: formData,
            success: function(response) {
                $('#message_update').css('background-color', 'lightgreen').text(
                    'Lançamento atualizado com sucesso!');
                // Opcional: recarregar a lista de lançamentos ou fechar o modal
                setTimeout(function() {
                    $('#loadEditModal').modal('hide');
                    // Aqui você pode recarregar a lista se ela estiver na mesma página
                    // Ex: window.location.reload(); ou uma função que recarrega a tabela AJAX
                }, 1500);
            },
            error: function(xhr) {
                console.error("Erro ao atualizar lançamento:", xhr.responseText);
                $('#message_update').css('background-color', 'red').text('Erro ao atualizar: ' + (
                    xhr.responseJSON.message || 'Verifique os dados.'));
            }
        });
    });

</script>
