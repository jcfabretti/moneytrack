@extends('adminlte::page')

@section('title', 'Lançamentos')

@section('content_header')

    <head>
        <script src="{{ asset('js/lancamento.js') }}"></script>
        <script src="{{ asset('js/script.js') }}"></script>
        <link rel="stylesheet" type="text/css" href="{{ asset('css/styleForm.css') }}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

        <style>
            .form-control:focus,
            .form-select:focus {
                border-color: #007bff;
                /* Cor da borda azul do Bootstrap */
                box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
                /* Efeito de sombra do Bootstrap */
            }

            .grid-container-externo {
                display: grid;

                border: 5px solid red;
            }

            .grid-container-interno {
                display: grid;
                border: 5px solid blue;
            }

            /* Estilos existentes para o container do tooltip */
            .tooltip-container {
                position: relative;
                display: inline-block;
                cursor: help;
                /* Adicionado padding ao redor do ícone */
                padding: 10px;
                /* Ajuste conforme necessário */
                border-radius: 50%;
                /* Opcional: para criar um efeito circular no fundo do padding */
            }
        </style>
    </head>
@stop

@section('content')
    <?php
    $codigoEmpresa = session('app.empresaId');
    $codigoGrupoEconomico = session('app.grupoEmpresarial');
    $empresaCodigoPlanoCategoria = session('app.empresaCodPlanoConta');
    $ultimaDataLcto = session('app.ultimaDataLcto');
    ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">

                <div class="card shadow-sm my-3" style="background-color: #92AFC2; max-width: 100%;">
                    <div class="card-body form-compact-body">
                        <form id="ajaxForm" method="POST">
                            @csrf
                            {{-- 
                            <div class="input-group">
                                <input type="text" class="form-control" id="empresa_nome"
                                    placeholder="Selecione a Empresa" value="{{ session('app.empresaNome') }}" readonly>
                                <span class="input-group-btn">
                                    <button class="btn btn-secondary" type="button" onclick="jsOpenSelectEmpresa()">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
--}}
                            <div class="input-group">

                                <select class="form-control" id="empresa_nome" name="empresa_nome" onchange="selecionaEmpresa(this.value)">
                                    <option value="" {{ session('app.empresa_id') == null ? 'selected' : '' }}>Todas as
                                        Empresas</option>
                                    @foreach ($EMPRESAS as $empresa)
                                        <option value="{{ $empresa->id }}"
                                            {{ session('app.empresa_id') == $empresa->id ? 'selected' : '' }}>
                                            {{ $empresa->id }}-{{ $empresa->nome }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-row">

                                <input type="hidden" name="empresa_id" id="empresa_id"
                                    value="{{ session('app.empresaId') }}">

                                <input type="hidden" name="grupo_economico_id" id="grupo_economico_id"
                                    value="{{ session('app.grupoEmpresarial') }}">

                                <input type="hidden" class="form-control" name="origem" id="origem" value="Manual">

                                <div class="col-md-6 form-group">
                                    <label for="data_lcto">Data:</label>
                                    <input type="date" class="form-control" name="data_lcto" id="data_lcto"
                                        maxlength="6" value="{{ $ultimaDataLcto }}" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="tipo_docto">Tipo/Nº Documento:</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control col-md-4" name="tipo_docto"
                                            id="tipo_docto" maxlength="6" style="text-transform: uppercase;">
                                        <input type="text" class="form-control col-md-4" name="numero_docto"
                                            id="numero_docto" maxlength="6" onchange="jsCheckDocto(this)" value=""
                                            autocomplete="off" required>
                                    </div>
                                </div>

                            </div>
                            <div class="form-row">
                                <div class="col-md-6 form-group">
                                    <label for="tipo_conta">Tipo de Conta</label>
                                    <select class="form-control" name="tipo_conta" id="tipo_conta"
                                        onchange="jsOcultaCategoriaContraPartida(this)" required>
                                        <option value="banco">1-Banco</option>
                                        <option value="fornecedor">2-Fornecedor</option>
                                        <option value="cliente">3-Cliente</option>
                                    </select>
                                </div>

                                <div class="col-md-6 form-group">
                                    <label for="conta_partida">Nº da Conta</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control col-md-4" name="conta_partida"
                                            id="conta_partida" onchange="jsGetParceiro(this)" maxlength="4"
                                            value="" required>
                                        <input type="text" class="form-control" id="nomePartida" name="nomePartida"
                                            value="" style="font-size: 0.9em; font-weight: bold;" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row grupo categoria-contrapartida">
                                {{-- Campo Categoria --}}
                                <div class="col-md-12 form-group"> {{-- Ocupa a largura total da linha --}}
                                    <label for="categorias_id">Categoria:</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="categorias_id" id="categorias_id"
                                            onchange="jsGetCategoria(this.value,$('#codPlanoCategoria').val())" maxlength="5" required>
                                        <input type="text" class="form-control" name="nomeConta" id="nomeConta"
                                            maxlength="6" readonly style="font-size: 0.9em; font-weight: bold;">
                                    </div>
                                    <input type="hidden" name="codPlanoCategoria" id="codPlanoCategoria"
                                        value="{{ $empresaCodigoPlanoCategoria }}">
                                </div>

                                {{-- Campo Contrapartida --}}
                                <div class="col-md-12 form-group"> {{-- Ocupa a largura total da linha, forçando nova linha --}}
                                    <label for="conta_contrapartida">Contrapartida:</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="conta_contrapartida"
                                            id="conta_contrapartida" onchange="jsGetContraPartida(this)"
                                            maxlength="4" required>
                                        <input type="text" class="form-control" name="nomeContraPartida"
                                            id="nomeContraPartida" style="font-size: 0.9em; font-weight: bold;" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-8 form-group">
                                    <label for="historico">Histórico:</label>
                                    <input type="text" class="form-control" name="historico" id="historico"
                                        style="text-transform:uppercase" maxlength="40" required>
                                </div>
                                <div class="col-md-2 form-group">
                                    <label>Unidade:</label>
                                    <input type="text" class="form-control" id="unidade" name="unidade"
                                        maxlength="10" style="text-transform: uppercase;">
                                </div>
                                <div class="col-md-2 form-group">
                                    <label>Qtde:</label>
                                    <input type="numeric" class="form-control" name="quantidade" id="quantidade"
                                        placeholder="0" maxlength="8">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-6 form-group">
                                    <label for="valor">Valor:</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control col-md-8" maxlength="15"
                                            name="valor" id="valor" autocomplete="off"
                                            onkeydown="handleDeleteClear(event)" oninput="formatMoeda(this)"
                                            onblur="formatAndValidateValor(this)" style="direction: rtl;"
                                            style="font-weight: bold;" required>

                                        <span class="tooltip-container">
                                            <i class="fa fa-info-circle" style="font-size:24px;color:#138496"
                                                aria-hidden="true"></i>
                                            <span class="tooltip-text">1.Débito (em vermelho): digite -099 =
                                                0,99-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br> 2.Credito:
                                                digite sem sinal 999 =
                                                9,99&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br> 3.Apagar
                                                valor: tecle DEL </span>
                                        </span>

                                    </div>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="centro_custo">Centro de Custo:</label>
                                    <input type="text" class="form-control" name="centro_custo" id="centro_custo"
                                        maxlength="20" autocomplete="off">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-6 form-group">
                                    <label for="vencimento">Vencimento:</label>
                                    <input type="date" class="form-control" name="vencimento" id="vencimento"
                                        autocomplete="off">
                                </div>
                                <div class="col-md-6 form-group"
                                    style="display: flex; justify-content: flex-end; align-items: center; gap: 10px;">
                                    <button type="submit" class="btn btn-success">Gravar</button>

                                    <a href="{{ url('/home/showlancamento') }}" class="btn btn-primary">
                                        <i class="bi bi-arrow-return-left"></i>Listar
                                    </a>
                                </div>
                                <div class="col-9 message align-items-start">
                                    <h4 id="message" style="background-color: rgb(224, 233, 121)"></h4>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6" style="overflow-y: auto; max-height: 500px;">
                <div class="card shadow-sm my-3">
                    <div class="card-body">
                        <table id="ajaxList" class="table table-bordered">
                            <thead style="background-color: #92AFC2; max-width: 100%;">
                                <tr>
                                    <th>Doc</th>
                                    <th>Valor</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


@stop



@section('css')
    <!-- Add any required CSS here -->
@stop


@section('js')
    <script>
        document.getElementById('tipo_docto').focus();
        // Set today's date as default for the date input
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date();
            const formattedDate = today.getFullYear() + '-' +
                String(today.getMonth() + 1).padStart(2, '0') + '-' +
                String(today.getDate()).padStart(2, '0');
            document.getElementById('data_lcto').value = formattedDate;
        });

        document.getElementById('ajaxForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission

            const formData = new FormData(this); // Get form data
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute(
                'content'); // CSRF token

            fetch('/lancamento/store', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: formData,
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Append lançamento digitado na lateral
                        const tableBody = document.querySelector('#ajaxList tbody');
                        const newRow = document.createElement('tr');
                        newRow.style.backgroundColor = '#c1e0e6'; // Set background color for new row
                        newRow.innerHTML = `
                        <td>${formData.get('numero_docto')}</td>
                        <td>${formData.get('valor')}</td> `;

                        tableBody.prepend(newRow); // Use prepend to add the row at the top
                        //tableBody.appendChild(newRow);

                        // Set background color for the first row only
                        tableBody.querySelectorAll('tr').forEach((row, index) => {
                            row.style.backgroundColor = index === 0 ? '#c1e0e6' : '';
                        });

                        // Reset form inputs
                        document.getElementById('ajaxForm').reset();
                        document.getElementById('numero_docto').focus();

                        // Display success message
                        nrdocto = document.getElementById('numero_docto').value;
                        displayMessage('Lançamento ' + nrdocto + ' gravado', true);

                        // Force scroll container to update
                        const tableContainer = document.querySelector('#ajaxList').parentElement;
                        const rows = tableBody.querySelectorAll('tr');
                        if (rows.length > 10) {
                            tableContainer.style.overflowY = 'auto';
                            tableContainer.style.maxHeight = '500px';
                        }
                    } else {
                        // Display error message
                        displayMessage('Error: ' + data.message, false);
                    }
                })
                .catch(error => {
                    // Display error message
                    displayMessage('An error occurred.' + error, false);

                });
        })

        function jsOpenModalSearchCategoria() {

            $.ajax({
                url: '/categoria/consulta', // Substitua '/sua-rota' pela rota que chama o método 'suaFuncao' no seu Controller
                type: 'GET',
                success: function(response) {
                    // Insere o HTML do modal em um elemento da sua página
                    $('#seu-elemento-modal').html(response.html);
                    // Abre o modal (certifique-se de que o ID do modal está correto)
                    $('#modalListaCategoria').modal('show');
                },
                error: function(xhr, status, error) {
                    console.error("Erro ao carregar modal:", status, error);
                    alert(
                        'Ocorreu um erro ao carregar o modal. Por favor, tente novamente.'
                    ); // Melhore o feedback ao usuário.
                }
            });
        }

        function loadCreateCategoria() {
            $('#modalListaCategoria').modal('show');
        }

        function loadEditCategoria(id, nome, categoria_pai, nivel, fk_tipocategoria_id) {
            // Carrega os dados no modal de edição. Use os IDs corretos dos campos do seu modal.
            $('#editModalCategoria #edit_id').val(id);
            $('#editModalCategoria #edit_nome').val(nome);
            $('#editModalCategoria #edit_categoria_pai').val(categoria_pai);
            $('#editModalCategoria #edit_nivel').val(nivel);
            $('#editModalCategoria #edit_fk_tipocategoria_id').val(fk_tipocategoria_id);
            $('#editModalCategoria').modal('show'); // Abre o modal de edição.
        }

        function loadDeleteModal(id, nome) {
            // Carrega os dados no modal de exclusão. Use os IDs corretos do seu modal.
            $('#deleteModalCategoria #delete_id').val(id);
            $('#deleteModalCategoria #delete_nome').text(nome); // Exibe o nome no modal.
            $('#deleteModalCategoria').modal('show'); // Abre o modal de exclusão.
        }

        function jsOpenSelectEmpresa() {
            $('#modalSelectEmpresa').modal('show');

        }

        /**
         * Oculta/Exibe a seção de Categoria de Contrapartida e define valores padrão.
         *
         * Esta função deve ser chamada quando o valor do campo 'tipo_conta' muda.
         * Por exemplo, no HTML: <select id="tipo_conta" onchange="jsOcultaCategoriaContraPartida()"></select>
         */
        function jsOcultaCategoriaContraPartida() {
            // Usa jQuery para obter o valor do tipo de conta
            var tipoConta = $('#tipo_conta').val();
            var codPlanoCategoria = $('#codPlanoCategoria').val();
            var semCodCategoria = '99999';
            var semCodParceiro = '1';

            // Seleciona o elemento que contém a categoria de contrapartida (assumindo que tem a classe 'categoria-contrapartida')
            var categoriaContrapartidaContainer = $('.categoria-contrapartida');

            // Se o tipo de conta for 'Banco', exibe a seção
            if (tipoConta === 'banco') {
                categoriaContrapartidaContainer.show(); // jQuery: exibe o elemento (display: block)
                $('#categorias_id').val('');
                $('#conta_contrapartida').val('');
                $('#nomeContraPartida').val('');
                $('#nomeConta').val('');
            } else {
                // Se não for 'Banco', oculta a seção
                categoriaContrapartidaContainer.hide(); // jQuery: oculta o elemento (display: none)
                // Limpa ou define valores padrão para os campos quando a seção é oculta.
                // Assumindo que '99999' é um ID de categoria padrão ou "desativado".
                $('#categorias_id').val(semCodCategoria);
                $('#conta_contrapartida').val('1'); // Define um valor padrão       
                $('#nomeContraPartida').val('Sem Contrapartida'); // Define um valor padrão
                // Opcional: Se 'nomePartida' está associado e deve ser limpo/redefinido
                // $('#nomePartida').val('');
            }
        }

/**
 * Valida o valor do campo de entrada onBlur.
 * Verifica se o valor é zero ou nulo e exibe uma mensagem de alerta.
 * Valores negativos são considerados válidos.
 *
 * @param {HTMLInputElement} inputElement O elemento input HTML a ser validado.
 */
function formatAndValidateValor(inputElement) {
    let value = inputElement.value;

    // 1. Limpa o valor para conversão numérica
    // Remove tudo que não é dígito, exceto vírgula e o sinal de menos (para valores negativos)
    value = value.replace(/[^0-9,-]/g, '');
    value = value.replace(/\./g, ''); // Remove pontos de milhares para a conversão

    // 2. Converte para número para validação
    let numericValue;
    if (value.trim() === '' || value === ',' || value === '-') { // Lida com campo vazio, apenas vírgula ou apenas sinal de menos
        numericValue = 0.00; // Assume 0.00 para esses casos para a validação de zero/nulo
    } else {
        // Substitui vírgula por ponto para parseFloat
        let cleanedForParse = value.replace(',', '.');
        numericValue = parseFloat(cleanedForParse);
    }

    // Se o valor numérico for NaN (Not a Number) após a conversão, assume 0.00
    if (isNaN(numericValue)) {
        numericValue = 0.00;
    }

    // 3. Garante que o valor no input esteja no formato final de moeda (R$ X.XXX,XX)
    // Isso é importante para o onblur, para que o valor final seja sempre bem formatado.
    inputElement.value = numericValue.toLocaleString('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });

    // 4. Lógica de Validação e Feedback (usando displayMessage)
    // Remove classes de validação anteriores do input
    inputElement.classList.remove('is-invalid', 'is-valid');

    let message = '';
    let isSuccessMessage = true; // Para o displayMessage

    // Verifica se o valor é zero ou nulo (considerando 0.00 como nulo para este contexto)
    if (numericValue === 0.00) {
        message = 'O valor não pode ser zero ou nulo.';
        isSuccessMessage = false; // Isso é um erro/alerta
        inputElement.classList.add('is-invalid'); // Feedback visual vermelho
    } 

    // Exibe a mensagem usando a função global displayMessage
    if (typeof displayMessage === 'function') {
        displayMessage(message, isSuccessMessage);
    } else {
        console.warn('Função displayMessage não encontrada. Mensagem: ' + message);
    }
}

    </script>
@stop
