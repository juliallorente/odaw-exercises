<?php

// http://localhost:8099/formulario.php

$erros = [
    "nome" => "",
    "email" => "",
    "senha" => "",
    "comentario" => "",
    "cidade" => "",
    "genero" => ""
];

$valores = [
    "nome" => "",
    "email" => "",
    "senha" => "",
    "comentario" => "",
    "cidade" => "",
    "genero" => "",
    "novidades" => ""
];

$dados_enviados = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $valido = true;

    // Coleta e atribui os valores inseridos aos campos
    foreach ($valores as $chave => $valor) {
        if (isset($_POST[$chave])) {
            $valores[$chave] = $_POST[$chave];
        }
    }

    // Validar Nome
    if (empty($_POST["nome"])) {
        $erros["nome"] = "Nome é obrigatório.";
        $valido = false;
    }

    // Validar E-mail
    if (empty($_POST["email"]) || !filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $erros["email"] = "E-mail inválido ou em branco.";
        $valido = false;
    }

    // Validar Senha
    if (empty($_POST["senha"]) || strlen($_POST["senha"]) < 8) {
        $erros["senha"] = "A senha deve ter pelo menos 8 caracteres.";
        $valido = false;
    }

    // Validar Comentário
    if (empty($_POST["comentario"])) {
        $erros["comentario"] = "O comentário é obrigatório.";
        $valido = false;
    }

    // Validar Cidade
    if (empty($_POST["cidade"])) {
        $erros["cidade"] = "Cidade é obrigatória.";
        $valido = false;
    }

    // Validar Gênero
    if (empty($_POST["genero"])) {
        $erros["genero"] = "Gênero é obrigatório.";
        $valido = false;
    }

    if ($valido) {
        $dados_enviados = true;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Formulário de Exemplo</title>
</head>
<body>

<?php
if ($dados_enviados) {
    echo "<h3>Dados Enviados:</h3>";
    echo "Nome: " . $valores["nome"] . "<br>";
    echo "E-mail: " . $valores["email"] . "<br>";
    echo "Senha: " . $valores["senha"] . "<br>";
    echo "Comentário: " . $valores["comentario"] . "<br>";
    echo "Cidade: " . $valores["cidade"] . "<br>";
    echo "Receber novidades? " . (isset($valores["novidades"]) ? "Sim" : "Não") . "<br>";
    echo "Gênero: " . $valores["genero"] . "<br><hr>";
}
?>

<h2>Formulário de Exemplo</h2>
<form action="" method="post">
    Nome: <input type="text" name="nome" value="<?php echo htmlspecialchars($valores['nome']); ?>">
    <span style="color:red;"><?php echo $erros["nome"]; ?></span><br><br>

    E-mail: <input type="email" name="email" value="<?php echo htmlspecialchars($valores['email']); ?>">
    <span style="color:red;"><?php echo $erros["email"]; ?></span><br><br>

    Senha: <input type="password" name="senha" value="<?php echo htmlspecialchars($valores['senha']); ?>">
    <span style="color:red;"><?php echo $erros["senha"]; ?></span><br><br>

    Comentário: <textarea name="comentario" rows="5" cols="40"><?php echo htmlspecialchars($valores['comentario']); ?></textarea>
    <span style="color:red;"><?php echo $erros["comentario"]; ?></span><br><br>

    Cidade:
    <select name="cidade">
        <option value="">Selecione uma cidade</option>
        <option value="São Paulo" <?php echo $valores['cidade'] == 'São Paulo' ? 'selected' : ''; ?>>São Paulo</option>
        <option value="Rio de Janeiro" <?php echo $valores['cidade'] == 'Rio de Janeiro' ? 'selected' : ''; ?>>Rio de Janeiro</option>
        <option value="Belo Horizonte" <?php echo $valores['cidade'] == 'Belo Horizonte' ? 'selected' : ''; ?>>Belo Horizonte</option>
    </select>
    <span style="color:red;"><?php echo $erros["cidade"]; ?></span><br><br>

    Receber novidades? <input type="checkbox" name="novidades" value="sim" <?php echo $valores['novidades'] == 'sim' ? 'checked' : ''; ?>><br><br>
    
    Gênero:
    <input type="radio" name="genero" value="Feminino" <?php echo $valores['genero'] == 'Feminino' ? 'checked' : ''; ?>> Feminino
    <input type="radio" name="genero" value="Masculino" <?php echo $valores['genero'] == 'Masculino' ? 'checked' : ''; ?>> Masculino
    <span style="color:red;"><?php echo $erros["genero"]; ?></span><br><br>

    <input type="submit" value="Enviar">
    <input type="reset" value="Limpar">
</form>

</body>
</html>
