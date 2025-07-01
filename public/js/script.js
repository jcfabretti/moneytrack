
console.log('script.js loaded');

function limparCodCategoria(codCateg) {
  return codCateg.replace(/\./g, ''); // Replace all dots with empty string
}

function formatCodigoCategoria(code) {
  // formata codigo da categoria com pontos
  const newcode = String(code).slice(-5);
  part1 = newcode.charAt(0);        // First character
  part2 = newcode.substring(1, 3);  // Extracts characters at index 1 and 2
  part3 = newcode.substring(3, 5);  // Extracts characters at index 3 and 4

  // Return string formatada o forma: "X.YY.ZZ"
  return `${part1}.${part2}.${part3}`;
}

function formatCnpjCpf(value) {
  const cnpjCpf = value.replace(/\D/g, '');
  1607
  if (cnpjCpf.length === 11) {
    return cnpjCpf.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/g, "\$1.\$2.\$3-\$4");
  }

  return cnpjCpf.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/g, "\$1.\$2.\$3/\$4-\$5");
};

/**
 * Salva a quantidade de registos a serem exibidos por página via AJAX.
 *
 * @param {number} selectedValue O valor numérico da quantidade de itens por página.
 */
function saveQtyPerPage(selectedValue) {
    var qty = selectedValue;
    console.log('Tentando salvar quantidade por página:', qty);

    $.ajax({
        url: '/home/saveQtyPerPage', // Rota POST sem o parâmetro na URL
        method: 'POST', // Método HTTP POST
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'), // Token CSRF para segurança
            'Content-Type': 'application/json' // Informa que o corpo da requisição é JSON
        },
        data: JSON.stringify({ qty: qty }), // Envia os dados como um objeto JSON
        dataType: 'json', // Espera uma resposta JSON do servidor
        success: function (response) {
            console.log('Sucesso ao salvar quantidade por página:', response);
            // Recarrega a página para aplicar a nova configuração
            location.reload();
        },
        error: function (xhr, status, error) {
            console.error('Erro ao salvar quantidade por página:', xhr.status, error);
            console.error('Resposta do servidor:', xhr.responseText);
            // Exibe uma mensagem de erro para o utilizador (pode ser um modal, um alerta, etc.)
            alert('Ocorreu um erro ao salvar a configuração de itens por página. Por favor, tente novamente.');
        }
    });
}

function validarCnpj(cnpj) {
  if (!cnpj || cnpj.length != 14
    || cnpj == "00000000000000"
    || cnpj == "11111111111111"
    || cnpj == "22222222222222"
    || cnpj == "33333333333333"
    || cnpj == "44444444444444"
    || cnpj == "55555555555555"
    || cnpj == "66666666666666"
    || cnpj == "77777777777777"
    || cnpj == "88888888888888"
    || cnpj == "99999999999999")
    return false
  var tamanho = cnpj.length - 2
  var numeros = cnpj.substring(0, tamanho)
  var digitos = cnpj.substring(tamanho)
  var soma = 0
  var pos = tamanho - 7
  for (var i = tamanho; i >= 1; i--) {
    soma += numeros.charAt(tamanho - i) * pos--
    if (pos < 2) pos = 9
  }
  var resultado = soma % 11 < 2 ? 0 : 11 - soma % 11
  if (resultado != digitos.charAt(0)) return false;
  tamanho = tamanho + 1
  numeros = cnpj.substring(0, tamanho)
  soma = 0
  pos = tamanho - 7
  for (var i = tamanho; i >= 1; i--) {
    soma += numeros.charAt(tamanho - i) * pos--
    if (pos < 2) pos = 9
  }
  resultado = soma % 11 < 2 ? 0 : 11 - soma % 11
  if (resultado != digitos.charAt(1)) return false
  return true;
};

function validaCpf(cpf) {
  if (!cpf || cpf.length != 11
    || cpf == "00000000000"
    || cpf == "11111111111"
    || cpf == "22222222222"
    || cpf == "33333333333"
    || cpf == "44444444444"
    || cpf == "55555555555"
    || cpf == "66666666666"
    || cpf == "77777777777"
    || cpf == "88888888888"
    || cpf == "99999999999")
    return false
  var soma = 0
  var resto
  for (var i = 1; i <= 9; i++)
    soma = soma + parseInt(cpf.substring(i - 1, i)) * (11 - i)
  resto = (soma * 10) % 11
  if ((resto == 10) || (resto == 11)) resto = 0
  if (resto != parseInt(cpf.substring(9, 10))) return false
  soma = 0
  for (var i = 1; i <= 10; i++)
    soma = soma + parseInt(cpf.substring(i - 1, i)) * (12 - i)
  resto = (soma * 10) % 11
  if ((resto == 10) || (resto == 11)) resto = 0
  if (resto != parseInt(cpf.substring(10, 11))) return false
  return true
};

/* FORMAT numero categoria */
function js_formatCategoria(codigo) {
  codigo = codigo.toString();
  codigo = codigo.trim();
  codigofinal1 = codigo.substring(1, 2) + '.';
  codigofinal2 = codigo.substring(2, 4) + '.';
  codigofinal3 = codigo.substring(4, 7);
  return (codigofinal1 + codigofinal2 + codigofinal3);
};

// ########## FORMATAR codigo de categoria no padrão x.xx.xx ###############
function formatNumberToPattern(number) {
  // Convert the number to a string and remove non-digit characters
  const digits = number.toString().replace(/\D/g, "");

  // Define the pattern group sizes
  const groupSizes = [1, 2, 2]; // Adjust sizes as needed
  let formatted = "";
  let currentIndex = 0;

  // Build the formatted string based on the group sizes
  for (const size of groupSizes) {
    if (currentIndex < digits.length) {
      if (formatted) {
        formatted += ".";
      }
      formatted += digits.substr(currentIndex, size);
      currentIndex += size;
    }
  }

  // Append any remaining digits (if any, and desired)
  if (currentIndex < digits.length) {
    formatted += "." + digits.substr(currentIndex);
  }

  return formatted;
}

// ####################################################################################  
// Function to display error message below the modal header
function displayErrorMessage(message) {
  const modalHeader = document.querySelector('.modal-header');

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

// ######################################################################################
// Função para lidar com o evento de tecla pressionada no campo de valores monetarios

// Apaga o campo de valor antes da função formatMoeda() abaixo
function handleDeleteClear(event) {
    if (event.key === 'Delete') {
        event.preventDefault(); // evita comportamento padrão
        event.target.value = '';
        event.target.style.color = 'black';
    }
}

// Função para formatar o campo de valor monetário
// Esta função deve ser chamada no evento 'input' do campo de entrada
// e.g., <input type="text" oninput="formatMoeda(this)">
// Esta função formata o valor monetário com separadores de milhar e decimal
function formatMoeda(input, decimalSep = ',', thousandSep = '.') {
    let raw = input.value;

    // Detecta sinal negativo no início
    let isNegative = raw.startsWith('-');

    // Remove tudo que não for dígito
    let numbers = raw.replace(/[^0-9]/g, '');

  //  if (numbers.length < 3) {
   //     numbers = numbers.padStart(3, '0');
    //}

    let cents = numbers.slice(-2);
    let whole = numbers.slice(0, -2);

    // Formata milhares
    let formattedWhole = '';
    while (whole.length > 3) {
        formattedWhole = thousandSep + whole.slice(-3) + formattedWhole;
        whole = whole.slice(0, -3);
    }
    formattedWhole = whole + formattedWhole;

    let formatted = formattedWhole + decimalSep + cents;

    // Aplica o sinal negativo
    input.value = (isNegative ? '-' : '') + formatted;

    // Altera cor conforme sinal
    input.style.color = isNegative ? 'red' : 'black';
}


