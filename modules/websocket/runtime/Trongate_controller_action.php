<?php

const APP_ROOT = __DIR__ . '/../../../';
const MODULES_ROOT = APP_ROOT . 'modules';
const ENGINE_ROOT = APP_ROOT . 'engine';

spl_autoload_register(function ($class_name) {

    $class_name = str_replace('alidation_helper', 'alidation', $class_name);
    $target_filename = realpath(ENGINE_ROOT . '/' . $class_name . '.php');

    if (file_exists($target_filename)) {
        return require_once($target_filename);
    }

    return false;
});

/**
 * The reason this is a class on it's own is
 * because it's the only place where autoloading is useful
 * e.g. instantiating a trongate controller which extends Trongate 
 */
class Trongate_controller_action {
    public function call(array $json, array $client) {
        $module = $json['module'] ?? null;
        $controller = $json['controller'] ?? ucwords($module);
        $action = $json['action'] ?? '_on_websocket_message';

        $controller_path = MODULES_ROOT . '/' . $module . '/controllers/' . $controller . '.php';
        if (file_exists($controller_path)) {
            require_once $controller_path;
            $controllerInstance = new $controller($module);
            return $controllerInstance->$action($json, $client);
        }

        return "Error: Controller [$controller] not found.";
    }
}