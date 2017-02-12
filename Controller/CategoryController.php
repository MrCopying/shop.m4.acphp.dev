<?php

namespace Controller;

use Library\Controller;
use Model\Category;

class CategoryController extends Controller
{
    public $currentCategory = 0;

    public function __construct($data = array())
    {
        parent::__construct($data);
        $this->model = new Category();
        if ($this->alias) {
            $this->currentCategory = $this->model->getIdCategoryFromAlias(
                    $this->alias[count($this->alias) - 1]['id']
            );
        }
    }
    public function index(){
        //Текущая категория и ее товары
        if ($this->currentCategory){
            $id = &$this->currentCategory['id'];
            $this->data['curr_cat'] = $this->currentCategory;
            $this->data['curr_products'] = $this->model->getProductList($id, '');
        }else{
            $id = 0;
        }
        //
        $this->data['cats'] = $this->model->getCategory($id);
        //var_dump($this->data);
        //$this->model->createProduct();die();
        foreach ($this->data['cats'] as $cat){
            $this->data['products'][$cat['id']] = $this->model->getProductList($cat['id'], '');
        }
        return 'category\index.twig';
    }
    public function view()
    {
        if( isset($this->params[0]) ){

        }
        return 'category\index.twig';
    }
/*
 * TODO товары в катигориях находяться в двух таблицах!
 * 1. Товар должен иметь Родную категорию (в таб. Товаров)
 * 2. Другая категория может содержать тот же товар (в таб. Категории_Товары)
 * TODO ?? Домашняя категория может содержать чужие товары? использовать таб Категории_Товары
 */
}