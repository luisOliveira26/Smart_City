<!doctype html>

<html lang="pt-pt">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="style.css">
        <title>Cidade Inteligente - Login</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    </head>


    <body id="index">
        <div class="container col-sm-4 pt-5 mt-5">
            <div class="card">
                <div class="card-body">
                    <div class="row ">
                        <form action="#" method="POST">
                            <img width="350" src="estg_h.png" alt="Imagem da ESTG" id="imagem_ESTG">
                            <br><br><br>
                            <div class="form-group ps-2 pe-2" id="username">
                                <label for="nome1">Username: </label><br>
                                <input type="text" class="form-control" required name="nome" id="nome1" placeholder="Nome de Utilizador">
                            </div>
                            <br><br>
                            <div id="password" class="form-group ps-2 pe-2">
                                <label for="pass1">Password: </label><br>
                                <input type="password" class="form-control" name="pass" id="pass1" placeholder="Palavra-Passe">
                            </div>
                            <br>
                            <br>
                            <div class="d-flex flex-row-reverse pe-3">
                                <button class="btn btn-primary text-left" type="submit">Iniciar Sessão</button>
                            </div>
                            <br>
                            <div id="liveAlertPlaceholder"></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <script src="alertas.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-zYPOMqeu1DAVkHiLqWBUTcbYfZ8osu1Nd6Z89ify25QV9guujx43ITvfi12/QExE" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js" integrity="sha384-Y4oOpwW3duJdCWv5ly8SCFYWqFDsfob/3GkgExXKV4idmbt98QcxXYs9UoXAB7BZ" crossorigin="anonymous"></script>

        <?php

        // Recebe a linha com os dados do utilizador no ficheiro, e devolve as respetivas informações
        function obterDadosUtilizador($linha)
        {
            $stringRecebida = file_get_contents("files/login.txt");
            $Utilizadores = explode("\n", $stringRecebida);
            $dadosUser = explode(":", $Utilizadores[$linha]);
            return $dadosUser;
        }

        function verificarLogin($utilizador, $palavra_passe, $caminho_ficheiro)
        {
            //  A função recebe os inputs introduzidos, e verifica se existe esse utilizador no ficheiro, se existir, verifica se a palavra-passe
            //  coincide com a palavra-passe do utilizador

            //  Se todas estas condições se verificarem, devolve a linha do ficheiro com os dados do utilizador
            $stringRecebida = file_get_contents($caminho_ficheiro);
            $Utilizadores = explode("\n", $stringRecebida);
            $quantidadeUsers = count($Utilizadores);

            for ($i = 0; $i < $quantidadeUsers; $i++) {
                $dadosUser = explode(":", $Utilizadores[$i]);

                if ($dadosUser[0] == $utilizador) {
                    if (password_verify($palavra_passe, $dadosUser[1])){
                        return $i;
                    }
                }
            }

            return -1;
        }
        ?>

        <?php
        session_start();
        if (isset($_POST['nome'])) {
            $verificar = verificarLogin($_POST['nome'], $_POST['pass'], "files/login.txt");
            // Se a função devolver -1, significa que não encontrou os dados fornecidos pelo utilizador, caso contrário
            // cria uma variável de sessão, com o nome do utilizador e as suas respetivas permissões
            if ($verificar > -1) {
                $dadosUser = obterDadosUtilizador($verificar);
                $_SESSION['username'] = $dadosUser[0];
                $_SESSION['permissoes'] = $dadosUser[2];
                header("Location: dashboard.php");
            } else {
                // Ativa o poupup com um aviso de credenciais inválidas
                echo "<script>CredenciaisInvalidas();</script>";
            }
        }
        ?>

    </body>

</html>