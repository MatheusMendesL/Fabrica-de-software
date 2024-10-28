document.getElementById('1').addEventListener('blur', function() {
    const cep = this.value.replace(/\D/g, '');
    if (cep.length === 8 || cep.length === 0) {
        fetch(`https://viacep.com.br/ws/${cep}/json/`)
            .then(response => response.json())
            .then(data => {
                if (!data.erro) {
                    //document.getElementById('3').removeAttribute('disabled');
                    document.getElementById('3').setAttribute('value', data.logradouro);
                    //document.getElementById('2').removeAttribute('disabled');
                    document.getElementById('2').setAttribute('value', data.bairro);
                    //document.getElementById('7').removeAttribute('disabled');
                    document.getElementById('7').setAttribute('value', data.localidade);
                    //document.getElementById('6').removeAttribute('disabled');
                    document.getElementById('6').setAttribute('value', data.uf);
                }
            })
            .catch(error => console.error('Erro:', error));
    } else {
        alert("CEP inválido.");
        document.getElementById('3').setAttribute('value', '');
        document.getElementById('2').setAttribute('value', '');
        document.getElementById('7').setAttribute('value', '');
        document.getElementById('6').setAttribute('value', '');
    }
});

// Habilitar campos no envio do formulário

