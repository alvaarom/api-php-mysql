<?php 
  require_once 'connection/connection.php';
  require_once 'response.class.php';

  class auth extends connection {

    public function login($json){
      $_response = new response;

      $data = json_decode($json,true);

      if(!isset($data['user']) || !isset($data['password'])){
        //error falta algun campo
        return $_response->err_400();
      }else{
        //ok
        $user = $data['user'];
        $password = $data['password'];
        $password = parent::encrypt($password);

        $data = $this->getDataUser($user);
        
        if($data){
          //si existe el usuario
          //verificar la contraseña
          if($password == $data[0]['Password']){
            if($data[0]['Estado'] == 'Activo'){
              $verify = $this->insertToken($data[0]['UsuarioId']);
              if($verify){
                //se guardo
                $result = $_response->response;
                $result["result"] = array(
                  "token" => $verify
                );
                return $result;
              }else{
                //no se guardo
                return $_response->err_500("Internal Server Error, no se genero el token");
              }
            }else{
              //si el usuario esta inactivo
              return $_response->err_200("El usuario esta inactivo");
            }
          }else{
            //contraseña incorrecta
            return $_response->err_200("Contraseña incorrecta");
          }
        }else{
          //no existe el usuario
          return $_response->err_200("El usuario $user no existe");
        }
      }
    }

    private function getDataUser($email){
      $query = "SELECT UsuarioId,Password,Estado FROM usuarios WHERE Usuario = '$email'";
      $data = parent::getData($query);
      if(isset($data[0]['UsuarioId'])){
        return $data;
      }else{
        return 0;
      }
    }

    private function insertToken($userId){
      $val = true;
      $token = bin2hex(openssl_random_pseudo_bytes(16,$val));
      $date = date("Y-m-d H:i");
      $status = "Activo";
      $query = "INSERT INTO usuarios_token (UsuarioId,Token,Estado,Fecha) VALUES('$userId','$token','$status','$date')";
      $verify = parent::nonQuery($query);
      if($verify){
        return $token;
      }else{
        return 0;
      }
    }

  }
?>