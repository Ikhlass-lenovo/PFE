<?php


namespace App\Controller;
use \Datetime;
use Symfony\Component\HttpFoundation\JsonResponse;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerBuilder;
use App\Repository\GpsHistoRepository;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\UserBundle\Form\Factory\FactoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Controller\RegistrationController as BaseController;
use FOS\UserBundle\FOSUserEvents;

use App\Entity\User;

use App\Form\RegistrationType;

class RegistrationController extends BaseController
{
   
    public function __construct(EventDispatcherInterface $eventDispatcher, FactoryInterface $formFactory, UserManagerInterface $userManager, TokenStorageInterface $tokenStorage)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->formFactory     = $formFactory;
        $this->userManager     = $userManager;
        $this->tokenStorage    = $tokenStorage;
    }
    /**
   * Create User.
   * @Rest\Post("/registeruser")
   *
   * @return Response
   */
    public function registerAction(Request $request)
       {
    /** @var $formFactory FactoryInterface */
    $formFactory = $this->formFactory;
    /** @var $userManager UserManagerInterface */
    $userManager = $this->get('fos_user.user_manager');
    /** @var $dispatcher EventDispatcherInterface */
    $dispatcher = $this->get('event_dispatcher');
 
        $user = $userManager->createUser();
        $user->setEnabled(true);
 
        $event = new  GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);
 
        if (null !== $response = $event->getResponse()) {
            return $event->getResponse();
        }
 
        $form = $formFactory->createForm();
        $form->setData($user);
 
        /*if ('POST' === $request->getMethod()) {*/
            $form->handleRequest($request);
 
            if ($form->isValid()) {
                $event = new FormEvent($form, $request);
                $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);
 
                $userManager->updateUser($user);
 
                if (null === $response = $event->getResponse()) {
                   //nothing
                    $resultat = array(
            'msg' => 'vérifier les inputs!',
            'errors' => "wrong inputs"

        ); 
                }
               
                
      
                  
                
                $dispatcher->dispatch(\FOS\UserBundle\FOSUserEvents::REGISTRATION_COMPLETED, new \FOS\UserBundle\Event\FilterUserResponseEvent($user, $request, $response));
 
                $resultat = array(
                   'msg' => 'account created!',
                   'errors' => "ok!");
                   return new JsonResponse($resultat,  JsonResponse::HTTP_OK);
    }
    $dispatcher->dispatch(\FOS\UserBundle\FOSUserEvents::REGISTRATION_COMPLETED, new \FOS\UserBundle\Event\FilterUserResponseEvent($user, $request, $response));
 
    $resultat = array(
       'msg' => 'account created!',
       'errors' => "ok!");
       return new JsonResponse($resultat,  JsonResponse::HTTP_OK);
}
         
       
    
}
    