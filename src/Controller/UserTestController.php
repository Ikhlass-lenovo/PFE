<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\Validator\ConstraintViolationList;
use App\Entity\User;
use App\Exception\ResourcesValidationController;
use App\Repository\UserRepository;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use  Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserTestController extends FOSRestController
{
  
    /**
    * @Rest\Post("/users/confirm/account")
    * @Rest\View(StatusCode=200)
    */
    public function confirmEmailAction(Request $request, EntityManagerInterface $em, UserRepository $userRepo)
    {  
        $token = $request->request->get('activationToken');
        if (!$token) {
            throw new ResourcesValidationController("le token n'existe pas !");
        }

        $user = $userRepo->findOneByActivationToken($token);
        if (!$user) {
           return $this->handleView($this->view(["message"=>"utilisateur n'est existe pas !"], 404));
        }

        $user->setActivationToken(null);

        $em->persist($user);
        $em->flush();
       return $this->handleView($this->view(["message"=>"votre compte est activé !"], 200));
    }
    
    /**
    * @Rest\Post("/users/reset-password/send-email")
    * @Rest\View(StatusCode=204)
    */
    public function sendEmailAction(Request $request, UserRepository $userRepo, TokenGeneratorInterface $generator, \Swift_Mailer $mailer, EntityManagerInterface $em)
    {  
        $email = $request->request->get('email');
        if (!$email) {
            throw new ResourcesValidationController("l'email n'existe pas !");
        }

        $user = $userRepo->findOneByEmail($email);
        if (!$user) {
           return $this->handleView($this->view(["message"=>"utilisateur n'est existe pas !"], 404));
        }
        
        $user->setResetPasswordToken($generator->generateToken());

           $message =( new \Swift_Message("Reset Password"))
                    ->setFrom('noreplyomrane@gmail.com')
                    ->setTo($user->getEmail())
                    ->setBody(
                            $this->renderView(                   
                            'user/reset-password.html.twig',
                            [
                              'user' => $user,
                              'token'=>$user->getResetPasswordToken()
                            ]
                            )
                     , 'text/html');
            $mailer->send($message);
        $em->persist($user);
        $em->flush();

       return $this->handleView($this->view(["message"=>"une email est envoyé !"], 202));
    }
  

   /**
    * @Rest\Post("/users/reset-password")
    * @Rest\View(StatusCode=200)
    */
    public function resetpasswordAction(Request $request, EntityManagerInterface $em, UserRepository $userRepo, UserPasswordEncoderInterface $encoder)
    {  
        $token = $request->request->get('resetPasswordToken');
        if (!$token) {
            throw new ResourcesValidationController("le token n'existe pas !");
        }

        $user = $userRepo->findOneByResetPasswordToken($token);
        if (!$user) {
           return $this->handleView($this->view(["message"=>"utilisateur n'est existe pas !"], 404));
        }

        $password = $request->request->get('password');
        if (!$password || strlen($password)<6) {
            throw new ResourcesValidationController("Bad Request");
        }
        $user->setResetPasswordToken(null)
            ->setPassword($encoder->encodePassword($user,$password));

        $em->persist($user);
        $em->flush();

       return $this->handleView($this->view(["message"=>"votre password est bien modifié !"], 200));
    }
    
}
    