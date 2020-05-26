<?php 
namespace App\Controllers;

use App\Models\Project;
use Respect\Validation\Validator as v;

class ProjectsController extends BaseController{
    public function getAddProjectAction($request){
        $responseMessage = null;
        if($request->getMethod() == 'POST'){
            $postData = $request->getParsedBody();
            $projectValidator = v::key('title', v::stringType()->notEmpty())
                            ->key('description', v::stringType()->notEmpty());
            try{
                $projectValidator->assert($postData); 
                $postData = $request->getParsedBody();
                $project = new Project();
                $project->title = $postData['title'];
                $project->description = $postData['description'];
                $project->save();
                $responseMessage = 'Saved';
            }catch(\Exception $e){
                $responseMessage = $e->getMessage();

            }
        }
        return $this->renderHTML('addProject.twig',[
            'responseMessage' => $responseMessage
        ]);
    }
}