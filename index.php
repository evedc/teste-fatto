<?php
require('db/conexao.php');

function limparPost($dados) {
    // Remove espaços em branco no início e no final
    $dados = trim($dados);
    // Converte caracteres especiais em entidades HTML para prevenir XSS
    $dados = htmlspecialchars($dados, ENT_QUOTES, 'UTF-8');
    return $dados;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles.css">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Icons Google-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <title>Lista de Tarefas</title>
</head>
<body>
<section class="table-container"> 
    <div class="tarefas-box"> 
        <h1>Lista de Tarefas</h1>
        <form method="post">
            <input type="text" name="nome" placeholder="Digite sua tarefa" required><br>
            <input type="number" name="custo" placeholder="Custo (R$)" required><br>
            <input type="date" name="data_limite" placeholder="Data Limite" required><br>
            <button class="save" type="submit" name="salvar">Salvar Tarefa</button>
        </form> 
    </div>
    <br>

    <?php
    if (isset($_POST['salvar']) && isset($_POST['nome']) && isset($_POST['custo']) && isset($_POST['data_limite'])) {
        $nome = limparPost($_POST['nome']);
        $custo = limparPost($_POST['custo']);
        $data_limite = limparPost($_POST['data_limite']);
        $data = date('d-m-Y');

        if ($nome == "" || $nome == null) {
            echo "<b style='color:red'>Nome Tarefa não pode ser vazio</b>";
            exit();
        }

        if ($custo == "" || $custo == null) {
            echo "<b style='color:red'>Custo não pode ser vazio</b>";
            exit();
        }

        if ($data_limite == "" || $data_limite == null) {
            echo "<b style='color:red'>Data limite não pode ser vazia</b>";
            exit();
        }

        $sql = $pdo->prepare("INSERT INTO tarefas (nome, custo, data_limite) VALUES (?, ?, ?)");
        $sql->execute(array($nome, $custo, $data_limite));

        echo "<b style='color:green'>Tarefa inserida com sucesso!</b>";
    }

    // Seção onde verifica se o botão de remover foi clicado
    if (isset($_POST['remover']) && isset($_POST['id'])) {
        $id = limparPost($_POST['id']);
        
        // Executa a remoção da tarefa no banco de dados
        $sql = $pdo->prepare("DELETE FROM tarefas WHERE id = ?");
        if ($sql->execute([$id])) {
            echo "<b style='color:green'>Tarefa removida com sucesso!</b>";
        } else {
            echo "<b style='color:red'>Erro ao remover a tarefa.</b>";
        }
    }

    $sql = $pdo->prepare("SELECT * FROM tarefas");
    $sql->execute();
    $dados = $sql->fetchAll();

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
                        <button onclick='openEditModal(" . $valor['id'] . ", \"" . htmlspecialchars($valor['nome']) . "\", " . htmlspecialchars($valor['custo']) . ", \"" . htmlspecialchars($valor['data_limite']) . "\")' style='background:none; border:none; cursor:pointer;'>
                            <span class='material-icons'>edit</span>
                        </button>
                        <form method='post' style='display:inline;'>
                            <input type='hidden' name='id' value='" . htmlspecialchars($valor['id']) . "'>
                            <button type='submit' name='remover' onclick='return confirm(\"Tem certeza que deseja remover esta tarefa?\");' style='background:none; border:none; cursor:pointer;'>
                                <span class='material-icons'>delete</span>
                            </button>
                        </form>
                    </td>
                  </tr>";
        }

        echo "</table>";
    } else {
        echo "<p>Nenhuma tarefa cadastrada</p>";
    }

    if (isset($_POST['atualizar'])) {
        $id = limparPost($_POST['id']);
        $nome = limparPost($_POST['nome']);
        $custo = limparPost($_POST['custo']);
        $data_limite = limparPost($_POST['data_limite']);

        // Atualiza a tarefa no banco de dados
        $sql = $pdo->prepare("UPDATE tarefas SET nome = ?, custo = ?, data_limite = ? WHERE id = ?");
        $sql->execute(array($nome, $custo, $data_limite, $id));

        echo "<b style='color:green'>Tarefa atualizada com sucesso!</b>";
    }
    ?>

    <!-- Modal de Edição com CSS incluído diretamente -->
    <div id="editModal" style="display:none; position:fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.7); z-index: 1000;">
        <div style="background-color:#c4273f; margin: 15% auto; padding: 20px; border: 1px solid #888; width: 300px;">
            <span onclick="closeEditModal()" style="cursor:pointer; float:right;">&times;</span>
            <h2 style="color: #fff">Editar Tarefa</h2>
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
