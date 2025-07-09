<?php

include_once('conexao.php');

$titulo = "";
$autor = "";
$ano = "";
$erro = [];
$mensagemSucesso = "";



// Verifica se a requisição foi feita usando o método POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Você pode acessar os dados do formulário através da superglobal $_POST
    $titulo = $_POST['titulo'] ?? ''; // Usando operador null coalesce para evitar erro se não existir
    $autor = $_POST['autor'] ?? '';
    $ano = $_POST['ano'] ?? '';


    // valida dados do titulo
    if (empty($titulo)) {
        $erro['titulo'] = "O titulo do livro não pode ser vazio.";
    } elseif (strlen($titulo) < 3) {
        $erro['titulo'] =  "O título deve conter mais que três caracteres.";
    }

    // valida dados do autor

    if (empty($autor)) {
        $erro['autor'] = "O campo do autor não pode ser vazio.";
    } elseif (strlen($autor) < 4) {
        $erro['autor'] = "O nome do autor deve conter pelo menos quatro caracteres.";
    }

    // valida dados do ano

    if (empty($ano)) {
        $erro['ano'] = "O ano não pode ser vazio";
    } elseif (!is_numeric($ano) || strlen($ano) != 4 || $ano < 1000 || $ano > date("Y")) {
        // !is_numeric($ano)     -> Verifica se não é um número
        // strlen($ano) != 4    -> Verifica se não tem exatamente 4 dígitos
        // $ano < 1000           -> Evita anos como "0001" ou negativos
        // $ano > date("Y") + 10 -> Evita anos muito no futuro (ex: mais de 10 anos à frente do ano atual)
        $erro['ano'] = "O ano deve ser um número de 4 dígitos válido e não pode ser maior que o ano atual.";
    }

    if (empty($erro)) {

        $tituloDB = trim($titulo);
        $autorDB = trim($autor);
        $anoDB = trim($ano);
        // adicionar DB

        $inserir = mysqli_query($conexao, "INSERT INTO livros(titulo, autor, ano) VALUES('$titulo', '$autor', $ano)");

        if ($inserir) {
            $mensagemSucesso = "Livro " . htmlspecialchars($tituloDB) . "  cadastrado com sucesso.";
            $titulo = "";
            $autor = "";
            $ano = "";
            header('Location: index.php');
            exit();
        } else {
            $erro['db_erro'] = "erro ao inserir no banco de dados" . mysqli_error($conexao);
        }
    }
} else {
    // A página foi acessada via GET (ou outro método)
    //header('Location: adicionar.php');
    //exit();
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Livro</title>
    <link rel="stylesheet" href="css/adicionar.css">
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

        <form class="book-form" method="post" action="adicionar.php">
            <div class="form-group">
                <label for="titulo">Título : <?php
                                                if (!empty($erro)) {
                                                    foreach ($erro as $key => $erros) {
                                                        if ($key === "titulo") {
                                                            echo '<span style="color: red;">' . htmlspecialchars($erros) . '</span>';
                                                        }
                                                    }
                                                } else {
                                                    echo 'Digite o Título do Livro';
                                                }
                                                ?></label>
                <input
                    type="text"
                    id="titulo"
                    name="titulo"
                    value="<?php echo htmlspecialchars($titulo); ?>"
                    placeholder="">
            </div>

            <div class="form-group">
                <label for="autor">Autor: <?php
                                            if (!empty($erro)) {
                                                foreach ($erro as $key => $erros) {
                                                    if ($key === "autor") {
                                                        echo '<span style="color: red;">' . htmlspecialchars($erros) . '</span>';
                                                    }
                                                }
                                            } else {
                                                echo 'Digite o nome do autor';
                                            }
                                            ?></label>
                <input
                    type="text"
                    id="autor"
                    name="autor"
                    value="<?php echo htmlspecialchars($autor); ?>"
                    placeholder="">
            </div>

            <div class="form-group">
                <label for="ano">Ano : <?php
                                        if (!empty($erro)) {
                                            foreach ($erro as $key => $erros) {
                                                if ($key === "ano") {
                                                    echo '<span style="color: red;">' . htmlspecialchars($erros) . '</span>';
                                                }
                                            }
                                        } else {
                                            echo 'Digite o ano';
                                        }
                                        ?></label>
                <input
                    type="number"
                    id="ano"
                    name="ano"
                    value="<?php echo htmlspecialchars($ano); ?>"
                    placeholder="">
            </div>
            <div class="mensagem-container">
                <div class="mensagem ">
                    <?php
                    if ($mensagemSucesso) {
                        echo htmlspecialchars($mensagemSucesso);
                    }
                    ?>
                </div>

            </div>


            <button type="submit" class="save-button">Salvar</button>
        </form>

    </div>
</body>

</html>