document.addEventListener("DOMContentLoaded", function () {
    loadUserProfile();
    loadUserEvents();

    document.getElementById("profile-form").addEventListener("submit", async function (event) {
        event.preventDefault();
        await updateUserProfile();
    });

    document.getElementById("password-form").addEventListener("submit", async function (event) {
        event.preventDefault();
        await changeUserPassword();
    });
});

async function loadUserProfile() {
    const response = await fetch("fetchPerfil.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ action: "getProfile" })
    });
    const data = await response.json();
    if (data.success) {
        document.getElementById("nome").value = data.user.nome;
        document.getElementById("telefone").value = data.user.telefone;
        document.getElementById("congregacao").value = data.user.congregacao;
    }
}

async function updateUserProfile() {
    const nome = document.getElementById("nome").value;
    const telefone = document.getElementById("telefone").value;
    const congregacao = document.getElementById("congregacao").value;
    const csrfToken = document.querySelector("input[name='csrf_token']").value;

    const response = await fetch("fetchPerfil.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ action: "updateProfile", nome, telefone, congregacao, csrf_token: csrfToken })
    });
    const data = await response.json();
    alert(data.message);
}

async function changeUserPassword() {
    const currentPassword = document.getElementById("current_password").value;
    const newPassword = document.getElementById("new_password").value;
    const csrfToken = document.querySelector("input[name='csrf_token']").value;

    const response = await fetch("fetchPerfil.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ action: "changePassword", currentPassword, newPassword, csrf_token: csrfToken })
    });
    const data = await response.json();
    alert(data.message);
}

async function loadUserEvents() {
    const response = await fetch("fetchPerfil.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ action: "getUserEvents" })
    });
    const data = await response.json();
    const eventList = document.getElementById("event-list");
    eventList.innerHTML = "";

    if (data.success) {
        data.events.forEach(event => {
            const li = document.createElement("li");
            li.textContent = event.titulo;

            const cancelBtn = document.createElement("button");
            cancelBtn.textContent = "Cancelar";
            cancelBtn.onclick = () => cancelEvent(event.id);

            li.appendChild(cancelBtn);
            eventList.appendChild(li);
        });
    }
}

async function cancelEvent(eventId) {
    const response = await fetch("fetchPerfil.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ action: "cancelEvent", eventId })
    });
    const data = await response.json();
    if (data.success) {
        loadUserEvents();
    } else {
        alert(data.message);
    }
}
