<?php
// Inclui o arquivo de conexão com o banco de dados
include 'config.php';

// Variáveis para armazenar os dados do morador, pet, veiculo e documentos
$morador_data = null;
$pet_data = null;
$veiculo_data = null;
$documento_data = null;

// Verifica se o ID do morador foi passado via URL (Método GET)
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $morador_id = $_GET['id'];

    // Prepara a query para buscar os dados do morador principal
    $sql_morador = "SELECT * FROM moradores WHERE id = ?";
    $stmt_morador = $conn->prepare($sql_morador);
    // 'i' indica que o parâmetro é um inteiro (ID)
    $stmt_morador->bind_param("i", $morador_id);
    $stmt_morador->execute();
    $result_morador = $stmt_morador->get_result();

    if ($result_morador->num_rows > 0) {
        $morador_data = $result_morador->fetch_assoc();
        
        // Prepara a query para buscar os dados do pet (se existirem)
        $sql_pet = "SELECT * FROM pets WHERE morador_id = ?";
        $stmt_pet = $conn->prepare($sql_pet);
        $stmt_pet->bind_param("i", $morador_id);
        $stmt_pet->execute();
        $result_pet = $stmt_pet->get_result();
        $pet_data = $result_pet->fetch_assoc(); // Retorna null se não encontrar
        $stmt_pet->close();

        // Prepara a query para buscar os dados do veículo (se existirem)
        $sql_veiculo = "SELECT * FROM veiculos WHERE morador_id = ?";
        $stmt_veiculo = $conn->prepare($sql_veiculo);
        $stmt_veiculo->bind_param("i", $morador_id);
        $stmt_veiculo->execute();
        $result_veiculo = $stmt_veiculo->get_result();
        $veiculo_data = $result_veiculo->fetch_assoc(); // Retorna null se não encontrar
        $stmt_veiculo->close();

        // Prepara a query para buscar os dados dos documentos (se existirem)
        $sql_documento = "SELECT * FROM documentos WHERE morador_id = ?";
        $stmt_documento = $conn->prepare($sql_documento);
        $stmt_documento->bind_param("i", $morador_id);
        $stmt_documento->execute();
        $result_documento = $stmt_documento->get_result();
        $documento_data = $result_documento->fetch_assoc(); // Retorna null se não encontrar
        $stmt_documento->close();

    } else {
        echo "Morador não encontrado.";
        exit();
    }
 
    $stmt_morador->close();
} else {
    echo "ID do morador não fornecido.";
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Morador</title>
    <link rel="stylesheet" href="../assets/css/pages/adminstracao/administracao.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
        <div class="header-content">
            <nav class="main-nav-desktop">
                <ul>
                    </ul>
            </nav>
        </div>
    </header>

    <div class="container">
        <aside class="sidebar">
            <nav>
                <ul>
                    <li>
                        <span class="sidebar-title"><i class="fas fa-cog"></i> Administração</span>
                        <ul class="nested-menu">
                            <li><a href="administracao.php"><i class="fas fa-users"></i> Voltar para Moradores</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </aside>
        
        <main class="content">
            <section id="moradores" class="content-section active">
                <div class="residents-panel">
                    <div class="panel-header">
                        <h2>Editar Morador</h2>
                    </div>
                    <div class="popup-content">
                        <h3>Editar Dados do Morador</h3>
                        <div class="popup-tabs">
                            <button class="tab-button active" data-tab="dados-pessoais">Dados Pessoais</button>
                            <button class="tab-button" data-tab="dados-auxiliares">Dados Auxiliares</button>
                            <button class="tab-button" data-tab="documentos">Documentos</button>
                        </div>
                        
                        <form id="form-edicao-morador" action="atualizar_morador.php" method="POST">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($morador_data['id']); ?>">

                            <div id="dados-pessoais" class="tab-content active">
                                <label for="nome-cadastro">Nome:</label>
                                <input type="text" id="nome-cadastro" name="nome" value="<?php echo htmlspecialchars($morador_data['nome'] ?? ''); ?>" required>

                                <label for="cpf-cadastro">CPF:</label>
                                <input type="text" id="cpf-cadastro" name="cpf" value="<?php echo htmlspecialchars($morador_data['cpf'] ?? ''); ?>" required>

                                <label for="rg-cadastro">RG:</label>
                                <input type="text" id="rg-cadastro" name="rg" value="<?php echo htmlspecialchars($morador_data['rg'] ?? ''); ?>" required>

                                <label for="apto-cadastro">Número do Apartamento:</label>
                                <input type="text" id="apto-cadastro" name="apartamento" value="<?php echo htmlspecialchars($morador_data['apartamento'] ?? ''); ?>" required>

                                <label for="bloco-cadastro">Bloco:</label>
                                <input type="text" id="bloco-cadastro" name="bloco" value="<?php echo htmlspecialchars($morador_data['bloco'] ?? ''); ?>" required>

                                <label for="email-cadastro">E-mail:</label>
                                <input type="email" id="email-cadastro" name="email" value="<?php echo htmlspecialchars($morador_data['email'] ?? ''); ?>" required>
                            </div>

                            <div id="dados-auxiliares" class="tab-content">
                                <div class="checkbox-line-group">
                                    <div class="checkbox-group">
                                        <input type="checkbox" id="possui-pet" name="possui-pet" <?php echo ($pet_data) ? 'checked' : ''; ?>>
                                        <label for="possui-pet">Possui Pet?</label>
                                    </div>
                                    <div class="input-quantidade-wrapper">
                                        <label for="quantidade-pets" class="sr-only">Quantidade de Pets:</label>
                                        <input type="number" id="quantidade-pets" name="quantidade-pets" min="0" value="<?php echo ($pet_data) ? 1 : 0; ?>" <?php echo ($pet_data) ? '' : 'disabled'; ?>>
                                    </div>
                                </div>
                                
                                <label for="nome-pet-cadastro">Nome Pet:</label>
                                <input type="text" id="nome-pet-cadastro" name="nome-pet" value="<?php echo htmlspecialchars($pet_data['nome_pet'] ?? ''); ?>">

                                <label for="raca-pet-cadastro">Raça:</label>
                                <input type="text" id="raca-pet-cadastro" name="raca-pet" value="<?php echo htmlspecialchars($pet_data['raca_pet'] ?? ''); ?>">

                                <div class="checkbox-line-group">
                                    <div class="checkbox-group">
                                        <input type="checkbox" id="possui-veiculo" name="possui-veiculo" <?php echo ($veiculo_data) ? 'checked' : ''; ?>>
                                        <label for="possui-veiculo">Possui veículo?</label>
                                    </div>
                                    <div class="input-quantidade-wrapper">
                                        <label for="quantidade-veiculos" class="sr-only">Quantidade de Veículos:</label>
                                        <input type="number" id="quantidade-veiculos" name="quantidade-veiculos" min="0" value="<?php echo ($veiculo_data) ? 1 : 0; ?>" <?php echo ($veiculo_data) ? '' : 'disabled'; ?>>
                                    </div>
                                </div>
                                
                                <label for="descricao-veiculo-cadastro">Descrição:</label>
                                <input type="text" id="descricao-veiculo-cadastro" name="descricao-veiculo" value="<?php echo htmlspecialchars($veiculo_data['descricao_veiculo'] ?? ''); ?>">

                                <label for="placa-veiculo-cadastro">Placa:</label>
                                <input type="text" id="placa-veiculo-cadastro" name="placa-veiculo" value="<?php echo htmlspecialchars($veiculo_data['placa_veiculo'] ?? ''); ?>">

                                <label for="modelo-veiculo-cadastro">Modelo:</label>
                                <input type="text" id="modelo-veiculo-cadastro" name="modelo-veiculo" value="<?php echo htmlspecialchars($veiculo_data['modelo_veiculo'] ?? ''); ?>">

                                <label for="cor-veiculo-cadastro">Cor:</label>
                                <input type="text" id="cor-veiculo-cadastro" name="cor-veiculo" value="<?php echo htmlspecialchars($veiculo_data['cor_veiculo'] ?? ''); ?>">
                            </div>

                            <div id="documentos" class="tab-content">
                                <label>Comprovante de Residencia</label>
                                <div class="document-upload-container">
                                    <div class="document-preview">
                                        <img src="<?php echo htmlspecialchars($documento_data['comprovante_residencia'] ?? 'https://via.placeholder.com/150x100?text=Preview'); ?>" alt="Preview Comprovante Residencia">
                                    </div>
                                    <div class="document-actions">
                                        <input type="file" id="comprovante-residencia" name="comprovante-residencia" accept="image/*, application/pdf">
                                        <button type="button" class="btn btn-outline-primary upload-btn"><i class="fas fa-upload"></i> Upload</button>
                                        <button type="button" class="btn btn-outline-danger remove-btn"><i class="fas fa-trash"></i> Remover</button>
                                    </div>
                                </div>

                                <label>Comprovante Registro Geral</label>
                                <div class="document-upload-container">
                                    <div class="document-preview">
                                        <img src="<?php echo htmlspecialchars($documento_data['comprovante_rg'] ?? 'https://via.placeholder.com/150x100?text=Preview'); ?>" alt="Preview Comprovante RG">
                                    </div>
                                    <div class="document-actions">
                                        <input type="file" id="comprovante-rg" name="comprovante-rg" accept="image/*, application/pdf">
                                        <button type="button" class="btn btn-outline-primary upload-btn"><i class="fas fa-upload"></i> Upload</button>
                                        <button type="button" class="btn btn-outline-danger remove-btn"><i class="fas fa-trash"></i> Remover</button>
                                    </div>
                                </div>

                                <label>Imagem de perfil</label>
                                <div class="document-upload-container">
                                    <div class="document-preview">
                                        <img src="<?php echo htmlspecialchars($documento_data['imagem_perfil'] ?? 'https://via.placeholder.com/150x100?text=Preview'); ?>" alt="Preview Imagem Perfil">
                                    </div>
                                    <div class="document-actions">
                                        <input type="file" id="imagem-perfil" name="imagem-perfil" accept="image/*">
                                        <button type="button" class="btn btn-outline-primary upload-btn"><i class="fas fa-upload"></i> Upload</button>
                                        <button type="button" class="btn btn-outline-danger remove-btn"><i class="fas fa-trash"></i> Remover</button>
                                    </div>
                                </div>
                            </div>

                            <div class="popup-actions">
                                <a href="administracao.php" class="btn btn-outline-secondary">Cancelar</a>
                                <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </main>
    </div>
    
    <footer>
        </footer>
    
    <script src="../assets/js/pages/administracao/administracao.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const possuiPetCheckbox = document.getElementById('possui-pet');
            const quantidadePetsInput = document.getElementById('quantidade-pets');
            const possuiVeiculoCheckbox = document.getElementById('possui-veiculo');
            const quantidadeVeiculosInput = document.getElementById('quantidade-veiculos');
            const tabButtons = document.querySelectorAll('.tab-button');
            const tabContents = document.querySelectorAll('.tab-content');

            // Lógica para pets
            if (!possuiPetCheckbox.checked) {
                quantidadePetsInput.setAttribute('disabled', 'true');
            }
            possuiPetCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    quantidadePetsInput.removeAttribute('disabled');
                    if (quantidadePetsInput.value == 0) {
                        quantidadePetsInput.value = 1;
                    }
                } else {
                    quantidadePetsInput.setAttribute('disabled', 'true');
                    quantidadePetsInput.value = 0;
                }
            });

            // Lógica para veículos
            if (!possuiVeiculoCheckbox.checked) {
                quantidadeVeiculosInput.setAttribute('disabled', 'true');
            }
            possuiVeiculoCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    quantidadeVeiculosInput.removeAttribute('disabled');
                    if (quantidadeVeiculosInput.value == 0) {
                        quantidadeVeiculosInput.value = 1;
                    }
                } else {
                    quantidadeVeiculosInput.setAttribute('disabled', 'true');
                    quantidadeVeiculosInput.value = 0;
                }
            });

            // Lógica para abas
            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    tabButtons.forEach(btn => btn.classList.remove('active'));
                    tabContents.forEach(content => content.classList.remove('active'));
                    this.classList.add('active');
                    const targetTab = this.dataset.tab;
                    document.getElementById(targetTab).classList.add('active');
                });
            });
            // O código para upload de arquivos pode ser o mesmo do seu `administracao.js`
        });
    </script>
</body>
</html>