<?php

namespace App\Events;

use App\Entity\User;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



class UserSendMailListener extends AbstractController
{
    // the entity listener methods receive two arguments:
    // the entity instance and the lifecycle event
     

     private  $mailer;

    function __construct( \Swift_Mailer $mailer)
    {
    	$this->mailer = $mailer; 
      
    }

    public function postPersist(User $user, LifecycleEventArgs $event)
    {       
           $token =$user->getActivationToken();
           $message =( new \Swift_Message("Confirmation de l'émail"))
                    ->setFrom('omrane.ikhlass@gmail.com')
                    ->setTo($user->getEmail())
                    ->setBody(
                            $this->renderView(                   
                            'user/email.html.twig',
                            [
                              'user' => $user,
                              'token'=>$token
                            ]
                            )
                     , 'text/html');
             $this->mailer->send($message);
    }
}