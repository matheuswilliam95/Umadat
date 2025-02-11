document.addEventListener("DOMContentLoaded", function () {
    const selectCongregacao = document.getElementById("congregacao");
    const selectConjunto = document.getElementById("conjunto");

    selectCongregacao.addEventListener("change", function () {
        const congregacaoId = this.value;
        selectConjunto.innerHTML = '<option value="">Carregando...</option>';

        if (congregacaoId) {
            fetch(`../api/get_conjuntos.php?congregacao_id=${congregacaoId}`)
                .then(response => response.json())
                .then(data => {
                    selectConjunto.innerHTML = '<option value="">Selecione um conjunto</option>';
                    data.forEach(conjunto => {
                        const option = document.createElement("option");
                        option.value = conjunto.id;
                        option.textContent = conjunto.nome;
                        selectConjunto.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error("Erro ao carregar conjuntos:", error);
                    selectConjunto.innerHTML = '<option value="">Erro ao carregar</option>';
                });
        } else {
            selectConjunto.innerHTML = '<option value="">Selecione uma congregação primeiro</option>';
        }
    });
});
