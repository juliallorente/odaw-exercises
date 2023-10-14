<?php

// Suponha que esta seja a senha que o usuário escolheu ao se registrar.
$senhaOriginal = "minhaSenha123";

// Ciframos a senha.
$senhaCifrada = password_hash($senhaOriginal, PASSWORD_DEFAULT);

// Imprimimos a senha cifrada para visualização.
echo "Senha Cifrada: " . $senhaCifrada . "<br>";

// Suponha que esta seja a senha que o usuário inseriu ao tentar fazer login.
$senhaInserida = "minhaSenha123";

// Verificamos se a senha inserida corresponde à senha cifrada.
if (password_verify($senhaInserida, $senhaCifrada)) {
    echo "Senha correta!";
} else {
    echo "Senha incorreta!";
}

?>
