<?php
namespace Matheos\MicroMVC;

class JsonResponse {
    private $error, $errorMsg, $data;

    function __construct() {
        $this->error = 0;
    }

    public function set_Data($data) {
        $this->data = $data;
    }

    public function set_Error($error) {
        $this->error    = 1;
        $this->errorMsg = $error;
    }

    public function send() {
        echo json_encode(array(
            "error"     => $this->error,
            "error_msg" => $this->errorMsg,
            "data"      => $this->data
        ));
    }
}