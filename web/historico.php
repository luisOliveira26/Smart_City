<!DOCTYPE html>
<html lang="pt-pt">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cidade Inteligente - Histórico</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <meta http-equiv="refresh" content="5">
</head>

<?php

include("verificarSessao.php");
if ($_SESSION['permissoes'] != "admin") {
    header("Location: dashboard.php");
}


function verificarNome($array, $tamanho, $string)
{
    for ($i = 0; $i < $tamanho; $i++) {
        if ($string == $array[$i]) {
            return $i;
        }
    }
    return -1;
}


// Validações
if (!isset($_GET['pesquisa']) || trim($_GET['pesquisa']) != $_GET['pesquisa']) {
    $pesquisa = "geral";
} else {
    $pesquisa = $_GET['pesquisa'];
}

$ficheiro = file_get_contents("api/files/nomeSensores.txt");
$nomeSensores = explode(":", $ficheiro);
$tamanhoNomes = count($nomeSensores);

$posicaoNome = verificarNome($nomeSensores, $tamanhoNomes, $pesquisa);


?>


<body style="background-color: #f0f0f0;">

    <?php include("navbar.php"); ?>

    <div class="container">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="display-5 p-5 ps-0" style="font-size: 60px;">Histórico</h1>
            </div>
            <div class="col-sm-6 d-flex flex-row-reverse">
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Opções
                    </button>
                    <ul class="dropdown-menu">
                        <!------- Insere as opções no menu dropdown a partir do ficheiro com o nome dos respetivos sensores / atuadores ----->
                        <li><a class="dropdown-item" href="historico.php">Geral</a></li>
                        <?php for ($i = 0; $i < $tamanhoNomes; $i++) { ?>
                            <li><a class="dropdown-item" href="historico.php?pesquisa=<?= $nomeSensores[$i]; ?>"><?php if ($nomeSensores[$i] == "led") {
                                                                                                                        echo "LED";
                                                                                                                    } else {
                                                                                                                        echo ucfirst($nomeSensores[$i]);
                                                                                                                    } ?></a></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <br><br><br>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <!------ Nome da página ----->
                <b>Histórico - <?php if ($posicaoNome > -1 && $pesquisa != "led") {
                                    echo ucfirst($pesquisa);
                                } else if ($pesquisa == "led") {
                                    echo "LED";
                                } else {
                                    echo "Geral";
                                } ?></b>
            </div>

            <div class="card-body">


                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Nome do Dispositivo IoT</th>
                            <th scope="col">Valor</th>
                            <th scope="col">Data de Atualização</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        // Percorre o ficheiro de nomes dos sensores/atuadores, e faz uma busca pelos seus respetivos logs para criar a tabela
                        for ($i = 0; $i < $tamanhoNomes; $i++) {
                            if ($posicaoNome == $i || $posicaoNome == -1) {
                                $valoresHistorico = file_get_contents("api/files/" . $nomeSensores[$i] . "/log.txt");
                                if (empty($valoresHistorico)) {
                                    continue;
                                }
                                $linhasLog = preg_split('/\r\n|\r|\n/', $valoresHistorico);
                                // O histórico contêm apenas as 10 últimas linhas
                                $contarNdeRegistos = count($linhasLog) - 1;
                                for ($j = 0; $j < $contarNdeRegistos; $j++) {
                                    if ($j >= $contarNdeRegistos - 10) {
                                        $valores = explode(";", $linhasLog[$j]);


                        ?>
                                        <tr>
                                            <td><?php if ($nomeSensores[$i] == "led") {
                                                    echo "LED";
                                                } else {
                                                    echo ucfirst($nomeSensores[$i]);
                                                } ?></td>
                                            <td><?php
                                                if ($nomeSensores[$i] == "temperatura") {
                                                    echo $valores[1]." º";
                                                }else if($nomeSensores[$i] == "humidade"){
                                                    echo $valores[1]." %";
                                                }else if($nomeSensores[$i] == "led"){
                                                    echo $valores[1];
                                                }
                                                else{
                                                     if(str_contains($valores[1],"1")){echo "ON";}else if(str_contains($valores[1],"0")){echo "OFF";}
                                                }?></td>
                                            <td><?= $valores[0] ?></td>
                                        </tr>
                        <?php
                                    }
                                }
                            }
                        }
                        ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <br><br><br>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-zYPOMqeu1DAVkHiLqWBUTcbYfZ8osu1Nd6Z89ify25QV9guujx43ITvfi12/QExE" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js" integrity="sha384-Y4oOpwW3duJdCWv5ly8SCFYWqFDsfob/3GkgExXKV4idmbt98QcxXYs9UoXAB7BZ" crossorigin="anonymous"></script>
</body>


</html>