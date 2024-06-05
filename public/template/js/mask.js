function mascaraCpfCnpj(input) {
    let value = input.value;
    value = value.replace(/\D/g, '');

    if (value.length <= 11) {
        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
    } else {
        value = value.replace(/^(\d{2})(\d)/, '$1.$2');
        value = value.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
        value = value.replace(/\.(\d{3})(\d)/, '.$1/$2');
        value = value.replace(/(\d{4})(\d)/, '$1-$2');
    }

    input.value = value;
}

function mascaraData(dataInput) {
    let data = dataInput.value;
    data = data.replace(/\D/g, '');
    data = data.replace(/(\d{2})(\d)/, '$1-$2')
    data = data.replace(/(\d{2})(\d)/, '$1-$2');
    dataInput.value = data;
}

function mascaraTelefone(telefoneInput) {
    let telefone = telefoneInput.value;
    telefone = telefone.replace(/\D/g, '');
    telefone = telefone.replace(/(\d{2})(\d)/, '($1) $2');
    telefone = telefone.replace(/(\d{5})(\d)/, '$1-$2');
    telefoneInput.value = telefone;
}

function mascaraReal(input) {
    let value = input.value;
    
    value = value.replace(/\D/g, '');
    value = (parseInt(value) / 100).toFixed(2);
    value = value.replace('.', ',');
    value = value.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');

    input.value = value;
}

function mascaraPorcentagem(input) {
    let value = input.value;

    // Remove caracteres não numéricos, exceto pontos
    value = value.replace(/[^\d.]/g, '');

    // Se houver mais de um ponto, remova os extras
    value = value.replace(/(\..*)\./g, '$1');

    // Formata como porcentagem
    if (value.includes('.')) {
        const parts = value.split('.');
        value = parts[0].replace(/\D/g, '') + '.' + parts.slice(1).join('');
    } else {
        value = value.replace(/\D/g, '');
    }

    // Atualiza o valor no input
    input.value = value;
}

function consultaCEP() {
    var cep = $('[name="postal_code"]').val();

    cep = cep.replace(/\D/g, '');

    if (/^\d{8}$/.test(cep)) {

        cep = cep.replace(/(\d{5})(\d{3})/, '$1-$2');
        $.get(`https://viacep.com.br/ws/${cep}/json/`, function (data) {
            $('[name="address"]').val(data.logradouro);
            $('[name="city"]').val(data.localidade);
            $('[name="state"]').val(data.uf);
        })
            .fail(function () {
                console.error('CEP não encontrado');
            });
    } else {
        console.error('CEP não encontrado');
    }
}

function geraExcel() {

    var tabela = document.getElementById('tabela');
    var wb = XLSX.utils.table_to_book(tabela, {
        sheet: 'Sheet 1'
    });
    var wbout = XLSX.write(wb, {
        bookType: 'xlsx',
        type: 'binary'
    });

    function s2ab(s) {
        var buf = new ArrayBuffer(s.length);
        var view = new Uint8Array(buf);
        for (var i = 0; i < s.length; i++) {
            view[i] = s.charCodeAt(i) & 0xFF;
        }
        return buf;
    }

    var blob = new Blob([s2ab(wbout)], {
        type: 'application/octet-stream'
    });
    var url = URL.createObjectURL(blob);
    var a = document.createElement('a');
    a.href = url;
    a.download = 'tabela.xlsx';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    setTimeout(function() {
        URL.revokeObjectURL(url);
    }, 100);
};

function copyToClipboard(link) {
    var linkElement = link.getAttribute("data-link"); // Use getAttribute para obter o valor de data-link
    var linkText = linkElement;

    var tempInput = document.createElement("input");
    tempInput.setAttribute("value", linkText); // Use setAttribute para definir o valor
    document.body.appendChild(tempInput);
    tempInput.select();
    document.execCommand("copy");
    document.body.removeChild(tempInput);

    alert("Link copiado para a área de transferência!");
}

