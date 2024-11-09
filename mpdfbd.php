<?php
require 'vendor/autoload.php'; // Carrega a biblioteca mPDF

// Dados de conexão com o banco de dados
$host = 'localhost';
$dbname = 'biblioteca';
$username = 'root';
$password = '';

// Conexão com o banco de dados usando PDO
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta SQL para buscar informações dos livros
    $query = "SELECT titulo, autor, ano_publicado, resumo FROM livros";
    $stmt = $pdo->prepare($query);
    $stmt->execute();

    // Recupera os dados do livro
    $livros = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Cria uma instância do mPDF
    $mpdf = new \Mpdf\Mpdf();

    // Configura o conteúdo do PDF
    $html = '<h1>Biblioteca - Lista de livros</h1>';
    $html .= '<table border="1" cellpadding="10" cellspacing="0" width="100%">';
    $html .= '<tr><th>Título</th><th>Autor</th><th>Ano de Publicação</th><th>Resumo</th></tr>';

    // Popula o HTML com os dados dos livros
    foreach ($livros as $livro) {
        $html .= '<tr>';
        $html .= '<td>' . htmlspecialchars($livro['titulo']) . '</td>';
        $html .= '<td>' . htmlspecialchars($livro['autor']) . '</td>';
        $html .= '<td>' . htmlspecialchars($livro['ano_publicado']) . '</td>';
        $html .= '<td>' . htmlspecialchars($livro['resumo']) . '</td>';
        $html .= '</tr>';
    }

    $html .= '</table>';

    // Escreve o conteúdo HTML no PDF
    $mpdf->WriteHTML($html);

    // Gera o PDF e força o download
    $mpdf->Output('lista_de_livros.pdf', \Mpdf\Output\Destination::DOWNLOAD);

} catch (PDOException $e) {
    echo "Erro na conexão com o banco de dados: " . $e->getMessage();
} catch (\Mpdf\MpdfException $e) {
    echo "Erro ao gerar o PDF: " . $e->getMessage();
}
?>
