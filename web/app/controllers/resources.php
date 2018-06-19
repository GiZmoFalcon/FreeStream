<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/Controller.php';

class Resources extends Controller{


    public function js($res){
        parent::getResoure($res);
    }

}