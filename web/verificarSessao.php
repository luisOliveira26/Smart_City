<?php 
// Verfica se a sessão existe, caso contrário redireciona para a página de login
session_start();
if (!isset($_SESSION['username']) && !isset($_SESSION['permissoes'])) {
  die(header("Location: index.php"));
}else{
  $permissoes = $_SESSION['permissoes'];
}

?>