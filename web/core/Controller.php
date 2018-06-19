<?php

class Controller{
    
    public static function view($view, $data=[]){
        require_once $_SERVER['DOCUMENT_ROOT'].'/app/views/'.$view.'.php';
    }

    public static function getResource($res){
    	$contents=file_get_contents($_SERVER['DOCUMENT_ROOT']."/resources/$res");
    	echo $contents;
    }
}