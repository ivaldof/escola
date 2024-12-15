<?php
// Incluir a biblioteca FPDF
require('fpdf/fpdf.php'); // Ajuste o caminho conforme necessário

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

// Verifica se um arquivo foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['pdf_file'])) {
    $fileTmpPath = $_FILES['pdf_file']['tmp_name'];
    $fileName = $_FILES['pdf_file']['name'];

    // Verifica se é um arquivo PDF
    if (mime_content_type($fileTmpPath) === 'application/pdf') {
        // Carregar o PDF
        $pdf = new FPDF();
        $pdf->AddPage();
        
        // Tentar extrair texto do PDF
        // Aqui, estamos utilizando um processo simplificado de leitura do conteúdo (para mais precisão, seria necessário usar outra ferramenta como o PDFParser)
        $content = file_get_contents($fileTmpPath); // Extração de conteúdo simples
        
        // Dividir o conteúdo do PDF por linhas ou pontos finais
        $sentences = preg_split('/(\.|\n)/', $content, -1, PREG_SPLIT_NO_EMPTY);

        // Insere as sentenças no banco de dados
        foreach ($sentences as $sentence) {
            $sentence = trim($sentence); // Remove espaços extras
            if (!empty($sentence)) {
                $stmt = $conn->prepare("INSERT INTO pdf_data (sentence) VALUES (?)");
                $stmt->bind_param("s", $sentence);
                $stmt->execute();
            }
        }

        echo "Arquivo PDF importado com sucesso!";
    } else {
        echo "Por favor, envie um arquivo PDF válido.";
    }
} else {
    echo "Nenhum arquivo enviado.";
}

// Fecha a conexão
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Importar PDF</title>
</head>
<body>
    <h1>Importar PDF para o Banco de Dados</h1>
    <form action="importar_pdf.php" method="post" enctype="multipart/form-data">
        <label for="pdf_file">Selecione um arquivo PDF:</label>
        <input type="file" name="pdf_file" id="pdf_file" accept="application/pdf">
        <button type="submit">Importar</button>
    </form>
</body>
</html>
