<?php

namespace App\Controller;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class GpsController extends AbstractController
{
    /**
     * @Route("/gps", name="gps")
     */
    public function index()
    {
        return $this->render('gps/index.html.twig', [
            'controller_name' => 'GpsController',
        ]);
    }
    /**
 * @Route("/registerUser", name="api_register", methods={"POST"})
 */
public function register(ObjectManager $om, UserPasswordEncoderInterface $passwordEncoder, Request $request)
{
   $user = new User();
   $email                  = $request->request->get("email");
   $password               = $request->request->get("password");
   $passwordConfirmation   = $request->request->get("password_confirmation");
   

   $errors = [];
   if($password != $passwordConfirmation)
   {
       $errors[] = "Password does not match the password confirmation.";
   }
   if(strlen($password) < 6)
   {
       $errors[] = "Password should be at least 6 characters.";
   }
   if(!$errors)
   {
       $encodedPassword = $passwordEncoder->encodePassword($user, $password);
       $user->setEmail($email);
       $user->setPassword($encodedPassword);
       $om->persist($user);
       $om->flush();
       return $this->json([
           'user' => $user
       ]);
   }
  
   return $this->json([
       'errors' => $errors
   ], 400);
}
}
