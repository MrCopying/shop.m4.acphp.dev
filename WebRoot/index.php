<?php
use Library\App;
use Library\Lang;

define('DS',DIRECTORY_SEPARATOR);
define('ROOT', __DIR__ . DS . '..' . DS);
//define('ROOT', dirname(dirname(__FILE__)) . DS);
define('VIEW_DIR', ROOT . 'View' . DS);
define('LIB_DIR', ROOT . 'Library' . DS);
define('CONTROLLER_DIR', ROOT . 'Controller' . DS);
define('MODEL_DIR', ROOT . 'Model' . DS);
define('CONF_DIR', ROOT . 'Config' . DS);

$loader = require '../vendor/autoload.php';
$loader->add('', __DIR__ . '/../');

require_once(CONF_DIR . 'config.php');
session_start();

App::connect($_SERVER['REQUEST_URI']);
if( !App::$error ) {
    App::run();
}else{
    echo 'Eror: ' . App::$error;
};

/*
TODO @MrCopying: Add Menu

*/
/*
function __autoload($class_name)
{
    $lib_path = ROOT.DS.'Library'.DS.strtolower($class_name).'.class.php';
    $controllers_path = ROOT.DS.'controllers'.DS.str_replace('controller','', strtolower($class_name)).'.controller.php';
    $model_path = ROOT.DS.'models'.DS.strtolower($class_name).'.php';

    if(file_exists($lib_path)){
        require_once($lib_path);
    }elseif(file_exists($controllers_path)){
        require_once($controllers_path);
    }elseif(file_exists($model_path)){
        require_once($model_path);
    }
//    else{
//        throw new Exception('Failed to include class: '.$class_name);
//    }
}*/



function __($key, $default_value = '')
{
    return Lang::get($key, $default_value);
}
//Session::setFlash('Test flash message');
