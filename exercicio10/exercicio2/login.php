<?php

// Caminho do arquivo de autenticação
$arquivo = 'autenticacao.txt';

// Se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    if (validarCredenciais($arquivo, $usuario, $senha)) {
        echo "Login bem-sucedido!";
        // Redirecionar para uma página protegida, iniciar sessão, etc.
    } else {
        echo "Usuário ou senha incorretos.";
    }
}

function validarCredenciais($arquivo, $usuario, $senha) {
    if (!file_exists($arquivo)) {
        return false;
    }

    $linhas = file($arquivo);
    foreach ($linhas as $linha) {
        list($usuarioArquivo, $senhaArquivo) = explode(" ", trim($linha));

        if ($usuarioArquivo == $usuario && $senhaArquivo == $senha) {
            return true;
        }
    }

    return false;
}

?>

<form action="" method="post">
    Usuário: <input type="text" name="usuario" required><br>
    Senha: <input type="password" name="senha" required><br>
    <input type="submit" value="Entrar">
</form>
