<?php

namespace Grelo\Core;

class Loader
{
    static public function cargarControlador($controller)
    {
        $controllersPath = defined(CONTROLLERS_PATH) ? CONTROLLERS_PATH : __DIR__ . '/../../../../../app/Controllers/';
        $controlador = ucwords($controller) . 'Controller';
        $strFileController = $controllersPath . $controlador . '.php';

        if (!is_file($strFileController)) {
            $strFileController = $controllersPath . ucwords(DEFAULT_CONTROLLER) . 'Controller.php';
        }

        require_once $strFileController;
        $controllerObj = new $controlador();
        return $controllerObj;
    }

    static public function cargarAccion($controllerObj, $action, $params = null)
    {
        $accion = $action;
        if (is_array($params)) {
            call_user_func_array(array($controllerObj, $accion), $params);
        } else {
            call_user_func(array($controllerObj, $accion), $params);
        }
    }

    static public function to($input, $params = null) {
        [$controlador, $accion] = explode('#', $input);
        $controllerObj = self::cargarControlador($controlador);
        self::cargarAccion($controllerObj, $accion, $params);
    }

}
