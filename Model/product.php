<?php

namespace Model;
use Library\Model;

class Product extends Model
{

    private
    function createPrice(){
        $maxCost = 15000;
        $minCost = 11340;
        $difCostOld = 500;
        $percent = 20;
        $usePercent = 0;
        $idProductStart = 231;
        $idProductFinish = 258;
        $sql = "INSERT INTO `price` (
                  `product_id`,
                  `cost`, 
                  `cost_old`,
                  `percent`,
                  `user_id`
                 ) VALUE ";

        for($i = $idProductStart; $i<=$idProductFinish; $i++){
            $cost =  round(rand($minCost*100, $maxCost*100)/100, 2);
            if($usePercent){
                $cost_old = round($cost * 100 / (100 - $percent), 2);
            }else{
                $cost_old = round($cost + rand(50,$difCostOld), 2);
                $percent = null;
            }
            $sql.="('{$i}', '{$cost}', '{$cost_old}', '{$percent}', '0')";
            $sql.= $i<$idProductFinish ? ',' : ';';
        }
        var_dump($sql);
        $this->db->query($sql);
}
}