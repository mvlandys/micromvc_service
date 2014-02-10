<?php
/**
 * Controller Class
 *
 * The Core Controller Class
 *
 * @package    MicroMVC
 * @author     Mathew Vlandys <mvlandys@gmail.com>
 * @license    https://www.apache.org/licenses/LICENSE-2.0.html  Apache License v2.0
 *
 */

namespace Matheos\MicroMVC;

class Controller
{
    protected $model;

    public function __construct()
    {
        $this->json = new JsonResponse();

        $modelFile  = $this->className() . "Model";
        $modelClass = "\\Matheos\\App\\" . $modelFile;

        $cfg  = AppConfig::getInstance()->config;
        $base = $cfg->Core->rootFolder;

        if (file_exists($base . "/App/models/{$modelFile}.php")) {
            $this->model = new $modelClass;
        }
    }

    public function className()
    {
        $class = explode('\\', get_class($this));
        return end($class);
    }
}
