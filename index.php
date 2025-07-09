<?php
include_once 'conexao.php';

// Inicializa a variável que conterá os dados para a tabela
$dadosParaTabela = [];

// Verifica se há um termo de pesquisa na URL
if (isset($_GET['termo_pesquisa']) && !empty(trim($_GET['termo_pesquisa']))) {
    // Houve uma pesquisa. Prepara a query de busca.
    $termo_pesquisa = trim($_GET['termo_pesquisa']);
    // REMOVIDO: mysqli_real_escape_string para simplificação no estudo básico
    $query = "SELECT * FROM livros WHERE titulo LIKE '%$termo_pesquisa%' OR autor LIKE '%$termo_pesquisa%'";
} else {
    // Não houve pesquisa (ou o termo estava vazio). Prepara a query para listar todos.
    $query = "SELECT * FROM livros";
}

// Executa a query definida acima (seja de pesquisa ou de listagem completa)
$sql = mysqli_query($conexao, $query);

// Verifica se a consulta foi bem-sucedida
if ($sql) {
    // Se há resultados, preenche $dadosParaTabela
    if (mysqli_num_rows($sql) > 0) {
        while ($valor = mysqli_fetch_assoc($sql)) {
            $dadosParaTabela[] = $valor;
        }
    } else {
        // Se a consulta não retornou linhas (nenhum resultado), $dadosParaTabela permanece vazio
        // A mensagem de "Nenhum livro encontrado" será exibida no HTML
    }
} else {
    // Erro ao executar a query no banco de dados
    echo "Erro ao acessar o banco de dados: " . mysqli_error($conexao);
}



?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Livros</title>
    <link rel="stylesheet" href="css/index.css">
</head>

<body>
    <div class="container">
        <header class="header">
            <h1>Listar Livros</h1>
            <form action="index.php" method="GET" class="search-form">
                <input
                    type="text"
                    name="termo_pesquisa"
                    placeholder="Buscar por título ou autor..."
                    class="search-input"
                    value="<?php
                            if (isset($_GET['termo_pesquisa'])) {
                                echo htmlspecialchars($_GET['termo_pesquisa']);
                            }
                            ?>">
                <button type="submit" class="search-button">Pesquisar</button>
            </form>
            <button class="add-button"><a href="adicionar.php" style="text-decoration: none; color: white;">Adicionar Novo Livro</a></button>
        </header>
        <main class="main-content">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Autor</th>
                            <th>Ano</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        if (!empty($dadosParaTabela)) {
                            foreach ($dadosParaTabela as $values) {
                        ?>
                                <tr>
                                    <td>
                                        <?php
                                        echo htmlspecialchars($values['id']);
                                        ?>
                                    </td>
                                    <td> <?php
                                            echo htmlspecialchars($values["titulo"]);
                                            ?>
                                    </td>
                                    <td> <?php
                                            echo htmlspecialchars($values['autor']);
                                            ?>
                                    </td>
                                    <td>
                                        <?php
                                        echo htmlspecialchars($values['ano']);
                                        ?>
                                    </td>
                                    <td class="actions">
                                        <a href="editar.php?id=<?php echo htmlspecialchars($values['id']); ?>" class="edit-button">
                                            <button class="edit-button">Editar</button>
                                        </a>

                                        <br>
                                        <form style="text-align: center;" class="delete-button" action="delete.php" method="POST"><button name="delete" class="delete-button" value="<?php echo htmlspecialchars($values['id']); ?>">Excluir</button></form>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>

                        <?php
                            //else da verificação do array livros vazio


                        } else {
                        ?>
                            <tr>
                                <td>
                                    <?php
                                    echo "nenhum registro.";
                                    ?>
                                </td>
                                <td> <?php
                                        echo "nenhum registro.";
                                        ?></td>
                                <td> <?php
                                        echo "nenhum registro.";
                                        ?>
                                </td>
                                <td>
                                    <?php
                                    echo "nenhum registro.";
                                    ?>
                                </td>

                            </tr>


                        <?php
                            // fechando verificação else array livros 
                        }


                        ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>

</html>