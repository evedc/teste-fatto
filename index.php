<?php
require('db/conexao.php');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Tarefas</title>
</head>
<body>
    <h1>Lista de Tarefas</h1>
    <form method="post">
        <input type="text" name="nome" placeholder="Digite seu nome" required>
        <input type="email" name="custo" placeholder="Custo (R$)" required>
        <input type="date" name="data_limite" placeholder="Data Limite" required>
        <button type="submit" name="salvar">Salvar Tarefa</button>
    </form> 
    <br>


    <?php

 if (isset($_POST['salvar'])&& isset($_POST['nome'])&& isset($_POST['custo'])&& isset($_POST['data_limite'])){
    
    $nome = limparPost($_POST['nome']);
    $email= limparPost($_POST['email']);
    $data_limite= limparPost($_POST['data_limite']);
    $data=date('d-m-Y');

  
    if ($nome=="" || $nome==null){
        echo "<b style='color:red'>Nome não pode ser vazio</b>";
        exit();
    }

    if ($custo=="" || $custo==null){
        echo "<b style='color:red'Custo não pode ser vazio</b>";
        exit();
    }

    if ($data_limite=="" || $data_limite==null){
        echo "<b style='color:red'Data limite não pode ser vazia</b>";
        exit();
    }

    if (!preg_match("/^[a-zA-Z-' ]*$/",$nome)) {
        echo "<b style='color:red'>Somente permitido letras e espaços em branco para o nome</b>";
        exit();
    }

 
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<b style='color:red'>Formato de email inválido!</b>";
        exit();
    }

    $sql = $pdo->prepare("INSERT INTO clientes VALUES (null,?,?,?)");
    $sql->execute(array($nome,$custo,$data_limite));

    echo "<b style='color:green'>Tarefa inserida com sucesso!</b>";

 }
    ?>
    
</body>
</html>