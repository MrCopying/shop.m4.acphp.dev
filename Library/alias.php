<?php

namespace Library;

class Alias
{

    protected $parents;

    protected $params;

    protected $controller;

    protected $action;

    /**
     * @return array
     */
    public function getParents()
    {
        return $this->parents;
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
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @return string
     */
    public function getController()
    {
        return $this->controller;
    }

    public function __construct(array $alias_uri)
    {
        $where = '';
        foreach ($alias_uri as $value){
            $where.= "'" . App::$db->escape(strtolower($value)) . "',";
        }
        $where = substr($where, 0, -1);
        $sql = "SELECT * FROM `alias` WHERE uri IN ({$where})";

        $result = App::$db->query($sql);
        if( $result ){
            $this->checkCategories($alias_uri, $result);
        }else{
            return false;
        }
    }

    private function checkCategories(&$alias_uri, &$resSql){
        $parentCategory = 0;
        for($i = 0; $i < count($alias_uri); $i++){
            $isFindUri = false;
            foreach ($resSql as $item) {
                if( ( $item['is_category'] OR !$this->parents )        //optimal search
                    AND (strtolower($alias_uri[$i]) == $item['uri'])  //first condition
                    AND ($item['parent_id'] == $parentCategory)          //find parent
                    AND (App::hasController($item['controller'])))    //check controller
                {
                    $this->parents[] = $item;
                    $parentCategory = $item['id'];
                    $this->controller = $item['controller'];
                    $isFindUri = true;
                }
            }
            if(!$isFindUri){
                if (App::hasMethodController($this->controller, $alias_uri[$i])){
                    //$this->parents[$i-1]['action'] = $alias_uri[$i];
                    $this->action = $alias_uri[$i];
                    $i++;
                }
                for( $item = $i; $item < count($alias_uri); $item++){
                    $this->params[] = $alias_uri[$item];
                }
                break;
            }
        }
    }
}