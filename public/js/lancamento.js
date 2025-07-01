console.log("Script lancamento.js loaded successfully");
//-- change tab to enter key for lancamento input --//
$('body').on('keydown', 'input, select', function (e) {
    if (e.key === "Enter") {
        var self = $(this),
            form = self.parents('form:eq(0)'),
            focusable, next;
        focusable = form.find('input,a,select,button,textarea').filter(':visible').not(
            ':input[readonly]');
        next = focusable.eq(focusable.index(this) + 1);
        if (next.length) {
            next.focus();
        } else {
            form.submit();
        }
        return false;
    }
});
// Busca nome do Parceiro no CADASTRO DE PARCEIROS
function jsCheckDocto(numero_docto) {
    var nrDoc = document.getElementById("numero_docto").value;
    if (nrDoc == null || nrDoc == "" || (nrDoc.trim().length === 0) || (numero_docto.value.length === 0)) {
        alert("Please Fill In All Required Fields");
        return false;
    }
    return true;
};
// Busca nome do Parceiro no CADASTRO DE PARCEIROS
function jsGetParceiro(codParc) {
    // Obtém o elemento de input onde o nome do parceiro será exibido
    const nomePartidaInput = $('#nomePartida');

    // Limpa o valor anterior do campo de nome e qualquer mensagem de erro
    nomePartidaInput.val('');
    displayMessage('', true); // Limpa a mensagem anterior (pode ser ajustado conforme sua função displayMessage)

    // Se o código do parceiro estiver vazio, não faz a requisição
    if (!codParc) {
        displayMessage('Por favor, informe o código do parceiro.', false);
        return;
    }

    $.ajax({
        url: '/parceiro/fetchparceiro/' + codParc, // URL da requisição com o ID no path
        type: 'GET', // Método HTTP GET
        // O cabeçalho X-CSRF-TOKEN não é estritamente necessário para requisições GET,
        // mas é inofensivo e pode ser mantido para consistência se a rota também aceitar POST.
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        // 'contentType: "application/json"' não é necessário para requisições GET, pois não há corpo de requisição.
        dataType: 'json', // Espera uma resposta JSON do servidor e faz o parse automaticamente
        success: function (response) {
            console.log("Resposta do servidor (sucesso):", response);
            // Verifica se a resposta e a propriedade 'nome' existem
            if (response && response.nome) {
                nomePartidaInput.val(response.nome);
                // displayMessage('Parceiro encontrado!', true); // Mensagem de sucesso opcional
            } else {
                // Caso a resposta não tenha o formato esperado (e.g., {nome: null} ou {error: "Parceiro não encontrado"})
                nomePartidaInput.val('');
                displayMessage('Parceiro não encontrado ou dados inválidos.', false);
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            console.error("Erro na requisição AJAX:", xhr.status, thrownError);
            nomePartidaInput.val(''); // Limpa o campo de nome em caso de erro

            if (xhr.status == 404) {
                // Erro 404: Parceiro não cadastrado
                displayMessage('Erro: Parceiro não cadastrado!', false);
                inputElement.focus(); // Coloca o foco de volta no campo
                inputElement.select(); // Seleciona o texto no campo para fácil correção
            } else if (xhr.status == 500) {
                // Erro 500: Erro interno do servidor (tenta parsear JSON para obter mensagem detalhada)
                try {
                    const errorResponse = JSON.parse(xhr.responseText);
                    displayMessage('Erro interno: ' + (errorResponse.error || 'Ocorreu um erro no servidor.'), false);
                } catch (e) {
                    displayMessage('Erro interno do servidor.', false);
                }
            } else {
                // Outros erros HTTP
                displayMessage('Ocorreu um erro inesperado: ' + thrownError, false);
            }
        }
    });
}
// Procura o nome da categoria em Categorias

function jsGetCategoria(categ_id) {
    var codPlanoCategoria = $("#codPlanoCategoria").val();
    nrCategoria = codPlanoCategoria + categ_id;
    console.log(nrCategoria);

    $.ajax({
        url: '/categoria/buscaNomeCategoria/' + nrCategoria,
        type: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        contentType: "application/json",
        dataType: 'text',
        success: function (response) {
            var objJSON = JSON.parse(response);
            nomeConta.value = objJSON.nome;;;
        },
        error: function (xhr, ajaxOptions, thrownError) {
            if (xhr.status == 404) {
                displayMessage('Erro:' + formatCodigoCategoria(nrConta) + '- Categoria não cadastrada!', false);
                document.getElementById('plano_contas_conta').focus();
                document.getElementById('plano_contas_conta').select();
                //$('#plano_contas_conta').focus();
            }
        }
    });
};
// Procura nome da conta no PLANO DE CONTAS
function jsGetContraPartida(nrContraPartida) {
    console.log(nrContraPartida);
    $.ajax({
        url: '/parceiro/fetchparceiro/' + nrContraPartida,
        type: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        contentType: "application/json",
        dataType: 'text',
        success: function (response) {
            var objJSON = JSON.parse(response);
            nomeContraPartida.value = objJSON.nome;
        },
        error: function (xhr, ajaxOptions, thrownError) {
            if (xhr.status == 404) {
                displayMessage('Erro:' + ' Parceiro não cadastrado!', false);
                document.getElementById('conta_contrapartida').focus();
                document.getElementById('conta_contrapartida').select();
            }
        }
    });
};

// DISPLAY messages
function displayMessage(message, isSuccess = true) {
    const messageElement = document.getElementById('message');
    messageElement.textContent = message;
    messageElement.style.color = isSuccess ? 'green' : 'red';
    messageElement.style.textAlign = 'left';
    const audio1 = new Audio('/audio/error-2.mp3');
    const audio2 = new Audio('/audio/new-notification-7.mp3');
    if (!isSuccess) {
        audio1.play();
    } else {
        audio2.play();
    }
}

// Function to play the error sound
function playErrorSound() {
    const audio = new Audio('/audio/error-2.mp3');
    audio.play();
}

// #### FORMAT CURRENCY / VALORES #####################
function moeda(a, e, r, t) {
    if (a.value.replace(/[^\d]/g, '').length > a.getAttribute("maxlength") - 1)
        return
    let n = "",
        h = j = 0,
        u = tamanho2 = 0,
        l = ajd2 = "",
        o = window.Event ? t.which : t.keyCode;
    if (13 == o || 8 == o)
        return !0;
    if (n = String.fromCharCode(o),
        -1 == "0123456789".indexOf(n))
        return !1;
    for (u = a.value.length,
        h = 0; h < u && ("0" == a.value.charAt(h) || a.value.charAt(h) == r); h++)
        ;
    for (l = ""; h < u; h++)
        -
            1 != "0123456789".indexOf(a.value.charAt(h)) && (l += a.value.charAt(h));
    if (l += n,
        0 == (u = l.length) && (a.value = ""),
        1 == u && (a.value = "0" + r + "0" + l),
        2 == u && (a.value = "0" + r + l),
        u > 2) {
        for (ajd2 = "",
            j = 0,
            h = u - 3; h >= 0; h--)
            3 == j && (ajd2 += e,
                j = 0),
                ajd2 += l.charAt(h),
                j++;
        for (a.value = "",
            tamanho2 = ajd2.length,
            h = tamanho2 - 1; h >= 0; h--)
            a.value += ajd2.charAt(h);
        a.value += r + l.substr(u - 2, u)
    }
    return !1
}


// armazena empresa selecionada para digitar os lançamento na variável de sessão app.companyCod e app.companyName //
$("#selEmpr").change(function () {
    var e = document.getElementById("selEmpr");
    var value = e.value;
    var text = e.options[e.selectedIndex].text;
    idsel = selEmpr.value;
    nomeEmpresa = text;
    $.ajax({
        url: '/selecioneEmpresa/' + idsel,
        type: 'post',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            $('#success_message').addClass('alert alert-primary');
            $('success_message').text(response.message);
            var textoAlerta = nomeEmpresa;
            $('input#alertText.form-control').val(textoAlerta);
            $('#alertBox').modal('show');
        }
    });
});


function selEmpresa() {
    $('#ModalSelecioneEmpresa').modal('show');
    // $('#ModalSelecioneEmpresa').on('shown', function () {
    //   $('#numero_docto').focus();
    //}) 
}

function selecionaEmpresa(id) {
    console.log('selecionaEmpresa:', id);

    $.ajax({
        url: `/empresa/empresaselecionada/${id}`, // Ajuste esta URL para sua rota de API
        method: 'GET',
        success: function (response) {
            console.log(response.valor); // Pega o valor do lançamento (deve ser numérico)
           window.location.reload(); // Recarrega a página para aplicar a mudança
        },
        error: function (xhr) {
            console.error("Erro ao carregar lançamento:", xhr.responseText);
            $('#message_update').text('Erro ao carregar dados do lançamento.');
        }
    });
}

function applyFilters() {
    const selectedEmpresaId = $('#empresa_nome').val();
    let selectedDate = ''; // Inicializa como vazio

    // Verifica se o checkbox "Todas as Datas" está marcado
    if ($('#todas_datas_checkbox').is(':checked')) {
        selectedDate = ''; // Se marcado, a data deve ser vazia para o filtro do backend
    } else {
        selectedDate = $('#data_selecionada').val(); // Caso contrário, usa a data do input
    }

    const currentUrl = new URL(window.location.href);

    // Atualiza o parâmetro 'empresaId' na URL
    if (selectedEmpresaId) {
        currentUrl.searchParams.set('empresaId', selectedEmpresaId);
    } else {
        currentUrl.searchParams.delete('empresaId');
    }

    // Atualiza o parâmetro 'dataSelecionada' na URL
    if (selectedDate) {
        currentUrl.searchParams.set('dataSelecionada', selectedDate);
    } else {
        currentUrl.searchParams.delete('dataSelecionada'); // Remove se "Todas as Datas" for selecionado (data vazia)
    }

    window.location.href = currentUrl.toString();
}


// ########################################################################
// Bloco principal que é executado quando o DOM está pronto.
// ########################################################################
$(document).ready(function() {

    // ... (sua delegação de eventos para expandir/recolher a tree view) ...

    // -------------------------------------------------------------
    // Lógica para o Checkbox "Todas as Datas"
    // -------------------------------------------------------------
    const $dataInput = $('#data_selecionada');
    const $todasDatasCheckbox = $('#todas_datas_checkbox');

    // Ao carregar a página, se o checkbox estiver marcado, desabilita o input de data
    if ($todasDatasCheckbox.is(':checked')) {
        $dataInput.prop('disabled', true);
    }

    // Adiciona listener para o checkbox
    $todasDatasCheckbox.on('change', function() {
        if ($(this).is(':checked')) {
            $dataInput.prop('disabled', true); // Desabilita o input de data
            $dataInput.val(''); // Opcional: Limpa o valor do input de data
        } else {
            $dataInput.prop('disabled', false); // Habilita o input de data
            // Opcional: Definir a data atual quando habilita novamente, se preferir
            // const today = new Date();
            // const yyyy = today.getFullYear();
            // const mm = String(today.getMonth() + 1).padStart(2, '0');
            // const dd = String(today.getDate()).padStart(2, '0');
            // $dataInput.val(`${yyyy}-${mm}-${dd}`);
        }
    });
    });