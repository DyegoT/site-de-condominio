<?php
// Inclui o arquivo de conexão com o banco de dados
include 'config.php';

// Verifica se a requisição foi feita via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1. Receber os dados do formulário, incluindo o ID do morador
    $morador_id = $_POST['id'];
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $rg = $_POST['rg'];
    $apartamento = $_POST['apartamento'];
    $bloco = $_POST['bloco'];
    $email = $_POST['email'];

    // 2. Atualizar os dados do morador na tabela 'moradores'
    $sql_morador = "UPDATE moradores SET nome=?, cpf=?, rg=?, apartamento=?, bloco=?, email=? WHERE id=?";
    $stmt_morador = $conn->prepare($sql_morador);
    if ($stmt_morador === false) {
        die("Erro na preparação do statement: " . $conn->error);
    }
    $stmt_morador->bind_param("ssssssi", $nome, $cpf, $rg, $apartamento, $bloco, $email, $morador_id);
    $stmt_morador->execute();
    $stmt_morador->close();

    // 3. Lógica para atualizar a tabela 'pets'
    $possui_pet = isset($_POST['possui-pet']);
    $nome_pet = $_POST['nome-pet'] ?? '';
    $raca_pet = $_POST['raca-pet'] ?? '';
    
    // Verifica se já existe um pet para este morador
    $sql_check_pet = "SELECT id FROM pets WHERE morador_id = ?";
    $stmt_check_pet = $conn->prepare($sql_check_pet);
    if ($stmt_check_pet === false) {
        die("Erro na preparação do statement: " . $conn->error);
    }
    $stmt_check_pet->bind_param("i", $morador_id);
    $stmt_check_pet->execute();
    $result_check_pet = $stmt_check_pet->get_result();

    if ($possui_pet) {
        if ($result_check_pet->num_rows > 0) {
            // Se já existe, atualiza o pet
            $sql_pet = "UPDATE pets SET nome_pet=?, raca_pet=? WHERE morador_id=?";
            $stmt_pet = $conn->prepare($sql_pet);
            if ($stmt_pet === false) {
                die("Erro na preparação do statement: " . $conn->error);
            }
            $stmt_pet->bind_param("ssi", $nome_pet, $raca_pet, $morador_id);
            $stmt_pet->execute();
            $stmt_pet->close();
        } else {
            // Se não existe, insere um novo pet
            $sql_pet = "INSERT INTO pets (morador_id, nome_pet, raca_pet) VALUES (?, ?, ?)";
            $stmt_pet = $conn->prepare($sql_pet);
            if ($stmt_pet === false) {
                die("Erro na preparação do statement: " . $conn->error);
            }
            $stmt_pet->bind_param("iss", $morador_id, $nome_pet, $raca_pet);
            $stmt_pet->execute();
            $stmt_pet->close();
        }
    } elseif ($result_check_pet->num_rows > 0) {
        // Se o checkbox foi desmarcado, exclui o pet
        $sql_delete_pet = "DELETE FROM pets WHERE morador_id = ?";
        $stmt_delete_pet = $conn->prepare($sql_delete_pet);
        if ($stmt_delete_pet === false) {
            die("Erro na preparação do statement: " . $conn->error);
        }
        $stmt_delete_pet->bind_param("i", $morador_id);
        $stmt_delete_pet->execute();
        $stmt_delete_pet->close();
    }
    $stmt_check_pet->close();

    // 4. Lógica para atualizar a tabela 'veiculos' (implementação similar à de pets)
    $possui_veiculo = isset($_POST['possui-veiculo']);
    $descricao_veiculo = $_POST['descricao-veiculo'] ?? '';
    $placa_veiculo = $_POST['placa-veiculo'] ?? '';
    $modelo_veiculo = $_POST['modelo-veiculo'] ?? '';
    $cor_veiculo = $_POST['cor-veiculo'] ?? '';

    // Verifica se já existe um veículo para este morador
    $sql_check_veiculo = "SELECT id FROM veiculos WHERE morador_id = ?";
    $stmt_check_veiculo = $conn->prepare($sql_check_veiculo);
    if ($stmt_check_veiculo === false) {
        die("Erro na preparação do statement: " . $conn->error);
    }
    $stmt_check_veiculo->bind_param("i", $morador_id);
    $stmt_check_veiculo->execute();
    $result_check_veiculo = $stmt_check_veiculo->get_result();
    
    if ($possui_veiculo) {
        if ($result_check_veiculo->num_rows > 0) {
            // Se já existe, atualiza o veículo
            $sql_veiculo = "UPDATE veiculos SET descricao_veiculo=?, placa_veiculo=?, modelo_veiculo=?, cor_veiculo=? WHERE morador_id=?";
            $stmt_veiculo = $conn->prepare($sql_veiculo);
            if ($stmt_veiculo === false) {
                die("Erro na preparação do statement: " . $conn->error);
            }
            $stmt_veiculo->bind_param("ssssi", $descricao_veiculo, $placa_veiculo, $modelo_veiculo, $cor_veiculo, $morador_id);
            $stmt_veiculo->execute();
            $stmt_veiculo->close();
        } else {
            // Se não existe, insere um novo veículo
            $sql_veiculo = "INSERT INTO veiculos (morador_id, descricao_veiculo, placa_veiculo, modelo_veiculo, cor_veiculo) VALUES (?, ?, ?, ?, ?)";
            $stmt_veiculo = $conn->prepare($sql_veiculo);
            if ($stmt_veiculo === false) {
                die("Erro na preparação do statement: " . $conn->error);
            }
            $stmt_veiculo->bind_param("issss", $morador_id, $descricao_veiculo, $placa_veiculo, $modelo_veiculo, $cor_veiculo);
            $stmt_veiculo->execute();
            $stmt_veiculo->close();
        }
    } elseif ($result_check_veiculo->num_rows > 0) {
        // Se o checkbox foi desmarcado, exclui o veículo
        $sql_delete_veiculo = "DELETE FROM veiculos WHERE morador_id = ?";
        $stmt_delete_veiculo = $conn->prepare($sql_delete_veiculo);
        if ($stmt_delete_veiculo === false) {
            die("Erro na preparação do statement: " . $conn->error);
        }
        $stmt_delete_veiculo->bind_param("i", $morador_id);
        $stmt_delete_veiculo->execute();
        $stmt_delete_veiculo->close();
    }
    $stmt_check_veiculo->close();
    
    // A lógica para atualização de documentos (upload de arquivos) é mais complexa e
    // exigiria funções para mover arquivos temporários e atualizar os caminhos no banco de dados.
    // É importante notar que o tratamento de arquivos precisa ser mais robusto.

    // 5. Fecha a conexão
    $conn->close();

    // 6. Redireciona de volta para a página de administração após a atualização
    header("Location: administracao.php");
    exit();
} else {
    // Se a requisição não for POST, redireciona para a página de administração
    header("Location: administracao.php");
    exit();
}
?>