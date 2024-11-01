<?php
require('db/conexao.php');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles.css">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Tarefas</title>
</head>
<body>
<section class="table-container"> 
    <h1>Lista de Tarefas</h1>
    <form method="post">
        <input type="text" name="nome" placeholder="Digite sua tarefa" required>
        <input type="number" name="custo" placeholder="Custo (R$)" required>
        <input type="date" name="data_limite" placeholder="Data Limite" required>
        <button type="submit" name="salvar">Salvar Tarefa</button>
    </form> 
    <br>


    <?php

 if (isset($_POST['salvar'])&& isset($_POST['nome'])&& isset($_POST['custo'])&& isset($_POST['data_limite'])){
    
    $nome = limparPost($_POST['nome']);
    $custo= limparPost($_POST['custo']);
    $data_limite= limparPost($_POST['data_limite']);
    $data=date('d-m-Y');

  
    if ($nome=="" || $nome==null){
        echo "<b style='color:red'>Nome Tarefa não pode ser vazio</b>";
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

  
  
    $sql = $pdo->prepare("INSERT INTO tarefas (nome, custo, data_limite) VALUES (?, ?, ?)");

    $sql->execute(array($nome,$custo,$data_limite));

    echo "<b style='color:green'>Tarefa inserida com sucesso!</b>";

 }
    ?>

<?php

    $sql = $pdo->prepare("SELECT * FROM tarefas");
    $sql->execute();
    $dados = $sql->fetchAll();

   ?>

<?php

if (count($dados) > 0) {
    echo "<br><br><table>
    <tr>
        <th>Nome Tarefa</th>
        <th>Custo (R$)</th>
        <th>Data Limite</th>
        <th>Ações</th>
    </tr>";

    foreach ($dados as $chave => $valor) {
        echo "<tr>
                <td>" . htmlspecialchars($valor['nome']) . "</td>
                <td>" . htmlspecialchars($valor['custo']) . "</td>
                <td>" . htmlspecialchars($valor['data_limite']) . "</td>
                <td>
                    <button onclick='openEditModal(" . $valor['id'] . ", \"" . htmlspecialchars($valor['nome']) . "\", " . htmlspecialchars($valor['custo']) . ", \"" . htmlspecialchars($valor['data_limite']) . "\")'>Editar</button>
                    <form method='post' style='display:inline;'>
                        <input type='hidden' name='id' value='" . htmlspecialchars($valor['id']) . "'>
                        <button type='submit' name='remover' onclick='return confirm(\"Tem certeza que deseja remover esta tarefa?\");'>Remover</button>
                    </form>
                </td>
              </tr>";
    }

    echo "</table>";
} else {
    echo "<p>Nenhuma tarefa cadastrada</p>";
}
?>

<!-- Modal de Edição com CSS incluído diretamente -->
<div id="editModal" style="display:none; position:fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.7); z-index: 1000;">
    <div style="background-color: white; margin: 15% auto; padding: 20px; border: 1px solid #888; width: 300px;">
        <span onclick="closeEditModal()" style="cursor:pointer; float:right;">&times;</span>
        <h2>Editar Tarefa</h2>
        <form method="post" id="editForm">
            <input type="text" name="nome" id="editNome" required>
            <input type="number" name="custo" id="editCusto" required>
            <input type="date" name="data_limite" id="editDataLimite" required>
            <input type="hidden" name="id" id="editId">
            <button type="submit" name="atualizar">Atualizar Tarefa</button>
        </form>
    </div>
</div>
 
</section>
    <script src="engine.js"></script>
</body>
</html>