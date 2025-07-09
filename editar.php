<?php
include_once('conexao.php');
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_busca = trim($_GET['id']);

    $resultado_busca = [];

    $query_busca = "SELECT * FROM livros WHERE id = '$id_busca'";

    $busca_dados_alteracao = mysqli_query($conexao, $query_busca);

    if (!empty($busca_dados_alteracao)) {
        while ($linhas = mysqli_fetch_assoc($busca_dados_alteracao)) {
            $resultado_busca[] = $linhas;
        }
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_alteracao = trim($_POST['id']);
    $autor_alteracao = trim($_POST['autor']);
    $titulo_alteracao = trim($_POST['titulo']);
    $ano_alteracao = trim($_POST['ano']);

    $mensagemSucesso = "";
    $mensagemErro = [];
    if (empty($titulo_alteracao)) {
        $mensagemErro['titulo'] = "O titulo não pode ser vazio.";
    } elseif (strlen($titulo_alteracao) < 4) {
        $mensagemErro['titulo'] = "Titulo deve conter mais de quatro caracteres.";
    }

    if (empty($autor_alteracao)) {
        $mensagemErro['autor'] = "O campo do autor autor não pode ser vazio";
    } elseif (strlen($autor_alteracao) < 4) {
        $mensagemErro['autor'] = "Autor não pode ter menos que quetro caracteres";
    }

    if (empty($ano_alteracao)) {
        $mensagemErro['ano'] = "O ano não pode ser vazio.";
    } elseif (!is_numeric($ano_alteracao) || strlen($ano_alteracao) != 4 || $ano_alteracao < 1000 || $ano_alteracao > date("Y")) {
        $mensagemErro['ano'] = "O ano deve ser um número de 4 dígitos válido e não pode ser maior que o ano atual.";
    }

    if (empty($mensagemErro)) {
        $query_alteracao = "UPDATE livros SET
            titulo = '$titulo_alteracao',
            autor = '$autor_alteracao',
            ano = $ano_alteracao
            WHERE id = '$id_alteracao'";
        $inserir_alteracao = mysqli_query($conexao, $query_alteracao);




        if ($inserir_alteracao) {
            // Query executada com sucesso, verificando se alguma linha foi afetada
            if (mysqli_affected_rows($conexao) > 0) {
                $mensagemSucesso = "Livro cadastrado com sucesso ";
            }
        } else {
            // Erro na execução da query SQL
            $mensagemErro['db'] = "Erro ao atualizar no banco de dados: " . mysqli_error($conexao);
        }


        $id_alteracao = "";
        $autor_alteracao = "";
        $titulo_alteracao = "";
        $ano_alteracao = "";
    } else {
        $mensagemErro['db'] = "erro ao inserir no banco de dados" . mysqli_error($conexao);
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Livro</title>
    <link rel="stylesheet" href="css/editar.css">
</head>

<body>
    <div class="container">
        <a href="index.php" class="back-link">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            Voltar
        </a>

        <h1 class="page-title">Editar Livro</h1>

        <div class="feedback-message-container">
            <div class="feedback-message">
                <?php
                if (!empty($mensagemErro)) {
                    foreach ($mensagemErro as $key => $values) {
                        echo '<ul style="color: red;">
                                    <li>"' . $values . '"</li>                                    
                              </ul>';
                    }
                } elseif (!empty($mensagemSucesso)) {
                    echo '<p style="color: green;">' . $mensagemSucesso . '</p>';
                }

                ?>
            </div>
        </div>
        <form class="book-form" method="post" action="editar.php">
            <div class="form-group">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($id_busca); ?>">
                <label for="titulo">Título atual: <?php if (!empty($resultado_busca)) {
                                                        echo '<span style="color: limegreen;">' . htmlspecialchars($resultado_busca[0]['titulo']) . '</span>';
                                                    } else {
                                                        echo "Nenhum dado encontrado";
                                                    }  ?></label>
                <input
                    type="text"
                    id="titulo"
                    name="titulo"
                    value="" placeholder="Digite o Título do Livro a ser alterado"
                    required>
            </div>

            <div class="form-group">
                <label for="autor">Autor atual: <?php if (!empty($resultado_busca)) {
                                                    echo '<span style="color: limegreen;">' . htmlspecialchars($resultado_busca[0]['autor']) . '</span>';
                                                } else echo "Nenhum dado encontrado."; ?></label>
                <input
                    type="text"
                    id="autor"
                    name="autor"
                    value="" placeholder="Digite o nome do autor a ser alterado"
                    required>
            </div>

            <div class="form-group">
                <label for="ano">Ano atual: <?php if (!empty($resultado_busca)) {
                                                echo '<span style="color: limegreen;">' . htmlspecialchars($resultado_busca[0]['ano']) . '</span>';
                                            } else {
                                                echo "Nenhum dado encontrado.";
                                            }  ?></label>
                <input
                    type="number"
                    id="ano"
                    name="ano"
                    value="" placeholder="Digite o ano a ser alterado"
                    required>
            </div>



            <button type="submit" class="save-button">Salvar Alterações</button>
        </form>

    </div>
</body>

</html>