<?php 
namespace App\Controllers;

use App\Models\{Job,Project};

class CurriculumController extends BaseController{
    
    public function curriculumAction(){
    $jobs = Job::all();
    $projects = Project::all(); 
    $name = 'Hector Benitez';
    $limitMonths = 2000;

    return $this->renderHTML('curriculum.twig',[
        'name'=>$name,
        'jobs'=>$jobs,
        'projects'=>$projects,
        ]);
    
    }

}