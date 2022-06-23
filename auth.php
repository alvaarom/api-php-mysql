<?php 

  require_once "class/auth.class.php";
  require_once "class/response.class.php";
  
  $_auth = new auth;
  $_response = new response;

  if($_SERVER['REQUEST_METHOD']== "POST"){
    //Recibir datos
    $postBody = file_get_contents('php://input');

    //Enviamos al manejador
    $arrayData = $_auth->login($postBody);

    //Devolvemos una respuesta
    header('Content-Type: application/json');
    if(isset($arrayData['result']['err_id'])){
      $responseCode = $arrayData['result']['err_id'];
      http_response_code($responseCode);
    }else{
      http_response_code(200);
    }

    echo json_encode($arrayData);
  }else{
    header('Content-Type: application/json');
    $arrayData = $_response->err_405();
    echo json_encode($arrayData);
  }

?>