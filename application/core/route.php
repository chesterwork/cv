<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of route
 *
 * @author Dir
 */
class Route {

    static function start() {
        // kontroller i dejstvie po umolchaniju
        $controller_name = 'Main';
        $action_name = 'index';

        $routes = explode('/', $_SERVER[REQUEST_URI]);

        // poluchaem imja kontrollera
        if (!empty($routes[1])) {
            $controller_name = $routes[1];
        }

        // poluchaem imja ekshena
        if (!empty($routes[2])) {
            $action_name = $routes[2];
        }

        // dobavljaem prefiksy
        $model_name = 'Model_' . $controller_name;
        $controller_name = 'Controller_' . $controller_name;
        $action_name = 'action_' . $action_name;

        // Podcepljaem falj s klassom modeli (fajla modeli mozet i ne bytj)

        $model_file = strtolower($model_name) . '.php';
        $model_path = "application/models/" . $model_file;
        if (file_exists($model_path)) {
            include "application/models" . $model_file;
        }

        // podcepljaem fajl s klassom kontrollera
        $controller_file = strtolower($controller_name) . '.php';
        $contoller_path = "applecation/controllers/" . $controller_file;
        if (file_exists($contoller_path)) {
            include "application/controllers/" . $controller_file;
        } else {
            /*
             praviljno by bylo kinutj zdesj iskljuchenie,
             * no dlja uprowenija sdelaem redirect na stranicu 404
             */
            Route::Error404();
        }
        
        // sozdaem kontroller
        $controller = new $controller_name;
        $action = $action_name;
        
        if (method_exists($controller, $action)) {
            // vyzyvaem dejstvie kontrollera
            $controller->$action();
        } else {
            // zdesj tak ze razumnee bylo by kinutj iskljuchenie
            Route::ErrorPage404;
        }
    }
    
    function ErrorPage404() {
        $host = 'http://'.$_SERVER['HTTP_HOST'].'/';
        header('HTTP/1.1 404 Not Found');
        header("Status: 404 Not Found");
        header('Location:'.$host.'404');
    }

}
