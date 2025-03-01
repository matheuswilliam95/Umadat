function initAutocomplete() {
    var input = document.getElementById('local');
    var suggestionsContainer = document.getElementById('suggestions');
    var geocoder = L.Control.Geocoder.nominatim();
    input.addEventListener('input', function () {
        var query = input.value;
        if (query.length > 2) {
            geocoder.geocode(query, function (results) {
                suggestionsContainer.innerHTML = '';
                if (results.length > 0) {
                    results.forEach(function (result) {
                        var suggestionItem = document.createElement('div');
                        suggestionItem.className = 'suggestion-item';
                        suggestionItem.textContent = result.name;
                        suggestionItem.addEventListener('click', function () {
                            input.value = result.name;
                            suggestionsContainer.innerHTML = '';
                        });
                        suggestionsContainer.appendChild(suggestionItem);
                    });
                }
            });
        } else {
            suggestionsContainer.innerHTML = '';
        }
    });
}