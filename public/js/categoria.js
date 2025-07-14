// Funções para o modelo CATEGORIA
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

// ############################################################
// load Modal Edit Categoria
function loadEditCategoria(id, nome, categoria_pai, nivel,tipoCategoriaId) {
    $('#tipoCategoria_id').val( tipoCategoriaId); // Seta o tipo de categoria selecionado no modal

    const newcode = String(id).slice(-5);
    part1 = newcode.charAt(0);        // First character
    part2 = newcode.substring(1, 3);  // Extracts characters at index 1 and 2
    part3 = newcode.substring(3, 5);  // Extracts characters at index 3 and 4

    if (`${nivel}` == 1) {
        categoria_pai = tipoCategoriaId +'00000'; // Categoria Pai para nível 1 é sempre 00000
    }

    // Pega o NOME da Categoria Pai
    $.ajax({
        url: `/categoria/getNomeCategoria/${categoria_pai}`,
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (userData) {
            // Assuming the server returns an object with 'id' and 'nome' fields

            const idFormated = formatCodigoCategoria(userData.numero_categoria);
            const nome = userData.nome;

            if (`${nivel}` == 1) {
                var categoriaPaiDisplay = "0.00.00-GRUPO TOTALIZADOR";

            } else {
                var categoriaPaiDisplay = `${idFormated}-${nome}`;
            }

             $('#editCategoria .modal-body #categoriaPai_legenda').val(categoriaPaiDisplay);
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
// ##################################################################
// Load Delete Modal
function loadDeleteModal(id, nome) {
    numeroComNome = formatCodigoCategoria(id) + '-' + nome;
    removeErrorMessage();
    $('#modal-category_name').text(numeroComNome);
    $('#modal-confirm_delete').attr('onclick', `confirmDelete(${id})`);
    $('#deleteCategory').modal('show');
}

function confirmDelete(id) {
    if (!id) {
        alert("Invalid ID provided.");
        return;
    }
    $.ajax({
        url: `/categoria/destroy/${id}`,
        type: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },

        success: function (response) {

            if (response && response.message) {
                $(".errors").text(response.message); // Assuming `.errors` is a container for error messages.
                // $('#deleteCategory').modal('hide');
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


// ###################################################################
// Formata codigo da categoria
function formatCodigoCategoria(code) {
   // formata codigo da categoria com pontos
    const newcode = String(code).slice(-5);
    part1 = newcode.charAt(0);        // First character
    part2 = newcode.substring(1, 3);  // Extracts characters at index 1 and 2
    part3 = newcode.substring(3, 5);  // Extracts characters at index 3 and 4

    // Return string formatada o forma: "X.YY.ZZ"
    return `${part1}.${part2}.${part3}`;
}

// ###################################################################
// Function to display error message below the modal header
function displayErrorMessage(formId, message) {
    formId = formId + ' .modal-header';

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

// ########################################################################
//  FORMATA no momento da digitação o codigo da Categoria
//  Quando completa 5 digitos chama a função handleCreateCateg(a.value);
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

// ########################################################################
// chamada por js_formatCategoria ao completar 5 digitos
// procura se o nivel pai acima já existe e mostra o nome ou mensagem de erro

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
        url: `/categoria/getcategorias/${codCategoria}/${nivelPai}`,
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (userData) {
            // Assuming the server returns an object with 'id' and 'nome' fields
            const id = userData.numero_categoria;
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



// Função para formatar o número da categoria
function formatarNumeroCategoria(id) {
    return id.toString().padStart(4, '0'); // Exemplo: formata 1 como 0001
}


// ########################################################################
// Bloco principal que é executado quando o DOM está pronto.
// ESTE É O ÚNICO $(document).ready() QUE DEVE HAVER NESTE ARQUIVO.
// ########################################################################
$(function () {

    // -------------------------------------------------------------
    // FUNÇÃO AJAX PARA RECARREGAR A TREE VIEW (MOVIDA PARA DENTRO DO ready)
    // -------------------------------------------------------------
    // Esta função NÃO precisa ser global (window.recarregarTreeView = ...).
    // Ela é chamada do HTML (onchange) e do $(document).ready().
    // Para ser acessível do onchange, ela precisa ser global.
    // MAS, para manter a consistência da refatoração e evitar duplicação,
    // vamos deixá-la aqui e adicionar o "window." prefix.
    function recarregarTreeView(selectedTipoCategoriaId) {

        var treeViewContainer = $('#categoria-table');

        treeViewContainer.html('<tr><td colspan="3" class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x"></i> Carregando categorias...</td></tr>');

        $.ajax({
            url: `/categoria/treeview-ajax/${selectedTipoCategoriaId}`, // CORRIGIDO O TYPO AQUI!
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                if (response && response.trim().length > 0) {
                    treeViewContainer.html(response);
                } else {
                    treeViewContainer.html('<tr><td colspan="3" class="text-center py-4">Nenhuma categoria encontrada para o tipo selecionado.</td></tr>');
                    // console.warn("AJAX: Resposta vazia ou sem conteúdo da Tree View.");
                }
            },
            error: function (xhr, status, error) {
                //    console.error("AJAX: Erro na requisição AJAX:", xhr.responseText, status, error);
                treeViewContainer.html('<tr><td colspan="3" class="alert alert-danger text-center">Erro ao carregar a estrutura de categorias. Por favor, tente novamente.</td></tr>');
            }
        });
    }

    // Tornando a função recarregarTreeView global para que o `onchange` do HTML possa chamá-la.
    window.recarregarTreeView = recarregarTreeView;


    // -------------------------------------------------------------
    // DELEGAÇÃO DE EVENTOS PARA EXPANDIR/RECOLHER A TREE VIEW
    // -------------------------------------------------------------
    // Anexa o evento de clique ao 'document' para '.toggle-icon'
    $(document).on('click', '.toggle-icon', function (e) {
        const currentIcon = $(this);
        const parentRow = currentIcon.closest('tr');
        if (!parentRow.length) return;

        const parentId = currentIcon.data('id');
        const nivel = parseInt(parentRow.data('nivel'));
        const tableBody = $('#categoria-table');

        const rowsToToggle = tableBody.find(`tr[data-parent-id='${parentId}']`);

        const isExpanded = currentIcon.hasClass('fa-minus-circle');
        currentIcon.toggleClass('fa-plus-circle', isExpanded);
        currentIcon.toggleClass('fa-minus-circle', !isExpanded);

        rowsToToggle.each(function () {
            const row = $(this);
            row.toggle(!isExpanded);

            if (isExpanded) {
                const childId = row.data('id');
                const grandChildRows = tableBody.find(`tr[data-parent-id='${childId}']`);
                grandChildRows.each(function () {
                    $(this).hide();
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
    var initialSelectedTipoId = $('#tipoCategoria_select').val();
    if (initialSelectedTipoId && initialSelectedTipoId !== "") {
        recarregarTreeView(initialSelectedTipoId);
    }
});