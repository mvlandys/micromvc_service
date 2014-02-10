<?php
namespace Matheos\App;

class Error extends \Matheos\MicroMVC\Controller {
    public function errorHandler($errno, $errstr, $errfile, $errline) {
        $this->json->set_Data("");
        $this->json->set_Error("Error: {$errstr}\nLine {$errline}: {$errfile}");
        $this->json->send();

        $AppConfig = \Matheos\MicroMVC\AppConfig::getInstance();
        $LogErrors = $AppConfig->config->Site->logErrors;

        if ($LogErrors == "true") {
            $this->model->insert_Error($errstr, $errno);
        }

        exit(1);
    }

    public function exceptionHandler($e) {
        $this->json->set_Data("");
        $this->json->set_Error("Exception :{$e->getMessage()}");
        $this->json->send();

        $AppConfig = \Matheos\MicroMVC\AppConfig::getInstance();
        $LogErrors = $AppConfig->config->Site->logErrors;

        if ($LogErrors == "true") {
            $this->model->insert_Error($e->getMessage(), $e->getCode(), $e->getTrace());
        }

        exit(1);
    }

    public function shutdownHandler() {
        $error = error_get_last();
        if (!empty($error)) {
            $errno   = $error["type"];
            $errstr  = $error["message"];
            $errfile = $error["file"];
            $errline = $error["line"];

            $trace   = array(array(
                "line"  => $errline,
                "file"  => $errfile
            ));

            $this->json->set_Data("");
            $this->json->set_Error("Fatal Error: {$errstr}\nLine {$errline}: {$errfile}");
            $this->json->send();

            $AppConfig = \Matheos\MicroMVC\AppConfig::getInstance();
            $LogErrors = $AppConfig->config->Core->logErrors;

            if ($LogErrors == "true") {
                $this->model->insert_Error($errstr, $errno, $trace);
            }
        }
    }
}
