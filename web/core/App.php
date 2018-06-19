<?php

////////////////////////////////////////////////////////////////
// This is the main control center of the application.
// The execute() method is executed when the user loads the page.
// execute() method parses the URL, and determines which controller
// and method the user wants to execute.
///////////////////////////////////////////////////////////////

class App{
    ////////////////////////////////////////////////////////////////
    // Here is the default controller, default method and default
    // parameters that are to be considered if the user requests are
    // invalid
    ///////////////////////////////////////////////////////////////
    protected $defaultController="home";
    protected $defaultMethod="index";
    protected $params=[];

    public function __construct(){

    }

    public function execute(){

        $url=$this->parseURL();
        ////////////////////////////////////////////////////////////////
        // The URL mentioned by the user will be like:
        // localhost/<controller>/<method>/<parameters...>
        //
        // $url will hold the array which will have:
        // [<controller>, <method>, <parameters>...]
        //
        // So, $url[0] will be a controller, $url[1] will be a method and
        // $url[2 ... onwards] will be the parameters that are to be 
        // passed to the method.
        ///////////////////////////////////////////////////////////////

        $controller=$this->defaultController;

        ////////////////////////////////////////////////////////////////
        // check if the controller file exists
        ///////////////////////////////////////////////////////////////
        if(file_exists($_SERVER['DOCUMENT_ROOT'].'/app/controllers/'.$url[0].'.php')){
            $controller=$url[0];
        }

        
        ////////////////////////////////////////////////////////////////
        // if the controller is not available, then we already have set 
        // 'home' as default controller.
        ///////////////////////////////////////////////////////////////
        require_once $_SERVER['DOCUMENT_ROOT'].'/app/controllers/'.$controller.'.php';
        
        $controllerObj=new $controller();
        
        $method=$this->defaultMethod;

        ////////////////////////////////////////////////////////////////
        // check if a method is set in the url
        ///////////////////////////////////////////////////////////////
        if(isset($url[1])){

            if(method_exists($controllerObj,$url[1])){
                $method=$url[1];
            }
            ////////////////////////////////////////////////////////////////
            // if the method doesn't exists, then we have already have set
            // 'index' as default method
            ///////////////////////////////////////////////////////////////
            
        }

        ////////////////////////////////////////////////////////////////
        // check if the params have been set
        ///////////////////////////////////////////////////////////////
        if(isset($url[2])){ 
            ////////////////////////////////////////////////////////////////
            // the array elements apart from controller and method in 
            // $url are parameters.
            ///////////////////////////////////////////////////////////////
            $this->params=array_slice($url,2);
        }

        call_user_func_array([$controllerObj,$method],$this->params);
    }

    private function parseURL(){

        if($_GET['url']){
            ////////////////////////////////////////////////////////////////
            // trim-out trailing slashes like in "www.github.com/"
            ///////////////////////////////////////////////////////////////
            $url=rtrim($_GET['url'],'/');

            ////////////////////////////////////////////////////////////////
            // sanitize URL
            ///////////////////////////////////////////////////////////////
            $url=filter_var($url, FILTER_SANITIZE_URL);

            ////////////////////////////////////////////////////////////////
            // return each of the parameters as an array
            ///////////////////////////////////////////////////////////////
            return explode('/',$url);
        }
    }
    
    
}