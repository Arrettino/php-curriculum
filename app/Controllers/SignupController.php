<?php 
namespace App\Controllers;

use App\Models\Signup;
use Respect\Validation\Validator as v;

class SignupController extends BaseController{
    public function getAddUserAction($request){
        $responseMessage = null;
        if($request->getMethod() == 'POST'){
            $postData = $request->getParsedBody();
            $signupValidator = v::key('username', v::stringType()->notEmpty())
                                ->key('password', v::stringType()->notEmpty());
            try{
                $signupValidator->assert($postData); 
                $postData = $request->getParsedBody();
                $users = new Signup();
                $users->username = $postData['username'];
                $users->password = password_hash($postData['password'], PASSWORD_DEFAULT) ;
                $users->save();
                $responseMessage = 'Saved';
            }catch(\Exception $e){
                $responseMessage = $e->getMessage();

            }
        }
        return $this->renderHTML('signup.twig',[
            'responseMessage' => $responseMessage
        ]);
    }
}