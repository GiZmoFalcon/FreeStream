<?php

class App{
    protected $controller='home';
    protected $method='index';
    protected $params=[];

    public function __construct(){
        $par=$this->parseURL();
        print_r($par);
    }

    private function parseURL(){
        if($_GET['url']){
            $url=rtrim($_GET['url'],'/');   //trim-out trailing slashes like in "www.github.com/"
            $url=filter_var($url, FILTER_SANITIZE_URL); //sanitize URL
            return explode('/',$url);   //return each of the parameters as an array
        }
    }
    
    

}