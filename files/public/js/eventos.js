document.addEventListener("DOMContentLoaded", function () {
    fetchEventos();
});

function fetchEventos() {
    fetch("fetchEventos.php") // Endpoint PHP para buscar os eventos
        .then(response => response.json())
        .then(data => {
            const eventosContainer = document.getElementById("eventos-lista");
            eventosContainer.innerHTML = ""; // Limpa antes de preencher

            data.forEach(evento => {
                const eventoDiv = document.createElement("div");
                eventoDiv.classList.add("evento-card");

                // Criando o elemento de imagem (se houver capa)
                let imagemCapa = evento.imagem_capa
                    ? `<img src="${evento.imagem_capa}" alt="${evento.titulo}" class="evento-imagem">`
                    : `<img src="/public/uploads/default_evento.jpg" alt="Evento sem capa" class="evento-imagem">`;

                eventoDiv.innerHTML = `
                    ${imagemCapa}
                    <h3>${evento.titulo}</h3>
                    <p><strong>Data:</strong> ${evento.data_inicio} às ${evento.horario_inicio}</p>
                    <p><strong>Local:</strong> ${evento.local || "Não informado"}</p>
                    <a href="evento.php?id=${evento.id}" class="btn">Ver Detalhes</a>
                `;

                eventosContainer.appendChild(eventoDiv);
            });
        })
        .catch(error => console.error("Erro ao buscar eventos:", error));
}
