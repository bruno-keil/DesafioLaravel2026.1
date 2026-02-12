<script>
    document.getElementById('cep').addEventListener('input', function(e) {
        let cep = e.target.value.replace(/\D/g, '');
        if (cep.length === 8) {
            fetch(`/api/cep/${cep}`)
                .then(response => response.json())
                .then(data => {
                    if (!data.erro) {
                        document.getElementById('logradouro').value = data.logradouro;
                        document.getElementById('bairro').value = data.bairro;
                        document.getElementById('cidade').value = data.localidade;
                        document.getElementById('uf').value = data.uf;
                        document.getElementById('estado').value = data.uf;
                    }
                })
                .catch(error => console.error('Erro ao buscar CEP:', error));
        }
    });
</script>
