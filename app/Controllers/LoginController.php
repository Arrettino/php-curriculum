<?php 
namespace App\Controllers;

use App\Models\Signup;
use Respect\Validation\Validator as v;
use Laminas\Diactoros\Response\RedirectResponse;

class LoginController extends BaseController{
    public function getLoginAction($request){
        return $this->renderHTML('login.twig');
    }

    public function postAuthAction($request){
        $responseMessage = null;
        $postData = $request->getParsedBody();
        $user = Signup::where('username',$postData['username'])->first();
        if($user){
            if (password_verify($postData['password'], $user->password)){
                $_SESSION['userId']= $user->id;
                return header('location: /admin');
                exit;
            }
            $responseMessage = 'Bad credentials';
        }else{
            $responseMessage = 'Bad credentials';
        }
        return $this->renderHTML('login.twig',[
            'responseMessage' => $responseMessage 
        ]);
    }
    public function getLogoutAction($request){
        unset($_SESSION['userId']);
    }
}