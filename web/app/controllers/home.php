<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/Controller.php';

class Home extends Controller{


    public function index($name='',$surname=''){
        $data=array($name,$surname);
        parent::view('home',$data);
    }

    public function test($name='',$surname=''){
        $data=array($name,$surname);
        parent::view('test',$data);
    }

}