<?php 
namespace App\Controllers;

class AdminController extends BaseController{
    
    public function getIndexAction(){
    return $this->renderHTML('admin.twig');
    }

}