<?php
include_once('conexao.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $delete = trim($_POST['delete']);


    $identificado_delete = mysqli_query($conexao, "DELETE FROM livros WHERE id = '$delete'");

    if (!empty($identificado_delete)) {
        echo "Livro deletado com sucesso";
        header("location: index.php");
        exit();
    } else {
        echo "problemas ao deletar o livro";
        header("location: index.php");
        exit();
    }
    mysqli_close($conexao);
} else {
}
