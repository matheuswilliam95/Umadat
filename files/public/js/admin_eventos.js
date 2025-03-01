document.addEventListener('DOMContentLoaded', () => {
    initAutocomplete();
});

function initAutocomplete() {
    console.log("Inicializando auto completar.");
    const input = document.getElementById('local');
    const suggestionsContainer = document.getElementById('suggestions');

    if (!input || !suggestionsContainer) {
        console.error("Elemento de input ou container de sugestões não encontrado.");
        return;
    }

    input.addEventListener('input', () => {
        const query = input.value;
        console.log(`Valor do input: ${query}`);

        if (query.length > 2) {
            const url = `../includes/proxy.php?q=${encodeURIComponent(query)}`;
            console.log(`Fazendo fetch para: ${url}`);

            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Resposta da rede não ok');
                    }
                    return response.json();
                })
                .then(results => {
                    console.log("Resultados obtidos:", results);
                    suggestionsContainer.innerHTML = '';

                    if (results.length > 0) {
                        results.forEach(result => {
                            const suggestionItem = document.createElement('div');
                            suggestionItem.className = 'suggestion-item';
                            suggestionItem.textContent = result.display_name;

                            suggestionItem.addEventListener('click', () => {
                                input.value = result.display_name;
                                suggestionsContainer.innerHTML = '';
                            });

                            suggestionsContainer.appendChild(suggestionItem);
                        });
                    } else {
                        console.log("Nenhuma sugestão encontrada.");
                    }
                })
                .catch(error => console.error("Erro durante a requisição:", error));
        } else {
            suggestionsContainer.innerHTML = '';
        }
    });
}
