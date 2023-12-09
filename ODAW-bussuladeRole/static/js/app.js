
var btnSignin = document.querySelector("#signin");
var btnSignup = document.querySelector("#signup");

var body = document.querySelector("body");


btnSignin.addEventListener("click", function () {
   body.className = "sign-in-js"; 
});

btnSignup.addEventListener("click", function () {
    body.className = "sign-up-js";
})

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




