<?php
//echo $_SERVER['REQUEST_METHOD'];



if($_SERVER['REQUEST_METHOD'] == "POST") // Pediu post?
{

    if(isset($_POST['nome'])&& isset($_POST['valor']) && isset($_POST['hora']) && count($_POST) == 3 ) // Verifica se foi submetido todos os parametros e se só esses parametros foram submetidos
    {
        if($_POST['nome']=="temperatura" || $_POST['nome']=="humidade" || $_POST['nome']=="led" || $_POST['nome'] == "semaforo" || $_POST['nome'] == "avoid" || $_POST['nome'] == "luminosidade" || $_POST['nome'] == "alarme") // verificar se existe e qual o sensor/atuador 
        {
            file_put_contents("files/".$_POST['nome']."/hora.txt",  $_POST['hora'] . PHP_EOL); // escreve no ficheiro 
            file_put_contents("files/".$_POST['nome']."/valor.txt",  $_POST['valor'] . PHP_EOL);// escreve no ficheiro 
            file_put_contents("files/".$_POST['nome']."/log.txt",  $_POST['hora'] .";". $_POST['valor'] . PHP_EOL, FILE_APPEND);// escreve no ficheiro 
        }
        else
        {
            echo "sensor/atuador inexistente";
            http_response_code(400);// parametro invalido ou incompleto

        }
    }    
    else
    {
        echo "parametros em falta/invalidos";
        http_response_code(400);// parametro em falta
    }
        
}
else if($_SERVER['REQUEST_METHOD'] == "GET")// Pediu get?
{
    if(isset($_GET['nome']))
    {
        if($_GET['nome']=="temperatura")  // verificar se existe e qual o sensor/atuador 
        {
            $temperatura_hora = file_get_contents("files/".$_GET['nome']."/hora.txt"); //buscar a hora (guarda na variavel)
            $temperatura_valor = file_get_contents("files/".$_GET['nome']."/valor.txt"); // buscar o valor (guarda na variavel)
            $temperatura_nome = $_GET['nome'];
            echo $temperatura_valor;

        } 
        elseif( $_GET['nome']=="humidade" )  // verificar se existe e qual o sensor/atuador 
        {
            $humidade_hora = file_get_contents("files/".$_GET['nome']."/hora.txt"); //buscar a hora (guarda na variavel)
            $humidade_valor = file_get_contents("files/".$_GET['nome']."/valor.txt"); // buscar o valor (guarda na variavel)
            $humidade_nome = $_GET['nome'];
            echo $humidade_valor;

        } 
        else if($_GET['nome']=="led")  // verificar se existe e qual o sensor/atuador 
        {
            $led_hora = file_get_contents("files/".$_GET['nome']."/hora.txt"); //buscar a hora (guarda na variavel)
            $led_valor = file_get_contents("files/".$_GET['nome']."/valor.txt"); // buscar o valor (guarda na variavel)
            $led_nome = $_GET['nome'];
            echo $led_valor;
        }
        else if($_GET['nome']=="semaforo")  // verificar se existe e qual o sensor/atuador 
        {
            $ledRGB_hora = file_get_contents("files/".$_GET['nome']."/hora.txt"); //buscar a hora (guarda na variavel)
            $ledRGB_valor = file_get_contents("files/".$_GET['nome']."/valor.txt"); // buscar o valor (guarda na variavel)
            $ledRGB_nome = $_GET['nome'];
            echo $ledRGB_valor;
        }
        else if($_GET['nome']=="alarme")  // verificar se existe e qual o sensor/atuador 
        {
            $alarme_hora = file_get_contents("files/".$_GET['nome']."/hora.txt"); //buscar a hora (guarda na variavel)
            $alarme_valor = file_get_contents("files/".$_GET['nome']."/valor.txt"); // buscar o valor (guarda na variavel)
            $alarme_nome = $_GET['nome'];
            echo $alarme_valor;
        }
        else if($_GET['nome']=="avoid")  // verificar se existe e qual o sensor/atuador 
        {
            $avoid_hora = file_get_contents("files/".$_GET['nome']."/hora.txt"); //buscar a hora (guarda na variavel)
            $avoid_valor = file_get_contents("files/".$_GET['nome']."/valor.txt"); // buscar o valor (guarda na variavel)
            $avoid_nome = $_GET['nome'];
            echo $avoid_valor;
        } 
        else if($_GET['nome']=="luminosidade")  // verificar se existe e qual o sensor/atuador 
        {
            $luminosidade_hora = file_get_contents("files/".$_GET['nome']."/hora.txt"); //buscar a hora (guarda na variavel)
            $luminosidade_valor = file_get_contents("files/".$_GET['nome']."/valor.txt"); // buscar o valor (guarda na variavel)
            $luminosidade_nome = $_GET['nome'];
            echo $luminosidade_valor;
        } 
    }
    else
    {
        echo "parametro inixistente";
        http_response_code(400);
    }    
   
}
else //Não pediu post nem get
{
    http_response_code(403);
    echo "metodo nao permitido";    
}

?>