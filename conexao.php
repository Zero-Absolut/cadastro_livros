<?php

//criando variaveis com valores para cenexao
$hostname = "localhost";
$usuario = "root";
$senha = "";
$banco = "livraria";

//criando objeto para conexao


$conexao = new mysqli($hostname, $usuario, $senha, $banco);

// verificando se a conexao teve algum problema
//if ($conexao->connect_errno) {
  //  echo "falha ao conectar: ";
//} else {
  //  echo "conexão efetuada com sucesso";
//}
