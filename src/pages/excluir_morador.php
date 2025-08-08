<?php
// Inclui o arquivo de conexão com o banco de dados
include 'config.php';

// Verifica se o ID do morador foi passado via URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    // Sanitiza o ID para evitar ataques de injeção SQL
    $morador_id = $_GET['id'];

    // Prepara a query SQL para excluir o morador
    // A cláusula FOREIGN KEY (ON DELETE CASCADE) nas tabelas pets, veiculos e documentos
    // garante que os registros relacionados serão excluídos automaticamente.
    $sql = "DELETE FROM moradores WHERE id = ?";
    
    // Usa prepared statements para segurança
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $morador_id);

    // Executa a query
    if ($stmt->execute()) {
        // As mensagens de echo não serão exibidas devido ao redirecionamento,
        // mas são úteis para depuração.
        // echo "Morador excluído com sucesso!";
    } else {
        // echo "Erro ao excluir o morador: " . $stmt->error;
    }

    // Fecha o statement
    $stmt->close();
}
// Fecha a conexão com o banco de dados
$conn->close();

// Redireciona o usuário de volta para a página de administração após a exclusão.
// Esta é a correção necessária, pois a página de listagem é administracao.php.
header("Location: administracao.php");
exit();
?>