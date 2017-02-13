<?php

namespace Model;
use Library\Model;

class Category extends Model
{
    // Узнаем текущую категорию по id Алиаса
    public function getIdCategoryFromAlias($aliasId){
        $sql = "SELECT * FROM `category` WHERE alias_id = ${aliasId} LIMIT 1";
        return $this->db->query($sql)[0] ? $this->db->query($sql)[0] : false;
    }

    // Вернуть список под катигорий
    public function getCategory($parent = 0){
        $parent = $parent ? $this->db->escape((int)$parent) : 0;
        $sql = "SELECT * FROM `category` WHERE parent_id = '{$parent}'";
        return $this->db->query($sql);
    }

    // Вернуть список товаров привязаной Домашней катигории
    public function getProductList($category, $filter = '', $start = 0, $quantity = 5){
        $category = (int)$category;
        if ($category) {
            $start = (int)$start;
            $quantity = (int)$quantity;

            $sql = "SELECT 
                      p.id, 
                      p.category_id, 
                      p.name,
                      p.description, 
                      p.img_small, 
                      m.cost, 
                      m.cost_old, 
                      m.percent 
                    FROM product AS p 
                    LEFT JOIN price as m ON p.id = m.product_id 
                    WHERE 1 AND p.category_id = '${category}' 
                    LIMIT $start,$quantity ";
            return $this->db->query($sql);
            //var_dump($res);die();
            //return $res;
        }
        return false;
    }
    //Вернуть полный список товаров (с двух таблиц)
    public function getProductListAll(
        $category,
        $parentCategory = false,
        $filter = '',
        $start = 0,
        $quantity = 5
    ){
        $category = (int)$category;
        $parentCategory = (int)$parentCategory;
        $start = (int)$start;
        $quantity = (int)$quantity;
        if ($category) {
            $sql = 'SELECT 
                        p.id, 
                        p.category_id, 
                        p.name,
                        p.description, 
                        p.img_small, 
                        m.cost, 
                        m.cost_old, 
                        m.percent 
                    FROM product as p
                    LEFT JOIN price as m ON p.id = m.product_id
                    WHERE p.category_id = ' . "'${category}' ";
            $sql.=' UNION SELECT 
                        p.id, 
                        p.category_id, 
                        p.name,
                        p.description, 
                        p.img_small, 
                        m.cost, 
                        m.cost_old, 
                        m.percent 
                    FROM product as p
                    LEFT JOIN price as m ON p.id = m.product_id
                    JOIN category_product as cp ON p.id = cp.product_id
                    WHERE cp.is_active = 1 AND cp.category_id = ' . "'" . $category . "' ";
            $sql.= $parentCategory ? " AND p.category_id = '" . $parentCategory . "'" :' ORDER BY p.category_id ';
            $sql.= "LIMIT {$start},{$quantity} ";
            return $this->db->query($sql);
        }
        return false;
    }
    // Заполнение таблицы товарами !PRIVATE!!!
     private function createProduct(){
        $preName = 'NoteBooks ';
        $name = 'Sensory';//'Sumsung';//'Nokia';
        $desc = 'i5 2.3Ghz 4RAM 150G SSD';
        $Full = 'Full';
        $category_id = 17;//16;//7;
        $quantity = 25; $iStart = 10;
        $sql = 'INSERT INTO `product` ( `category_id`, `name`, `name_full`, `description`, `description_full`) VALUES ';

        for( $i = $iStart; $i <= $quantity; $i++){
            $iName = $preName . $name . ' ' . ($i*10+$i*1000). 'PS' . $i*20;
            $sql.= "( '${category_id}', '${iName}', '${iName}${Full}', '${iName}${desc}', '${iName}${desc}${Full}' )";
            $sql.= ($i < $quantity)? ',': ';';
        }
        $this->db->query($sql);
    }
}