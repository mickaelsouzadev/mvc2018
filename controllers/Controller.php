<?php
namespace controllers;
use controllers\Error404;
use controllers\Home;
use controllers\News;
use views\View;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Controller
 *
 * @author Professor
 */
class Controller 
{

    protected $config;
    private $query;

    /**
     * @var View
     */
    protected $view;

    /**
     *
     * @var Model;
     */
    protected $model;

    public function __construct() 
    {

        include 'config.php';
        $this->config = $config;
        $this->view = new View();
    }

    public function route($query = null) 
    {
        $class = null;
        $this->query = $query;
        if ($this->query) {
            $this->query = explode('/', $this->query);
            $class_name = $this->query[0];
            if (count($this->query) > 1) {
                $method = $this->query[1];
            } else {
                $method = null;
            }
            $param = (count($this->query) > 2) ? $this->query[2] : null;
            //$test = new News();
            //$test->index();
            //var_dump($test);
            var_dump($class_name);
            if (class_exists($class_name)) {
                var_dump($class_name);
                $class = new $class_name;

                if ($class instanceof Controller) {
                    if (method_exists($class, $method)) {
                        if ($param) {
                            $class->$method($param);
                        } else {
                            $class->$method();
                        }
                    } else {
                        if ($method === null or $method === "") {
                            if(method_exists($class, "index")){
                                $class->index();
                            } else {
                                $this->view->index(); 
                            }
                        } else {
                            $class = new Error404();
                            $class->error();
                       }
                    }
                }
            }
        }
        if ($this->query === null) {
            $class = new Home;
            $class->index();
        } elseif (!$class) {
            $class = new Error404;
            $class->error();			
        }
    }
    
    public function reload()
    {
        header('Location: '.$_SERVER['HTTP_REFERER']);
    }
}