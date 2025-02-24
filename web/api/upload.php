<?php

header('Content-Type: text/html; charset=utf-8');
//echo $_SERVER['REQUEST_METHOD'];



if($_SERVER['REQUEST_METHOD'] == "POST") // Pediu post?
{
    echo "recebi um post";
    print_r($_POST);

    if($_FILES['imagem'])
    {
        print_r($_FILES['imagem']);
        move_uploaded_file($_FILES['imagem']['tmp_name'], "imagens/webcam.jpg" );
        echo "tem imagem";
    }
    else
    {
        http_response_code(400);
        echo "Erro nos dados enviados – não existe elemento imagem";
    }
        
}
else //Não pediu post 
{
    http_response_code(403);
    echo "metodo nao permitido";    
}


?>