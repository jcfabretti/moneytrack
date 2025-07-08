/// public/js/categoria.js

// ############################################################
// FUNÇÕES GLOBAIS (Declaradas uma única vez no escopo global)
// ############################################################

// Load Create categoria Modal
function loadAddCategoria(tipoCategoriaId, selectedTipoCategoriaText) {
    // Verifica se o tipoCategoriaId foi passado
    if (!tipoCategoriaId) {
        // Se não foi selecionado, exibe um alerta e não abre o modal
        alert('Por favor, selecione um Tipo de Categoria antes de incluir.');
        return; // Sai da função para não abrir o modal
    }

    // 1. Seta valores dos campos
    $('.modal-subtitle').text(selectedTipoCategoriaText);
    document.getElementById('numero_categoria').value = '';
    document.getElementById('nome').value = '';
    document.getElementById('categoria_id').value = '';
    $('#tipo_categoria').val(tipoCategoriaId);         // Seta o tipo de categoria selecionado no modal
    document.getElementById('categoria_pai').value = '';
    document.getElementById('categoriaPai_legenda').value = '';

    // 2. Remove mensagens de erro anteriores, se existirem (supondo que 'removeErrorMessage()' exista)
    removeErrorMessage(); // Função que você deve ter para limpar validações

    // 7. Abre o modal
    $('#addCategoriaModal').modal('show');

}

// Load Create categoria Modal
function loadEditCategoria(id, nome, categoria_pai, nivel) {
    // Obtém a referência ao elemento <select>
    const tipoCategoriaSelect = document.getElementById('tipoCategoria_select');

    // Atribui o valor selecionado ao campo oculto (ou não) do modal
    // Certifique-se de que o ID do campo no seu modal seja 'tipo_categoria'
    document.getElementById('tipo_categoria').value = tipoCategoriaSelect.value;

    const newcode = String(id).slice(-5);
    part1 = newcode.charAt(0);        // First character
    part2 = newcode.substring(1, 3);  // Extracts characters at index 1 and 2
    part3 = newcode.substring(3, 5);  // Extracts characters at index 3 and 4

    // Pega o NOME da Categoria Pai
    $.ajax({
        url: `getNomeCategoria/${categoria_pai}`,
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (userData) {
            // Assuming the server returns an object with 'id' and 'nome' fields

            const idFormated = formatCodigoCategoria(userData.id);
            const nome = userData.nome;

            // Concatenate id and nome
            if (`${nivel}` == 1) {
                var categoriaPaiDisplay = "0.00.00-GRUPO TOTALIZADOR";

            } else {
                var categoriaPaiDisplay = `${idFormated}-${nome}`;
            }

            $('.modal-body #categoriaPai_legenda').val(categoriaPaiDisplay);

            // Remove the error message if the request is successful
            removeErrorMessage();
        },
    });

    // Seta valores nos <input text> do modal via Jquery
    var formatedId = part1 + '.' + part2 + '.' + part3;
    $('.modal-body #categoria_id').val(formatedId);
    $('.modal-body #numero_categoria').val(formatedId);
    $('.modal-body #nome').val(nome);
    $('.modal-body #categoria_pai').val(categoria_pai);
    $('.modal-body #nivel').val(nivel);

    // Show the modal
    $('#editCategoria').modal('show');

}

// Load Delete Modal
function loadDeleteModal(id, nome) {
    numeroComNome = formatCodigoCategoria(id) + '-' + nome;
    removeErrorMessage();
    $('#modal-category_name').text(numeroComNome);
    $('#modal-confirm_delete').attr('onclick', `confirmDelete(${id})`);
    $('#deleteCategory').modal('show');
}

// confirmDelete
function confirmDelete(id) {
    if (!id) {
        alert("Invalid ID provided.");
        return;
    }
    console.log('entrou');
    $.ajax({
        url: `/categoria/destroy/${id}`,
        type: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },

        success: function (response) {
            console.log('sucesso');
            if (response && response.message) {
                $(".errors").text(response.message); // Assuming `.errors` is a container for error messages.
                alert(response.message);
                location.reload(); // Reload the page to reflect the changes.
            } else {
                alert("Unexpected response format.");
            }
            removeErrorMessage();
        },
        error: function (xhr, ajaxOptions, thrownError) {
            // Check the status code from the response
            var response = xhr
                .responseJSON; // This assumes the server is sending a JSON response
            var errorMessage = 'An error occurred.';


            // If the response contains a status and message
            if (response && response.status && response.message) {
                errorMessage = response.message; // Get the error message from the response

            }
            // Display the error message under the modal header
            displayErrorMessage('#deleteCategory', "Erro: " + errorMessage);

        }

    });
}


// Formata codigo da categoria para exibição
function formatCodigoCategoria(code) {
    // formata codigo da categoria com pontos
    const newcode = String(code).slice(-5);
    part1 = newcode.charAt(0);        // First character
    part2 = newcode.substring(1, 3);  // Extracts characters at index 1 and 2
    part3 = newcode.substring(3, 5);  // Extracts characters at index 3 and 4

    // Return string formatada o forma: "X.YY.ZZ"
    return `${part1}.${part2}.${part3}`;
}

// Function to display error message below the modal header
function displayErrorMessage(formId, message) {
    formId = formId + ' .modal-header';
    console.log('id>' + formId);
    const modalHeader = document.querySelector(formId);
    // Check if error message already exists, to avoid duplicates
    let existingError = document.getElementById("modal-error-message");
    if (!existingError) {
        // Create a new div to hold the error message
        const errorDiv = document.createElement('div');
        errorDiv.id = "modal-error-message";
        errorDiv.style.color = 'red'; // Set the error message color
        errorDiv.style.marginTop = '5px'; // Add a small space between the header and the error message
        errorDiv.style.fontSize = '16px'; // Optional: adjust font size for the error message
        errorDiv.style.textAlign = 'left'; // Ensure text is left-aligned
        errorDiv.style.width = '100%'; // Ensure it spans the full width below the header

        // Set the error message content
        errorDiv.innerHTML = message;
        // Append the new div below the modal header
        modalHeader.appendChild(errorDiv);
    } else {
        // If the error message already exists, just update the content
        existingError.innerHTML = message;
    }
}

// Function to remove the error message
function removeErrorMessage() {
    const errorDiv = document.getElementById("modal-error-message");
    if (errorDiv) {
        errorDiv.remove(); // Remove the error message div
    }
}

// FORMATA no momento da digitação o codigo da Categoria
function js_formatCategoria(a, e, r, t) {
    // ao final chama funcao handleCreateCateg(a.value);
    // Prevent input length from exceeding the max allowed digits
    if (a.value.replace(/[^\d]/g, '').length > a.getAttribute("maxlength") - 1)
        return;

    let n = "",
        h = j = 0,
        u = tamanho2 = 0,
        l = ajd2 = "";
    const o = window.Event ? t.which : t.keyCode; // Get the key code

    // Allow "Enter" (13) and "Backspace" (8) keys for editing
    if (13 == o || 8 == o)
        return true;

    // Get the key that was pressed
    n = String.fromCharCode(o);

    // If the key is not a number, ignore the input
    if ("0123456789".indexOf(n) === -1)
        return false;

    // Remove leading zeros and other unwanted characters
    for (u = a.value.length, h = 0; h < u && ("0" == a.value.charAt(h) || a.value.charAt(h) == r); h++);
    for (l = ""; h < u; h++)
        if ("0123456789".indexOf(a.value.charAt(h)) !== -1) l += a.value.charAt(h);

    // Append the new character to the value
    l += n;

    // If the string is empty, clear the value
    if (l.length === 0) {
        a.value = "";
    }

    // For 1 or 2 digits, format with leading zeros and a period
    if (l.length === 1) {
        a.value = "0" + r + "0" + l;
    } else if (l.length === 2) {
        a.value = "0" + r + l;
    } else if (l.length > 2) {
        // Format the string with periods after every two digits
        for (ajd2 = "", j = 0, h = l.length - 1; h >= 0; h--) {
            // Add a period after every two digits
            if (j == 2) {
                ajd2 += e;
                j = 0;
            }
            ajd2 += l.charAt(h);
            j++;
        }

        // Reverse the string to match the correct order
        a.value = "";
        tamanho2 = ajd2.length;
        for (h = tamanho2 - 1; h >= 0; h--) {
            a.value += ajd2.charAt(h);
        }
    }

    // Check if the formatted string length reaches 7
    if (a.value.replace(/[^\d]/g, '').length === 5) {
        // Manually trigger the onchange handler when the length is 7

        if (typeof handleCreateCateg === 'function') {
            handleCreateCateg(a.value);
        }
    }

    return false;
}

// Checa se categoria pai existe
function handleCreateCateg(id) {

    //Habilita botão SALVAR
    const btnSalvar = document.querySelector('input.btn.btn-success');
    btnSalvar.disabled = false;

    const codigo_planoCategoria = document.getElementById('tipo_categoria').value;

    // Extract parts of the ID and initialize variables
    const parts = id.split('.');
    let nivelPai = '';

    // Validate that the first part is not '0'
    if (parts[0] === '0') {
        // Display error message in a new line under the modal header
        displayErrorMessage('#addCategoriaModal', "Erro: Format invalido. Redigite.");
        inputField.focus();
        return;
    }

    // Determine the level and construct nivelPai
    if (parts[1] === '00' && parts[2] === '00') {
        // Nivel 1
        nivelPai = '00000';
        $('#nivel').val(1);
    } else if (parts[2] === '00') {
        // Nivel 2
        nivelPai = `${parts[0]}0000`;
        $('#nivel').val(2);
    } else {
        // Nivel 3
        nivelPai = `${parts[0]}${parts[1]}00`;
        $('#nivel').val(3);
    }

    // Seta categoria_id que será armazenada no BD
    document.getElementById("categoria_id").value = `${parts[0]}${parts[1]}${parts[2]}`;
    codCategoria = document.getElementById("categoria_id").value;
    nivelPaiDisplay = nivelPai;

    // Adiciona o codigo do Plano de Categoria ao codigo
    nivelPai = codigo_planoCategoria + nivelPai;
    codCategoria = codigo_planoCategoria + codCategoria;

    // Perform AJAX request to fetch categories
    $.ajax({
        url: `getcategorias/${codCategoria}/${nivelPai}`,
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (userData) {
            // Assuming the server returns an object with 'id' and 'nome' fields
            const id = userData.id;
            const nome = userData.nome;

            // Concatenate id and nome
            const categoriaPaiDisplay = `${nivelPaiDisplay}-${nome}`;

            // Set the value of input with id "categoriaPai_legenda"
            document.getElementById("categoria_pai").value = `${id}`;
            document.getElementById("categoriaPai_legenda").value = categoriaPaiDisplay;

            // Remove the error message if the request is successful
            removeErrorMessage();


        },
        error: function (xhr, ajaxOptions, thrownError) {
            // Check the status code from the response
            var response = xhr
                .responseJSON; // This assumes the server is sending a JSON response
            var errorMessage = 'An error occurred.';


            // If the response contains a status and message
            if (response && response.status && response.message) {
                errorMessage = response.message; // Get the error message from the response

                // Status 409 - cod categoria já cadastrado
                if (response.status === 409) {
                    document.getElementById("categoriaPai_legenda").value =
                        ''; // limpa legenda categ pai
                } else {
                    // Clear input fields
                    document.getElementById("categoria_pai").value = '';
                    document.getElementById("categoriaPai_legenda").value =
                        nivelPaiDisplay +
                        ' - Categoria Pai Não está cadastrada!';
                }
            }
            // Display the error message under the modal header
            displayErrorMessage('#addCategoriaModal', "Erro: " + errorMessage);

            // desabilita botão Salvar 
            const btnSalvar = document.querySelector('input.btn.btn-success');
            btnSalvar.disabled = true;


            // Focus back on the input field
            document.getElementById("numero_categoria").focus();
            document.getElementById("numero_categoria").select();
        }
    });
}

$(document).ready(function() {
    window.loadAddCategoria = loadAddCategoria; // Torna a função global para ser acessível no onclick
    // Função AJAX para recarregar a Tree View
    function recarregarTreeView(selectedTipoCategoriaId) {
        console.log('AJAX: recarregarTreeView - ID selecionado:', selectedTipoCategoriaId);

        var treeViewContainer = $('#categoria-table');

        // Mostra o spinner/mensagem de carregamento
        treeViewContainer.html('<tr><td colspan="3" class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x"></i> Carregando categorias...</td></tr>');

        $.ajax({
            url: `/categoria/indexTreeView/${selectedTipoTipoCategoriaId}`, // Certifique-se que esta URL corresponde à sua rota Laravel
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log('AJAX: Requisição bem-sucedida. Conteúdo da resposta:', response);
                if (response && response.trim().length > 0) {
                    treeViewContainer.html(response);
                    console.log("AJAX: Tree View atualizada com sucesso no #categoria-table!");
                    // Não é necessário reiniciar os listeners da treeview aqui por causa da delegação.
                } else {
                    treeViewContainer.html('<tr><td colspan="3" class="text-center py-4">Nenhuma categoria encontrada para o tipo selecionado.</td></tr>');
                    console.warn("AJAX: Resposta vazia ou sem conteúdo da Tree View.");
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX: Erro na requisição AJAX:", xhr.responseText, status, error);
                treeViewContainer.html('<tr><td colspan="3" class="alert alert-danger text-center">Erro ao carregar a estrutura de categorias. Por favor, tente novamente.</td></tr>');
            }
        });
    }
    window.recarregarTreeView = recarregarTreeView; // Torna a função global para ser acessível no onchange

    // -------------------------------------------------------------
    // DELEGAÇÃO DE EVENTOS PARA EXPANDIR/RECOLHER A TREE VIEW
    // -------------------------------------------------------------
    // Anexa o evento de clique ao 'document' (ou a um container estático maior que a tabela)
    // Isso garante que mesmo o conteúdo injetado por AJAX terá o evento de clique.
    $(document).on('click', '.toggle-icon', function(e) {
        const currentIcon = $(this);
        const parentRow = currentIcon.closest('tr');
        if (!parentRow.length) return; // Garante que encontramos uma linha

        const parentId = currentIcon.data('id'); // Pega o data-id do ícone
        const nivel = parseInt(parentRow.data('nivel'));
        const tableBody = $('#categoria-table'); // Seu tbody, que contém as linhas

        const rowsToToggle = tableBody.find(`tr[data-parent-id='${parentId}']`);

        const isExpanded = currentIcon.hasClass('fa-minus-circle');
        currentIcon.toggleClass('fa-plus-circle', isExpanded); // Se expandido, volta para '+'
        currentIcon.toggleClass('fa-minus-circle', !isExpanded); // Se não expandido, vira '-'

        rowsToToggle.each(function() {
            const row = $(this);
            row.toggle(!isExpanded); // Alterna a visibilidade (display: none/block)

            // Colapsa os filhos de forma recursiva (se a linha atual está sendo recolhida)
            if (isExpanded) { // Se o ícone mudou de '-' para '+' (ou seja, estamos recolhendo)
                const childId = row.data('id');
                const grandChildRows = tableBody.find(`tr[data-parent-id='${childId}']`);
                grandChildRows.each(function() {
                    $(this).hide(); // Esconde as linhas netas
                    const grandChildIcon = $(this).find('.toggle-icon');
                    if (grandChildIcon.length) {
                        grandChildIcon.removeClass('fa-minus-circle').addClass('fa-plus-circle');
                    }
                });
            }
        });
    });

    // -------------------------------------------------------------
    // CARREGAMENTO INICIAL DA TREE VIEW (SE HOUVER UMA SELEÇÃO PADRÃO)
    // -------------------------------------------------------------
    // Este bloco já deve estar no seu `document.ready` da página principal.
    // Garante que a Tree View seja carregada na carga inicial se houver um tipo selecionado.
    var initialSelectedTipoId = $('#tipoCategoria_select').val();
    if (initialSelectedTipoId && initialSelectedTipoId !== "") {
        recarregarTreeView(initialSelectedTipoId);
    }
});

    