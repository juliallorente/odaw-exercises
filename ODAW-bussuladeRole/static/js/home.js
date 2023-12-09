
function abrirModal(idModal) {
  var modal = document.getElementById(idModal);
  modal.style.display = "block";
}

function fecharModal(idModal) {
  var modal = document.getElementById(idModal);
  modal.style.display = "none";
}

// Fechar o modal se o usuário clicar fora da área do modal
window.onclick = function(event) {
  if (event.target.className === 'modal') {
      event.target.style.display = "none";
  }
}

document.getElementById('formCriarRole').addEventListener('submit', function(e) {
  e.preventDefault();

  var formData = new FormData(this);

  fetch('/add-role', {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    console.log(data.message);
    alert(data.message); // Exibe a mensagem de sucesso
    // Fechar o modal e atualizar a página ou adicionar o novo rolê à lista
    fecharModal('criarRoleModal');
    window.location.reload(); // Recarrega a página

  })
  .catch(error => {
    console.error('Erro:', error);
    alert('Ocorreu um erro ao tentar criar o rolê.'); // Mensagem de erro
  });
});

//editar role
document.querySelectorAll('.edit-role-button').forEach(button => {
  button.addEventListener('click', function() {
      const roleId = this.dataset.roleId;
      // Busque os dados do rolê usando roleId e preencha o formulário
      abrirModal('editarRoleModal');
      // ... Código para preencher o formulário ...
  });
});


document.addEventListener('DOMContentLoaded', function() {
  // Configura os event listeners dos botões de presença
  const confirmButtons = document.querySelectorAll('.confirm-presence-button');
  confirmButtons.forEach(button => {
    button.addEventListener('click', function(e) {
      e.preventDefault();
      const roleId = this.getAttribute('data-role-id');
      const isConfirmed = this.getAttribute('data-confirmed') === 'true';
      togglePresence(roleId, isConfirmed);
    });
  });

  // Verifica a presença atual e atualiza os botões
  verificarPresencaAtualizarBotoes();
});

function togglePresence(roleId, isConfirmed) {
  const url = isConfirmed ? `/desconfirmar-presenca/${roleId}` : `/confirm-presence/${roleId}`;
  fetch(url, { method: 'POST' })
    .then(response => response.json())
    .then(data => {
      alert(data.message);
      const button = document.querySelector(`[data-role-id="${roleId}"]`);
      if (button) {
        button.textContent = isConfirmed ? "Confirmar Presença" : "Desconfirmar Presença";
        button.setAttribute('data-confirmed', !isConfirmed);
      }
    })
    .catch(error => console.error('Erro:', error));
}

function verificarPresencaAtualizarBotoes() {
  fetch('/verificar-presenca')
    .then(response => response.json())
    .then(data => {
      for (const [roleId, isConfirmed] of Object.entries(data)) {
        const button = document.querySelector(`[data-role-id="${roleId}"]`);
        if (button) {
          button.textContent = isConfirmed ? "Desconfirmar Presença" : "Confirmar Presença";
          button.setAttribute('data-confirmed', isConfirmed);
        }
      }
    })
    .catch(error => console.error('Erro:', error));
}


function carregarRolesConfirmados() {
  fetch('/get-roles-confirmados')
  .then(response => response.json())
  .then(data => {
    console.log(data);  // Verifique os dados recebidos
    const lista = document.getElementById('listaRolesConfirmados');
    lista.innerHTML = ''; // Limpa a lista atual
    data.rolesConfirmados.forEach(role => {
      const item = document.createElement('li');
      item.textContent = role.nome; // Supondo que 'nome' é um campo do seu documento
      lista.appendChild(item);
    });
  })
  .catch(error => console.error('Erro:', error));
}

function editarPerfil() {
  document.getElementById('infoUsuario').style.display = 'none';
  document.getElementById('formEditarPerfil').style.display = 'block';
}

function salvarAlteracoes() {
  var nome = document.getElementById('nomeUsuario').value;
  var email = document.getElementById('emailUsuario').value;

  fetch('/editar-perfil', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: `nome=${encodeURIComponent(nome)}&email=${encodeURIComponent(email)}`
  })
  .then(response => response.json())
  .then(data => {
    alert(data.message);

    // Atualizar as informações no modal
    var infoUsuario = document.getElementById('infoUsuario');
    infoUsuario.querySelector('p:nth-child(1)').textContent = `Nome: ${nome}`;
    infoUsuario.querySelector('p:nth-child(2)').textContent = `Email: ${email}`;

    // Alternar visualizações
    document.getElementById('formEditarPerfil').style.display = 'none';
    infoUsuario.style.display = 'block';
  })
  .catch(error => console.error('Erro:', error));
}

document.addEventListener('DOMContentLoaded', function() {
  const cards = document.querySelectorAll('.card');
  const rolesContainer = document.querySelector('.principal-containt[data-tipo="role"]');
  const usersContainer = document.querySelector('.principal-containt[data-tipo="usuario"]');
  rolesContainer.style.display = 'block';
  usersContainer.style.display = 'none';

  // Função para mostrar ou ocultar os elementos com base no filtro
  function filterElements(attribute, value) {
    cards.forEach(card => {
      const hasAttribute = card.getAttribute(attribute);
      card.style.display = hasAttribute === value ? 'block' : 'none';
    });
  }

  // Adiciona ouvintes de eventos para itens de dropdown
  document.getElementById('filtro-todos').addEventListener('click', function() {
    cards.forEach(card => card.style.display = 'block');
    rolesContainer.style.display = 'block';
    usersContainer.style.display = 'none';
  });

  document.getElementById('filtro-usuario').addEventListener('click', function() {
    cards.forEach(card => card.style.display = 'block');
    usersContainer.style.display = 'block';
    rolesContainer.style.display = 'none';
  });

  // Ouvintes para filtros específicos dos rolês
  document.getElementById('filtro-promocao').addEventListener('click', function() {
    filterElements('data-promocao', 'true');
  });

  document.getElementById('filtro-happy-hour').addEventListener('click', function() {
    filterElements('data-happy-hour', 'true');
  });

  document.getElementById('filtro-musica').addEventListener('click', function() {
    filterElements('data-tipo-musica', 'true');
  });
});

//SEGUIR AMIGOS
document.addEventListener('DOMContentLoaded', function() {
  // Configura os event listeners dos botões de presença
  const confirmButtons = document.querySelectorAll('.follow-button');
  confirmButtons.forEach(button => {
    button.addEventListener('click', function(e) {
      e.preventDefault();
      const userId = this.getAttribute('data-user-id');
      const isConfirmed = this.getAttribute('data-confirmed') === 'true';
      toggleFollow(userId, isConfirmed);
    });
  });

  // Verifica a presença atual e atualiza os botões
  verificarFollowAtualizarBotoes();
});

function toggleFollow(userId, isConfirmed) {
  const url = isConfirmed ? `/unfollow-user/${userId}` : `/follow-user/${userId}`;
  fetch(url, { method: 'POST' })
    .then(response => response.json())
    .then(data => {
      if (data.message === "Seguindo Usuario" || data.message === "Você deixou de seguir este usuario") {
        const button = document.querySelector(`[data-user-id="${userId}"]`);
        if (button) {
          button.textContent = isConfirmed ? "Seguir" : "Deixar de Seguir";
          button.setAttribute('data-confirmed', !isConfirmed);
        }
      }
      alert(data.message);
    })
    .catch(error => console.error('Erro:', error));
}


function verificarFollowAtualizarBotoes() {
  fetch('/verificar-follow')
    .then(response => response.json())
    .then(data => {
      for (const [userId, isConfirmed] of Object.entries(data)) {
        const button = document.querySelector(`[data-user-id="${userId}"]`);
        if (button) {
          button.textContent = isConfirmed ? "Deixar de Seguir" : "Seguir";
          button.setAttribute('data-confirmed', isConfirmed);
        }
      }
    })
    .catch(error => console.error('Erro:', error));
}

//teste popover
const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]')
const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl))

//convidar amigos
