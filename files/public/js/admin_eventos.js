function initAutocomplete() {
    const input = document.getElementById('local');
    const suggestionsContainer = document.getElementById('suggestions');
    input.addEventListener('input', function () {
        const query = input.value;
        if (query.length > 2) {
            fetch(`../includes/proxy.php?q=${encodeURIComponent(query)}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(results => {
                    suggestionsContainer.innerHTML = '';
                    if (results.length > 0) {
                        results.forEach(result => {
                            const suggestionItem = document.createElement('div');
                            suggestionItem.className = 'suggestion-item';
                            suggestionItem.textContent = result.display_name;
                            suggestionItem.addEventListener('click', function () {
                                input.value = result.display_name;
                                suggestionsContainer.innerHTML = '';
                            });
                            suggestionsContainer.appendChild(suggestionItem);
                        });
                    }
                })
                .catch(error => console.error('Error:', error));
        } else {
            suggestionsContainer.innerHTML = '';
        }
    });
}