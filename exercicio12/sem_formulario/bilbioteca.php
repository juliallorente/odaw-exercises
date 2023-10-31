<?php

///opt/lampp/htdocs
// code biblioteca.php
//sudo mv cadastro.php /opt/lampp/htdocs/


$servername = "localhost";
$username = "root";
$password = "julia2001";
$dbname = "biblioteca";

$conn = new mysqli('localhost', 'root', 'julia2001', 'biblioteca');

// Verificar conexão
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
} else {
    echo "Conectado com sucesso!";
}

$conn->close();


function inserirLivro($titulo, $autor, $genero, $anoPublicacao, $isbn, $preco) {
    global $conn;
    $sql = "INSERT INTO livros (Titulo, Autor, Genero, AnoPublicacao, ISBN, Preco) VALUES ('$titulo', '$autor', '$genero', $anoPublicacao, '$isbn', $preco)";
    if ($conn->query($sql) === TRUE) {
        echo "Novo registro inserido com sucesso!";
    } else {
        echo "Erro: " . $sql . "<br>" . $conn->error;
    }
}

inserirLivro("O Sol é para todos", "Harper Lee", "Romance", 1960, "4567890123456", 20.99);

function alterarPreco($id, $novoPreco) {
    global $conn;
    $sql = "UPDATE livros SET Preco = $novoPreco WHERE ID = $id";
    if ($conn->query($sql) === TRUE) {
        echo "Registro atualizado com sucesso!";
    } else {
        echo "Erro: " . $sql . "<br>" . $conn->error;
    }
}

alterarPreco(1, 25.99);

function visualizarLivros() {
    global $conn;
    $sql = "SELECT * FROM livros";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "ID: " . $row["ID"]. " - Título: " . $row["Titulo"]. " - Autor: " . $row["Autor"]. "<br>";
        }
    } else {
        echo "0 resultados";
    }
}

visualizarLivros();

function apagarLivro($id) {
    global $conn;
    $sql = "DELETE FROM livros WHERE ID = $id";
    if ($conn->query($sql) === TRUE) {
        echo "Registro apagado com sucesso!";
    } else {
        echo "Erro: " . $sql . "<br>" . $conn->error;
    }
}

apagarLivro(1);

$conn->close();

?>
