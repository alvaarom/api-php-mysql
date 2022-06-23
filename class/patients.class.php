<?php 
  require_once 'connection/connection.php';
  require_once 'response.class.php';

  class patients extends connection {
    private $table = "pacientes";
    private $patientId = "";
    private $dni = "";
    private $name = "";
    private $address = "";
    private $postalcode = "";
    private $gender = "";
    private $phone = "";
    private $birthday = "0000-00-00";
    private $email = "";
    private $token = "";

    public function listPatients($pag = 1){
      $inicio = 0;
      $cantidad = 100;

      if($pag > 1){
        $inicio = ($cantidad * ($pag -1))+1;
        $cantidad = $cantidad * $pag;
      }

      $query = "SELECT PacienteId,Nombre,DNI,Telefono,Correo FROM " . $this->table . " limit $inicio,$cantidad";
      $data = parent::getData($query);
      return ($data);
    }

    public function getPatient($id){
      $query = "SELECT * FROM " . $this->table . " WHERE PacienteId = '$id'";
      return parent::getData($query);
    }

    public function post($json) {
      $_response = new response;
      $data = json_decode($json,true);

      if(!isset($data['token'])){
        return $_response->err_401();
      }else{
        $this->token = $data['token'];
        $arrayToken = $this->searchToken();
        if($arrayToken){
          if(!isset($data['name']) || !isset($data['dni']) || !isset($data['email'])){
            return $_response->err_400();
          }else{
            $this->nombre = $data['name'];
            $this->dni = $data['dni'];
            $this->email = $data['email'];
            if(isset($data['phone'])) {$this->phone = $data['phone'];}
            if(isset($data['address'])) {$this->address = $data['address'];}
            if(isset($data['postalcode'])) {$this->postalcode = $data['postalcode'];}
            if(isset($data['gender'])) {$this->gender = $data['gender'];}
            if(isset($data['birthday'])) {$this->birthday = $data['birthday'];}
            $resp = $this->insertPatient();
            if($resp){
              $respuesta = $_response->response;
              $respuesta["result"] = array(
                "pacienteId" => $resp
              );
              return $respuesta;
            }else{
              return $_response->err_500();
            }
          }
        }else{
          return $_response->err_401('Invalid or expired token');
        }
      }
    }

    public function put($json) {
      $_response = new response;
      $data = json_decode($json,true);

      if(!isset($data['token'])){
        return $_response->err_401();
      }else{
        $this->token = $data['token'];
        $arrayToken = $this->searchToken();
        if($arrayToken){
          if(!isset($data['patientId'])){
            return $_response->err_400();
          }else{
            $this->patientId = $data['patientId'];
            if(isset($data['patientId'])) {$this->patientId = $data['patientId'];}
            if(isset($data['dni'])) {$this->dni = $data['dni'];}
            if(isset($data['email'])) {$this->email = $data['email'];}
            if(isset($data['name'])) {$this->name = $data['name'];}
            if(isset($data['phone'])) {$this->phone = $data['phone'];}
            if(isset($data['address'])) {$this->address = $data['address'];}
            if(isset($data['postalcode'])) {$this->postalcode = $data['postalcode'];}
            if(isset($data['gender'])) {$this->gender = $data['gender'];}
            if(isset($data['birthday'])) {$this->birthday = $data['birthday'];}
            $resp = $this->updatePatient();
            if($resp){
              $respuesta = $_response->response;
              $respuesta["result"] = array(
                "pacienteId" => $this->patientId
              );
              return $respuesta;
            }else{
              return $_response->err_500();
            }
          }
        }else{
          return $_response->err_401('Invalid or expired token');
        }
      }
    }

    public function delete($json) {
      $_response = new response;
      $data = json_decode($json,true);

      if(!isset($data['token'])){
        return $_response->err_401();
      }else{
        $this->token = $data['token'];
        $arrayToken = $this->searchToken();
        if($arrayToken){
          if(!isset($data['patientId'])){
            return $_response->err_400();
          }else{
            $this->patientId = $data['patientId'];
            
            $resp = $this->deletePatient();
            if($resp){
              $respuesta = $_response->response;
              $respuesta["result"] = array(
                "pacienteId" => $this->patientId
              );
              return $respuesta;
            }else{
              return $_response->err_500();
            }
          }
        }else{
          return $_response->err_401('Invalid or expired token');
        }
      }
    }

    private function insertPatient(){
      $query = "INSERT INTO " . $this->table . " (DNI,Nombre,Direccion,CodigoPostal,Telefono,Genero,FechaNacimiento,Correo)
      values
      ('" . $this->dni . "','" . $this->name . "','" . $this->address . "','" . $this->postalcode . "','" . $this->phone . "','" . $this->gender . "','" . $this->birthday . "','". $this->email . "')";
      $resp = parent::nonQueryId($query);
      if($resp){
        return $resp;
      }else{
        return 0;
      }
    }

    private function updatePatient(){
      $query = "UPDATE " . $this->table . " SET Nombre='" . $this->name . "', Direccion ='" . $this->address . "', DNI='" . $this->dni . "', CodigoPostal='" . $this->postalcode . "', Telefono='" . $this->phone . "', Genero='" . $this->gender . "', FechaNacimiento='" . $this->birthday . "', Correo='" . $this->email . 
      "' WHERE PacienteId='" . $this->patientId . "'";
      $resp = parent::nonQuery($query);
      if($resp >= 1){
        return $resp;
      }else{
        return 0;
      }
    }

    private function deletePatient(){
      $query = "DELETE FROM " . $this->table . " WHERE PacienteId='" . $this->patientId . "'";
      $resp = parent::nonQuery($query);
      if($resp >= 1){
        return $resp;
      }else{
        return 0;
      }
    }

    private function searchToken(){
      $query = "SELECT TokenId,UsuarioId,Estado FROM usuarios_token WHERE Token='" . $this->token . "' AND Estado='Activo'";
      $resp = parent::getData($query);
      if($resp){
        return $resp;
      }else{
        return 0;
      }
    }

    private function updateToken($tokenId){
      $date = date("Y-m-d H:i");
      $query = "UPDATE usuarios_token SET Fecha='$date' WHERE TokenId='$tokenId'";
      $resp = parent::nonQuery($query);
      if($resp >= 1){
        return $resp;
      }else{
        return 0;
      }
    }
  }

?>