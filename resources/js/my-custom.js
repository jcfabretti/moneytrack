console.log('executing js in Resources..');
    document.getElementById('CodFiscal').addEventListener('input', function(e) {

        let x = e.target.value.replace(/\D/g, '').match(/(\d{0,2})(\d{0,3})(\d{0,3})(\d{0,4})(\d{0,2})/);
        e.target.value = !x[2] ? x[1] : x[1] + '.' + x[2] + (x[3] ? '.' : '') + x[3] + (x[4] ? '/' : x[4]) + x[
            4] + (x[5] ? '-' + x[5] : '');

        if (e.target.value.length < 15) {
            x = e.target.value.replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,3})(\d{0,2})/);
            e.target.value = !x[2] ? x[1] : x[1] + '.' + x[2] + (x[3] ? '.' : '') + x[3] + (x[4] ? '-' + x[4] :
                '');
        }
        //Caso queira pegar apenas os números use essa função para remover todos os caracteres menos os números em Javascript
        let valor = e.target.value.replace(/[^0-9]/g, '');
        console.log('Sem formatação: ' + valor);
    });
