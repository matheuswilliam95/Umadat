document.addEventListener("DOMContentLoaded", function () {
    const inscreverBtn = document.getElementById("inscricao-btn");
    if (inscreverBtn) {
        inscreverBtn.addEventListener("click", function () {
            const eventoId = this.getAttribute("data-evento-id");
            const action = this.getAttribute("data-action");
            if (action === 'login') {
                const currentUrl = encodeURIComponent(window.location.href);
                window.location.href = "<?php echo PASTA_BASE; ?>pages/login.php?redirect=" + currentUrl;
            } else {
                fetch("inscrever.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: `evento_id=${eventoId}&action=${action}`
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.message);
                            if (action === 'inscrever') {
                                inscreverBtn.textContent = 'Cancelar Inscrição';
                                inscreverBtn.setAttribute('data-action', 'cancelar');
                                inscreverBtn.classList.add('cancelar');
                            } else {
                                inscreverBtn.textContent = 'Inscrever';
                                inscreverBtn.setAttribute('data-action', 'inscrever');
                                inscreverBtn.classList.remove('cancelar');
                            }
                        } else {
                            alert("Erro: " + data.message);
                        }
                    })
                    .catch(error => {
                        console.error("Erro:", error);
                        alert("Erro ao realizar a ação.");
                    });
            }
        });
    }
}); 