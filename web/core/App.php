<?php


class App{
    protected $defaultController="home";
    protected $defaultMethod="index";
    protected $params=[];           //default parameters

    public function __construct(){

        $url=$this->parseURL();
        
        /* 
            The url mentioned by the user will be like:

            localhost/<controller>/<method>/<parameters...>

            $url will hold the array which will have

            [<controller>, <method>, <parameters>...]

            So, $url[0] will be a controller,
            $url[1] will be a method and $url[2 ... onwards] will be the parameters that are
            to be passed to the method.
        */
        $controller=$this->defaultController;
        if(file_exists('../app/controllers/'.$url[0].'.php')){  //check if the controller file exists
            $controller=$url[0];
        }

        //if the controller is not available, then we already have set 'home' as default controller
        require_once '../app/controllers/'.$controller.'.php';
        
        $controllerObj=new $controller();
        
        $method=$this->defaultMethod;


        if(isset($url[1])){     //check if a method is set in the url

            if(method_exists($controllerObj,$url[1])){
                $method=$url[1];
            }
            //if the method doesn't exists, then we have already have set 'index' as default method
        }

        if(isset($url[2])){ //check if the params have been set
            $this->params=array_slice($url,2); //the array elements apart from controller and method in $url are parameters.
        }

        call_user_func_array([$controllerObj,$method],$this->params);
    }

    private function parseURL(){

        if($_GET['url']){
            $url=rtrim($_GET['url'],'/');   //trim-out trailing slashes like in "www.github.com/"
            $url=filter_var($url, FILTER_SANITIZE_URL); //sanitize URL
            return explode('/',$url);   //return each of the parameters as an array
        }

    }
    
    

}