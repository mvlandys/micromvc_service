<?php
namespace Matheos\App;

class Main extends \Matheos\MicroMVC\Controller {
    public function index() {
        $this->json->set_Data("Welcome to the MicroMVC JSON service!");
        $this->json->send();
    }
}