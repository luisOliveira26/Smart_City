<?php
//echo $_SERVER['REQUEST_METHOD'];

if($_SERVER['REQUEST_METHOD'] == "GET")// Pediu get?
{
    if(isset($_GET['nome']))
    {
        if($_GET['nome']=="temperatura")  // verificar se existe e qual o sensor/atuador 
        {
            $temperatura_hora = file_get_contents("files/".$_GET['nome']."/hora.txt"); //buscar a hora (guarda na variavel)
            echo $temperatura_hora;
        } 
        elseif( $_GET['nome']=="humidade" )  // verificar se existe e qual o sensor/atuador 
        {
            $humidade_hora = file_get_contents("files/".$_GET['nome']."/hora.txt"); //buscar a hora (guarda na variavel)
            echo $humidade_hora;
        } 
        else if($_GET['nome']=="led")  // verificar se existe e qual o sensor/atuador 
        {
            $led_hora = file_get_contents("files/".$_GET['nome']."/hora.txt"); //buscar a hora (guarda na variavel)
            echo $led_hora;
        }
        else if($_GET['nome']=="semaforo")  // verificar se existe e qual o sensor/atuador 
        {
            $ledRGB_hora = file_get_contents("files/".$_GET['nome']."/hora.txt"); //buscar a hora (guarda na variavel)
            echo $ledRGB_hora;
        }
        else if($_GET['nome']=="alarme")  // verificar se existe e qual o sensor/atuador 
        {
            $alarme_hora = file_get_contents("files/".$_GET['nome']."/hora.txt"); //buscar a hora (guarda na variavel)
            echo $alarme_hora;
        }
        else if($_GET['nome']=="avoid")  // verificar se existe e qual o sensor/atuador 
        {
            $avoid_hora = file_get_contents("files/".$_GET['nome']."/hora.txt"); //buscar a hora (guarda na variavel)
            echo $avoid_hora;
        } 
        else if($_GET['nome']=="luminosidade")  // verificar se existe e qual o sensor/atuador 
        {
            $luminosidade_hora = file_get_contents("files/".$_GET['nome']."/hora.txt"); //buscar a hora (guarda na variavel)
            echo $luminosidade_hora;
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