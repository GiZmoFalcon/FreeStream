<?php
require_once '/var/www/html/core/Controller.php';

class Home extends Controller{


    public function index($name='',$surname=''){
        $data=array($name,$surname);
        parent::view('home',$data);
    }

}