<?php 

class connection {
  private $host;
  private $user;
  private $password;
  private $database;
  private $port;
  private $connection;

  function __construct() {
    $data = $this->connectionData();
    foreach ($data as $key => $value) {
      $this->host = $value['host'];
      $this->user = $value['user'];
      $this->password = $value['password'];
      $this->database = $value['database'];
      $this->port = $value['port'];
    }
    try {
      $this->connection = new mysqli($this->host,$this->user,$this->password,$this->database,$this->port);
    } catch (\Throwable $th) {
      //throw $th;
      echo 'Connection DB error.';
      die();
    }
  }

  private function connectionData() {
      $path = dirname(__FILE__);
      $jsondata = file_get_contents($path. '/'. 'config');
      return json_decode($jsondata, true);
  }

  private function encodeUTF8($array){
    array_walk_recursive($array, function(&$item, $key){
      if(!mb_detect_encoding($item,'utf-8',true)){
        $item = utf8_encode($item);
      }
    });
    return $array;
  }

  //SELECT
  public function getData($query){
    $results = $this->connection->query($query);
    $resultArray = array();
    foreach ($results as $key) {
      $resultArray[] = $key;
    }
    return $this->encodeUTF8($resultArray);
  }

  //INSERT (Devuelve la cantidad de filas agregadas)
  public function nonQuery($query){
    $results = $this->connection->query($query);
    return $this->connection->affected_rows;
  }

  //INSERT (Devuelve el id de la fila insertada)
  public function nonQueryId($query){
    $results = $this->connection->query($query);
    $rows = $this->connection->affected_rows;
    if($rows >= 1){
      return $this->connection->insert_id;
    }else{
      return 0;
    }
  }

  //Encrypt
  protected function encrypt($string){
    return md5($string);
  }
}

?>