<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ... (Seu código existente para dados pessoais)
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $rg = $_POST['rg'];
    $apartamento = $_POST['apartamento'];
    $bloco = $_POST['bloco'];
    $email = $_POST['email'];

    // Inserção na tabela moradores
    $sql_morador = "INSERT INTO moradores (nome, cpf, rg, apartamento, bloco, email) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt_morador = $conn->prepare($sql_morador);
    $stmt_morador->bind_param("ssssss", $nome, $cpf, $rg, $apartamento, $bloco, $email);

    if ($stmt_morador->execute()) {
        $morador_id = $stmt_morador->insert_id;

        // Lógica de upload para a imagem do pet
        $caminho_imagem_pet = NULL; // Inicializa a variável com NULL
        
        if (isset($_FILES['imagem-pet']) && $_FILES['imagem-pet']['error'] === UPLOAD_ERR_OK) {
            $caminho_pasta_pets = __DIR__ . "../../uploads/pets/";
            
            // Cria a pasta se ela não existir
            if (!is_dir($caminho_pasta_pets)) {
                mkdir($caminho_pasta_pets, 0777, true);
            }
            
            $nome_arquivo_original = basename($_FILES['imagem-pet']['name']);
            $extensao = pathinfo($nome_arquivo_original, PATHINFO_EXTENSION);
            
            // Gera um nome único para o arquivo para evitar substituição
            $nome_arquivo_unico = uniqid('pet_') . '.' . $extensao;
            $caminho_arquivo_destino = $caminho_pasta_pets . $nome_arquivo_unico;
            
            // Move o arquivo temporário para a pasta de destino
            if (move_uploaded_file($_FILES['imagem-pet']['tmp_name'], $caminho_arquivo_destino)) {
                // Salva o caminho relativo no banco de dados
                $caminho_imagem_pet = "../../uploads/pets/" . $nome_arquivo_unico;
            } else {
                // Se houver um erro no upload, você pode tratar aqui
                echo "Erro ao mover o arquivo de imagem do pet.";
                exit();
            }
        }

        // Exemplo para pets:
        if (isset($_POST['possui-pet']) && $_POST['possui-pet'] == 'on') {
            $nome_pet = $_POST['nome-pet'];
            $raca_pet = $_POST['raca-pet'];
            
            // Inserção com o caminho da imagem
            $sql_pet = "INSERT INTO pets (morador_id, nome_pet, raca_pet, imagem) VALUES (?, ?, ?, ?)";
            $stmt_pet = $conn->prepare($sql_pet);
            $stmt_pet->bind_param("isss", $morador_id, $nome_pet, $raca_pet, $caminho_imagem_pet);
            $stmt_pet->execute();
        }
        header("Location: administracao.php");
        exit();
    } else {
        echo "Erro: " . $sql_morador . "<br>" . $conn->error;
    }
    
    $stmt_morador->close();
}
$conn->close();
?>