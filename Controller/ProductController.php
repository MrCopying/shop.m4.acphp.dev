<?php

namespace Controller;

use Library\Controller;
use Model\Product;

class ProductController extends Controller{

    public function __construct(array $data = null)
    {
        parent::__construct($data);
        $this->model = new Product();
    }

    public function index()
    {
        //$this->model->createPrice();
    }
}