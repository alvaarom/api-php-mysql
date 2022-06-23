<?php 

  require_once 'class/response.class.php';
  require_once 'class/patients.class.php';

  $_response = new response;
  $_patients = new patients;

  if($_SERVER['REQUEST_METHOD'] == "GET"){
    if(isset($_GET["page"])){
      $pagina = $_GET["page"];
      $listPatients = $_patients->listPatients($pagina);
      header('Content-Type: application/json');
      http_response_code(200);
      echo json_encode($listPatients);
    }else if(isset($_GET["id"])) {
      $patientId = $_GET["id"];
      $patientData = $_patients->getPatient($patientId);
      header('Content-Type: application/json');
      http_response_code(200);
      echo json_encode($patientData);
    }
    else{
      $listPatients = $_patients->listPatients();
      echo json_encode($listPatients);
    }
  }else if($_SERVER['REQUEST_METHOD'] == "POST"){
    //Recibimos los datos
    $postBody = file_get_contents("php://input");
    //Enviamos los datos al manejador
    $arrayData = $_patients->post($postBody);

    //Devolvemos una respuesta
    header('Content-Type: application/json');
    if(isset($arrayData['result']['err_id'])){
      $responseCode = $arrayData['result']['err_id'];
      http_response_code($responseCode);
    }else{
      http_response_code(200);
    }
    echo json_encode($arrayData);
  }else if($_SERVER['REQUEST_METHOD'] == "PUT"){
    //Recibimos los datos
    $postBody = file_get_contents("php://input");
    //Enviamos los datos al manejador
    $arrayData = $_patients->put($postBody);

    //Devolvemos una respuesta
    header('Content-Type: application/json');
    if(isset($arrayData['result']['err_id'])){
      $responseCode = $arrayData['result']['err_id'];
      http_response_code($responseCode);
    }else{
      http_response_code(200);
    }
    echo json_encode($arrayData);

  }else if($_SERVER['REQUEST_METHOD'] == "DELETE"){
    //Recibimos los datos
    $postBody = file_get_contents("php://input");
    //Enviamos los datos al manejador
    $arrayData = $_patients->delete($postBody);

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
  };

?>