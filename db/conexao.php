<?php


$servidor = "localhost";
$usuario = "root";
$senha = "";
$banco = "lista_tarefas";

try {
   
    $pdo = new PDO("mysql:host=$servidor;dbname=$banco", $usuario, $senha);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
} catch (PDOException $e) {
 
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}


if (!function_exists('limparPost')) {
    function limparPost($dado) {
        $dado = trim($dado);
        $dado = stripslashes($dado);
        $dado = htmlspecialchars($dado);
        return $dado;
    }
}
?>
