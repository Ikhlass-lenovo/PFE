<?php

namespace App\Controller;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use App\Entity\User;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\Routing\Annotation\Route;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\UserRepository;
use FOS\RestBundle\View\View;
class UserController  extends FOSRestController
  { 
   

/**
     * List all gpsusers.
     *
     * @Rest\Get("/users/{id}/gpshistos")
    
    
     *
     * @return Doctrine\ORM\QueryBuilder
     */
  public function getgpsusers(Request $request)
  {
    $serializer = SerializerBuilder::create()->build();
    $result=$this->getDoctrine()->getRepository(User::class)->find($request->get('id'))->getGpsHistos();
    $jsonObject = $serializer->serialize($result,'json', SerializationContext::create()->setGroups(array('default')));
    
    return new Response($jsonObject, Response::HTTP_OK , []);
  
}
     
  

    /**
     * @Route("/users/{id}", name="show_user", methods={"GET"})
     */
    public function show(User $user, UserRepository $userRepository, SerializerInterface $serializer,$id)
    {
        $user = $userRepository->find($id);
        $data = $serializer->serialize($user, 'json');
        return new Response($data, 200, [
            'Content-Type' => 'application/json'
        ]);
    }
     
   
/**
     * @Rest\Get("/users")
     */
    public function getAllUsersAction(UserRepository $userRepo)
    {
         $users = $userRepo->findAll();
         return $this->handleView($this->view($users, 200));
    }

    /**
     * @Rest\Get("/users/{id}", requirements={"id" = "\d+"})
     */
    public function getUserAction($id)
    {
        return $this->findUserOrFail($id);
    }

    /**
     * @Rest\Delete("/users/{id}")
     */
    public function deletUser(User $user=null, EntityManagerInterface $em)
    {
        if (!$user) {
          return $this->handleView($this->view(
            ["status"=>404, "message"=>"Utilisateur n'est existe pas !"], 404));
        }
        $em->remove($user);
        $em->flush();
        return $this->handleView($this->view([], 204));
    }

}