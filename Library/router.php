<?php

namespace Library;

class Router{

    protected $uri;
    // if not found controller use $alias
    protected $alias;

    protected $controller;

    protected $action;

    protected $params;

    protected $route;

    protected $method_prefix;

    protected $language;


    /**
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @return array
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * @return string
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @return string
     */
    public function getMethodPrefix()
    {
        return $this->method_prefix;
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    public function __construct($uri){
        $this->uri = urldecode(trim($uri,'/'));

        //Get defaults
        $routes = Config::get('routes');
        $this->route = Config::get('default_route');
        $this->method_prefix = isset($routes[$this->route])? $routes[$this->route]: '';
        $this->language = Config::get('default_language');
        $this->controller = Config::get('default_controller');
        $this->action = Config::get('default_action');

        $uri_path = explode('?', $this->uri);

        //Get path like ?route/lng/controller/action/param1/param2/.../...
        //Get path for alias ?route/?lng/?alias-category.../?alias||controller/?alias||action/param1/param2/.../...
        $path = $uri_path[0];

        $path_parts = explode('/', $path);

        if ( !empty($path_parts[0]) ) {
            //Get route at first element
            if (in_array(strtolower($path_parts[0]), array_keys($routes))) {
                $this->route = strtolower($path_parts[0]);
                $this->method_prefix = isset($routes[$this->route]) ? $routes[$this->route] : '';
                array_shift($path_parts);
            }
            // Get language uri if multi-languages
            if (Config::get('isMultiLanguages') and in_array(strtolower(current($path_parts)), Config::get('languages'))) {
                $this->language = strtolower(current($path_parts));
                array_shift($path_parts);
            }
            // Get controller - next element of array
            $item = current($path_parts);
            if ($item) {
                // check of controller class, else this alias
                if (App::hasController($item)) {
                    $this->controller = $item;
                    array_shift($path_parts);
                    // Get action
                    $item = strtolower(current($path_parts));
                    if (($item) AND (App::hasMethodController($this->controller, $this->method_prefix . $item))) {
                        $this->action = $item;
                        array_shift($path_parts);
                    }
                    //Get params - all the rest
                    $this->params = $path_parts;
                } else {
                    $alias = new Alias($path_parts);
                    $this->alias = $alias->getParents();
                    if ($this->alias) {
                        $this->controller = $alias->getController();
                        $this->params = $alias->getParams();
                        if ($alias->getAction()) {
                            $this->action = $alias->getAction();
                        };
                    } else {
                        //Page not found
                        throw new \Exception('Page not found', 404);
                    }
                }
            }
        }
    }

    public static function redirect($location)
    {
        header("Location: $location");
    }
}