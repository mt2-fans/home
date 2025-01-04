<?php
// Conexão com o banco de dados
include 'conn.php';

$conn = new mysqli($servername, $username, $password);

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbplayer", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erro de conexão: " . $e->getMessage();
}
//Definindo Variáveis de Paginação
$por_pagina = 10; // Número de itens por página
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$inicio = ($pagina > 1) ? ($pagina * $por_pagina) - $por_pagina : 0;

//SQL
$sql = "SELECT p.job, p.name, p.level, p.exp, pi.empire FROM $dbplayer.player as p
INNER JOIN $dbplayer.player_index as pi ON p.account_id = pi.id
WHERE p.`name` NOT IN (SELECT mName FROM $dbcommon.gmlist)
ORDER BY p.level DESC LIMIT :inicio, :por_pagina";

//Consulta ao Banco de Dados
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':inicio', $inicio, PDO::PARAM_INT);
$stmt->bindValue(':por_pagina', $por_pagina, PDO::PARAM_INT);
$stmt->execute();
$players = $stmt->fetchAll(PDO::FETCH_ASSOC);

//Contando o Total de Itens
$total = $pdo->query("SELECT COUNT(*) FROM $dbplayer.player")->fetchColumn();
$paginas = ceil($total / $por_pagina);

$offset = ($pagina -1) * $por_pagina;

function pgClass($job)
{
    switch ($job) {
        case 0:
            return '<img src="./assets/ranking/0.png">';
        case 1:
            return '<img src="./assets/ranking/1.png">';
        case 2:
            return '<img src="./assets/ranking/2.png">';
        case 3:
            return '<img src="./assets/ranking/3.png">';
        case 4:
            return '<img src="./assets/ranking/4.png">';
        case 5:
            return '<img src="./assets/ranking/5.png">';
        case 6:
            return '<img src="./assets/ranking/6.png">';
        case 7:
            return '<img src="./assets/ranking/7.png">';
    }
}

function pgKingdom($kingdom)
{
    switch ($kingdom) {
        case 1:
            return '<img src="./assets/ranking/shinso.jpg">';
        case 2:
            return '<img src="./assets/ranking/chunjo.jpg">';
        case 3:
            return '<img src="./assets/ranking/jinno.jpg">';
    }
}
?>
<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="./assets/favicon.ico">
    <title>Metin2</title>
    <link href="./css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="logo">
            <a href="index.php" class="navbar-brand"><img src="./assets/metin2.png" class="img-fluid" alt="metin2"></a>
        </div>
        <div class="container-fluid">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="index.php">Cadastro</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="download.php">Download</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="ranking.php">Ranking</a>
                </li>
        </div>
    </nav>
    <div class="content">
        <div class="container">
            <h2 class="text text-primary">Ranking de Personagens</h2>
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Classe</th>
                        <th scope="col">Nome</th>
                        <th scope="col">Nível</th>
                        <th scope="col">Exp</th>
                        <th scope="col">Reino</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $count = 0;
                    // Saída dos dados de cada linha
                    foreach ($players as $player => $row) {
                        $position = $offset + $player +1;
                        echo "<tr>";
                        echo "<th scop='row'>" . $position . "</th>";
                        echo "<td>" . pgClass($row['job']) . "</td>";
                        echo "<td>" . $row["name"] . "</td>";
                        echo "<td>" . $row["level"] . "</td>";
                        echo "<td>" . $row["exp"] . "</td>";
                        echo "<td>" . pgKingdom($row["empire"]) . "</td>";
                        echo "</tr>";
                    }


                    // Fecha a conexão
                    $conn->close();
                    ?>
                </tbody>
            </table>
            <?php
            $total_paginas = ceil($total / $por_pagina);
            $limite = 2; // Número de páginas a mostrar antes e depois da página atual
            echo '<div class="container">';
                echo '<div class="pagination">';

                for ($i = 1; $i <= $total_paginas; $i++) {
                    // Se estamos na primeira página ou na última, sempre mostramos
                    if ($i == 1 || $i == $total_paginas) {
                        echo ($i == $pagina) ? "<strong class='page-link active'>$i</strong> " : "<a class='page-link' href='?pagina=$i'>$i</a> ";
                    }
                    // Mostra páginas vizinhas à página atual
                    elseif ($i >= $pagina - $limite && $i <= $pagina + $limite) {
                        echo ($i == $pagina) ? "<strong class='page-link active'>$i</strong> " : "<a class='page-link' href='?pagina=$i'>$i</a> ";
                    }
                    // Adiciona os três pontinhos
                    elseif ($i == $pagina - $limite - 1 || $i == $pagina + $limite + 1) {
                        echo "<div class='page-link'>...</div>";
                    }
                }

                echo '</div>';
            echo '</div>';
            ?>
        </div>
    </div>
    <footer id="sticky-footer" class="flex-shrink-0 py-3 bg-dark text-white-50">
        <div class="container text-center">
            <small>Todos os direitos reservados! Copyright &copy; <?php echo date("Y"); ?></small>
        </div>
    </footer>
</body>