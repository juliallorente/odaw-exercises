<?php
// Configura o fuso horário para obter a hora local
date_default_timezone_set('America/Sao_Paulo');

// Obtém a data atual no formato d/m/Y
$data_atual = date('d/m/Y');

// Obtém a hora atual no formato H:i
$hora_atual = date('H:i');

// Monta a string de saída formatada
$saida = "Hoje é $data_atual e agora são $hora_atual";

// Exibe a saída formatada
echo "<h2>Saída:</h2>";
echo "<p>$saida</p>";

// Função para exibir a data e hora formatadas
function exibirDataHora() {
    date_default_timezone_set('America/Sao_Paulo');
    $data_atual = date('d/m/Y');
    $hora_atual = date('H:i');
    return "Hoje é $data_atual e agora são $hora_atual";
}

function manipularNomes($nomes, $letra) {
    // Filtra os nomes que contêm a letra específica
    $nomesFiltrados = array_filter($nomes, function ($nome) use ($letra) {
        return stripos($nome, $letra) !== false;
    });

    // Transforma os nomes em maiúsculas
    $nomesMaiusculos = array_map('strtoupper', $nomesFiltrados);

    // Ordena os nomes em ordem alfabética inversa
    arsort($nomesMaiusculos);

    // Concatena os nomes em uma única string separada por vírgulas
    $nomesConcatenados = implode(', ', $nomesMaiusculos);

    return "Nomes que contêm '$letra' (em ordem alfabética inversa): $nomesConcatenados";
}

// Lista de nomes
$nomes = ["Alice", "Bob", "Carlos", "David"];

// Inverte a ordem dos nomes
$nomes_invertidos = array_reverse($nomes);

// Exibe os nomes invertidos
echo "<h2>Nomes Invertidos:</h2>";
echo "<p>" . implode(", ", $nomes_invertidos) . "</p>";

// Caminho do arquivo que armazena o contador
$contador_arquivo = 'contador.txt';

// Verifica se o arquivo existe
if (file_exists($contador_arquivo)) {
    // Lê o valor atual do contador no arquivo
    $contador = file_get_contents($contador_arquivo);
    // Incrementa o contador
    $contador++;
} else {
    // Inicia o contador se o arquivo não existir
    $contador = 1;
}

// Atualiza o arquivo com o novo valor do contador
file_put_contents($contador_arquivo, $contador);

// Exibe o contador de visitas formatado
echo "<h2>Contador de Visitas:</h2>";
echo "<p>Esta página foi visitada $contador vezes.</p>";

session_start();

if (isset($_SESSION['contador'])) {
    $_SESSION['contador']++;
} else {
    $_SESSION['contador'] = 1;
}

// Exibe o contador de visitas durante a sessão formatado
echo "<h2>Contador de Visitas durante a Sessão:</h2>";
echo "<p>Você visitou esta página " . $_SESSION['contador'] . " vezes durante esta sessão.</p>";
?>
