<?php

namespace Library;

class App{

    protected static $router;

    public static $db;

    public static $error;

    /**
     * @return mixed
     */
    public static function getRouter()
    {
        return self::$router;
    }

    public static function connect($uri)
    {
        try {
            self::$db = new DB(Config::get('db.host'), Config::get('db.user'), Config::get('db.password'), Config::get('db.db_name'));
            try{
                self::$router = new Router($uri);
                !Config::get('isMultiLanguages') ?: Lang::load(self::$router->getLanguage());
            } catch ( \Exception $exception){
                if ($exception->getCode() == 404){
                    self::$error = 404;
                    header('HTTP/1.0 404 Not Found',true,404);
                    echo 'Call view 404 page!';
                }
            };
        } catch ( \Exception $exception){
            self::$error = 100;
            echo 'Call view NOT DB : ' . $exception->getMessage();
        }
    }
    public static function run()
    {
        $controller_class = 'Controller\\' . ucfirst(self::$router->getController()) . 'Controller';
        $controller_method = strtolower(self::$router->getMethodPrefix() . self::$router->getAction());

        $layout = self::$router->getRoute();
        if ($layout == 'admin' && Session::get('role') != 'admin') {
            if ($controller_method != 'admin_login') {
                Router::redirect('/admin/users/login');
            }
        }


        $controller_object = new $controller_class();
        // Calling controller's method
        // Controller's action may return a view path
        $view_path = $controller_object->$controller_method();

        $view_object = new View($controller_object->getData(), $view_path);
        $content = $view_object->render();

        $layout_path = VIEW_DIR . $layout . '.html';
        $layout_view_object = new View(compact('content'), $layout_path);
        echo $layout_view_object->render();
    }
    public static function hasController($controller){
        return class_exists('Controller\\' . ucfirst(strtolower($controller)) . 'Controller');
    }

    public static function hasMethodController($controller, $method){
        if( self::hasController($controller) ){
            return method_exists('Controller\\' . ucfirst(strtolower($controller)) . 'Controller', $method );
        }
        return false;
    }
}