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
                                ->key('password', v::stringType()->notEmpty())
                                ->key('firstName', v::stringType()->notEmpty())
                                ->key('lastName', v::stringType()->notEmpty())
                                ->key('email', v::stringType()->notEmpty())
                                ->key('phone', v::stringType())
                                ->key('linkedin', v::stringType())
                                ->key('twitter', v::stringType());
            try{
                $signupValidator->assert($postData); 
                $postData = $request->getParsedBody();
                $users = new Signup();
                $users->username = $postData['username'];
                $users->password = password_hash($postData['password'], PASSWORD_DEFAULT) ;
                $users->firstName = $postData['firstName'];
                $users->lastName = $postData['lastName'];
                $users->email = $postData['email'];
                $users->phone = $postData['phone'];
                $users->linkedin = $postData['linkedin'];
                $users->twitter = $postData['twitter'];
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