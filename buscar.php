<?php
// Configuração do banco de dados
$host = "localhost";           // Host padrão do MySQL
$user = "u792028022_teste";         // Substitua pelo usuário do banco
$password = "Dc99398896";       // Substitua pela senha
$database = "u792028022_teste";       // Substitua pelo nome do banco

// Conexão com o banco de dados
$conn = new mysqli($host, $user, $password, $database);

// Verifica conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Obtém o termo de busca da URL
$query = $_GET['query'] ?? ''; // Captura o parâmetro 'query' da URL
$query = $conn->real_escape_string($query); // Evita SQL Injection

// Consulta ao banco de dados
$sql = "SELECT sentence FROM pdf_data WHERE sentence LIKE '%$query%'";
$result = $conn->query($sql);

// Exibe resultados
echo "<!DOCTYPE html>
<html lang='pt-BR'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Resultados da Busca</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { color: #333; }
        p { margin: 10px 0; }
    </style>
</head>
<body>
    <h1>Resultados da Busca</h1>";

if ($query) {
    echo "<p>Você buscou por: <strong>" . htmlspecialchars($query) . "</strong></p>";
}

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<p>" . htmlspecialchars($row['sentence']) . "</p>";
    }
} else {
    echo "<p>Nenhum resultado encontrado para sua busca.</p>";
}

echo "</body>
</html>";

// Fecha conexão com o banco
$conn->close();
?>
