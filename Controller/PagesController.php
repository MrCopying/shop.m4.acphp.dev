<?php

namespace Controller;

use Library\Controller;
//use Library\App;
use Library\Router;
use Library\Session;
use Model\Page;

class PagesController extends Controller{

    public function __construct($data = array())
    {
        parent::__construct($data);
        $this->model = new Page();
    }

    public function index(){
        //var_dump($this->alias);
        if ( $this->alias ){
            return $this->view($this->alias[count($this->alias)-1]['id']);
        }elseif($this->params){
            $this->view();
            if ($this->data['page']){
                Router::redirect('/pages/view/' . $this->params[0]);
            }else{
                throw new \Exception('Not found page: [' . $this->params[0] .']');
            }
        }else{
            $this->data['pages'] = $this->model->getList();
        }
        return 'pages\\index.twig';
    }

    public function view($idAlias = null){
        if($idAlias){
            $this->data['page'] = $this->model->getByAliasId($idAlias);
        }elseif( isset($this->params[0])){
            $this->data['page'] = $this->model->getByAlias(strtolower($this->params[0]));
        }else{
            throw new \Exception('Not param for Pages\View',303);
        }
        return 'pages\\view.twig';
    }

    public function admin_index()
    {
        $this->data['pages'] = $this->model->getList();
    }

    public function admin_add()
    {
        if ($_POST) {
           $result = $this->model->save($_POST);
            if ($result) {
                Session::setFlash('Page was saved.');
            } else {
                Session::setFlash('Error.');
            }
            Router::redirect('/admin/pages/');
        }
    }

    public function admin_edit()
    {
        if ($_POST) {
            $id = isset($_POST['id']) ? $_POST['id'] : null;
            $result = $this->model->save($_POST, $id);
            if ($result) {
                Session::setFlash('Page was saved.');
            } else {
                Session::setFlash('Error.');
            }
            Router::redirect('/admin/pages/');
        }

        if (isset($this->params[0])) {
            $this->data['page'] = $this->model->getById($this->params[0]);
        } else {
            Session::setFlash('Wrong page id.');
            Router::redirect('/admin/pages/');
        }
    }

    public function admin_delete()
    {
        if (isset($this->params[0])) {
            $result = $this->model->delete($this->params[0]);
            if ($result) {
                Session::setFlash('Page was saved.');
            } else {
                Session::setFlash('Error.');
            }
        }
        Router::redirect('/admin/pages/');
    }
}