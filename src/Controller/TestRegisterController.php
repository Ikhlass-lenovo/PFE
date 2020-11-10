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
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use  Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class TestRegisterController extends FOSRestController
{
  
    /**
    * @Rest\Post("/users")
    * @Rest\View(StatusCode=201, serializerGroups = {"user:read"})
    * @ParamConverter("user", converter="fos_rest.request_body")
    */
    public function createUserAction(User $user, EntityManagerInterface $em,
     ConstraintViolationList $violations, UserPasswordEncoderInterface $encoder, TokenGeneratorInterface $tokenGenerator)
    {  
         
        $code = $tokenGenerator->generateToken();
        $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash)
                 ->setActivationToken($code)
            ;
        $em->persist($user);
        $em->flush();
        dump($user);
       /*$message =( new \Swift_Message("Confirmation de l'émail"))
       ->setFrom('omrane.ikhlass@gmail.com')

                ->setTo('omran.mouez@gmail.com')
                    ->setBody(
                            $this->renderView(                   
                            'user/email.html.twig'
                            )
                     , 'text/html');
           $mailer->send($message);*/
       return $this->handleView($this->view($user, 201));
    }

    /**
    * @Rest\Get("/users", name="app_users_list")
    * @Rest\View( StatusCode=200, serializerGroups = {"user:read"})
    */
    public function listUserAction()
    {
        $articles = $this->getDoctrine()->getRepository(User::class)->findAll();
        
        return $this->handleView($this->view($articles));
    }

    /**
    * @Rest\Delete("/users/{id}", name="app_user_delete",  requirements = {"id"="\d+"})
    * @Rest\View( StatusCode=204)
    */
    public function deleteUserAction(User $user=null, EntityManagerInterface $em)
    {   if (!$user) {
    	throw new ResourcesValidationController("utilisteur n'est existe pas ");
        }
        $em->remove($user);
        $em->flush();
        return $this->handleView($this->view([], 204));

    }   
    
     /**
    * @Rest\Get("/users/{id}", name="app_user_delete",  requirements = {"id"="\d+"})
    * @Rest\View( StatusCode=200, serializerGroups = {"user:read"})
    */
    public function getUserAction(User $user=null, EntityManagerInterface $em)
    {   if (!$user) {
    	throw new ResourcesValidationController("utilisteur n'est existe pas ");
        }
       
        return $this->handleView($this->view($user, 200));

    }   
    
}
    
