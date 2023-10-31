<?php
$servername = "db";
$username = "root";
$password = "julia2001";
$dbname = "biblioteca";

// Criar conexão
$conn = new mysqli('127.0.0.1', 'root', 'julia2001', 'biblioteca');

// Checar conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Processar formulário de novo cadastro
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    $titulo = $_POST["titulo"];
    $autor = $_POST["autor"];
    $genero = $_POST["genero"];
    $anoPublicacao = $_POST["anoPublicacao"];
    $isbn = $_POST["isbn"];
    $preco = $_POST["preco"];

    $sql = "INSERT INTO livros (Titulo, Autor, Genero, AnoPublicacao, ISBN, Preco) VALUES ('$titulo', '$autor', '$genero', $anoPublicacao, '$isbn', $preco)";

    if ($conn->query($sql) === TRUE) {
        echo "Novo registro inserido com sucesso!";
    } else {
        echo "Erro: " . $sql . "<br>" . $conn->error;
    }
}

// Processar ação de apagar cadastro
if (isset($_GET["delete"])) {
    $idToDelete = $_GET["delete"];
    $sql = "DELETE FROM livros WHERE ID = $idToDelete";

    if ($conn->query($sql) === TRUE) {
        echo "Registro apagado com sucesso!";
    } else {
        echo "Erro: " . $sql . "<br>" . $conn->error;
    }
}

// Processar ação de editar cadastro
if (isset($_GET["edit"])) {
    $idToEdit = $_GET["edit"];
    $sql = "SELECT * FROM livros WHERE ID = $idToEdit";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $tituloEdit = $row["Titulo"];
        $autorEdit = $row["Autor"];
        $generoEdit = $row["Genero"];
        $anoPublicacaoEdit = $row["AnoPublicacao"];
        $isbnEdit = $row["ISBN"];
        $precoEdit = $row["Preco"];
    }
}

// Processar formulário de edição
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update"])) {
    $tituloEdit = $_POST["titulo"];
    $autorEdit = $_POST["autor"];
    $generoEdit = $_POST["genero"];
    $anoPublicacaoEdit = $_POST["anoPublicacao"];
    $isbnEdit = $_POST["isbn"];
    $precoEdit = $_POST["preco"];
    $idToUpdate = $_POST["idToUpdate"];

    $sql = "UPDATE livros SET Titulo = '$tituloEdit', Autor = '$autorEdit', Genero = '$generoEdit', AnoPublicacao = $anoPublicacaoEdit, ISBN = '$isbnEdit', Preco = $precoEdit WHERE ID = $idToUpdate";

    if ($conn->query($sql) === TRUE) {
        echo "Registro atualizado com sucesso!";
    } else {
        echo "Erro: " . $sql . "<br>" . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Cadastro de Livros</title>
</head>
<body>

<h1>Cadastro de Livros</h1>

<!-- Formulário para novo cadastro -->
<h2>Novo Cadastro</h2>
<form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
    Título: <input type="text" name="titulo" required><br>
    Autor: <input type="text" name="autor" required><br>
    Gênero: <input type="text" name="genero" required><br>
    Ano de Publicação: <input type="number" name="anoPublicacao" required><br>
    ISBN: <input type="text" name="isbn" required><br>
    Preço: <input type="number" name="preco" step="0.01" required><br>
    <input type="submit" name="submit" value="Cadastrar">
</form>

<!-- Listar todos os registros -->
<h2>Registros</h2>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Título</th>
        <th>Autor</th>
        <th>Gênero</th>
        <th>Ano de Publicação</th>
        <th>ISBN</th>
        <th>Preço</th>
        <th>Ações</th>
    </tr>
    <?php
    $sql = "SELECT * FROM livros";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["ID"] . "</td>";
            echo "<td>" . $row["Titulo"] . "</td>";
            echo "<td>" . $row["Autor"] . "</td>";
            echo "<td>" . $row["Genero"] . "</td>";
            echo "<td>" . $row["AnoPublicacao"] . "</td>";
            echo "<td>" . $row["ISBN"] . "</td>";
            echo "<td>" . $row["Preco"] . "</td>";
            echo "<td><a href='?delete=" . $row["ID"] . "'>Apagar</a> | <a href='?edit=" . $row["ID"] . "'>Editar</a></td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='8'>Nenhum registro encontrado.</td></tr>";
    }
    ?>
</table>

<!-- Formulário para editar registro -->
<?php if (isset($_GET["edit"])) { ?>
    <h2>Editar Cadastro</h2>
    <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <input type="hidden" name="idToUpdate" value="<?php echo $idToEdit; ?>">
        Título: <input type="text" name="titulo" value="<?php echo $tituloEdit; ?>" required><br>
        Autor: <input type="text" name="autor" value="<?php echo $autorEdit; ?>" required><br>
        Gênero: <input type="text" name="genero" value="<?php echo $generoEdit; ?>" required><br>
        Ano de Publicação: <input type="number" name="anoPublicacao" value="<?php echo $anoPublicacaoEdit; ?>" required><br>
        ISBN: <input type="text" name="isbn" value="<?php echo $isbnEdit; ?>" required><br>
        Preço: <input type="number" name="preco" step="0.01" value="<?php echo $precoEdit; ?>" required><br>
        <input type="submit" name="update" value="Atualizar">
    </form>
<?php } ?>

</body>
</html>
