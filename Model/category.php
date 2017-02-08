<?php

namespace Model;
use Library\Model;

class Category extends Model
{

    public function getCurrentCategory($alias_id = null)
    {
        $alias_id = (int)$alias_id;
        $sql = "SELECT * FROM `catigories` WHERE 1";
        $result = $this->db->query($sql);
        return $result;
    }
}