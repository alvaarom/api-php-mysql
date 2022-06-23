<?php 
  class response{

    public $response = [
      'status' => 'OK',
      'result' => array()
    ];

    public function err_405(){
      $this->response['status'] = 'ERR';
      $this->response['result'] = array(
        'err_id' => '405',
        'err_msg' => 'Method not allowed'
      );
      return $this->response;
    }

    public function err_200($msg = 'Wrong data'){
      $this->response['status'] = 'ERR';
      $this->response['result'] = array(
        'err_id' => '200',
        'err_msg' => $msg
      );
      return $this->response;
    }

    public function err_400(){
      $this->response['status'] = 'ERR';
      $this->response['result'] = array(
        'err_id' => '400',
        'err_msg' => 'Wrong sent data or format.'
      );
      return $this->response;
    }

    public function err_500($msg = 'Internal server error'){
      $this->response['status'] = 'ERR';
      $this->response['result'] = array(
        'err_id' => '500',
        'err_msg' => $msg
      );
      return $this->response;
    }

    public function err_401($msg = 'Not authorized'){
      $this->response['status'] = 'ERR';
      $this->response['result'] = array(
        'err_id' => '401',
        'err_msg' => $msg
      );
      return $this->response;
    }

  }
?>