<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations as FOSRest;
use Symfony\Component\HttpFoundation\Response;
use FOS\OAuthServerBundle\Model\ClientManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SecurityController extends FOSRestController
{
    private $client_manager ;

    public function __construct (ClientManagerInterface  $client_manager)
    {
        $this->client_manager = $client_manager ;
    }
    /**
     * Create Client.
      * @FOSRest\Post("/createClient")
      *
      * @return Response
     */
    public function AuthenticationAction(Request $request)
    {
        $data = json_decode($request->getContent(),true);
        if( empty ( $data['redirect-uri']) || empty($data['grant-type']))
        {
            
            return $this->handleView($this->view($data));
        }
            $clientManager = $this->client_manager;
            $client = $clientManager->createClient();
            $client->setRedirectUris([$data['redirect-uri']]);
            $client->setAllowedGrantTypes([$data['grant-type']]);
            $clientManager->updateClient($client);
            $rows =[
                'client_id'=>$client->getPublicId(), 'client_secret'=> $client->getSecret()
            ];

            return $this->handleView($this->view($rows));
         }

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
   public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
     /**
     * @Route("/registerutilisateur", name="register", methods={"POST"})
     */
   public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $values = json_decode($request->getContent());
        if(isset($values->username,$values->password)) {
            $user = new User();
            $user->setUsername($values->username);
            $user->setEmail($values->email);
            $user->setPassword($passwordEncoder->encodePassword($user, $values->password));
            $user->setRoles(['ROLE_USER']);
            $errors = $validator->validate($user);
            if(count($errors)) {
                $errors = $serializer->serialize($errors, 'json');
                return new Response($errors, 500, [
                    'Content-Type' => 'application/json'
                ]);
            }
            $entityManager->persist($user);
            $entityManager->flush();

            $data = [
                'status' => 201,  'message' => 'L\'utilisateur a été créé'
            ];

            return new JsonResponse($data, 201);
        }
        $data = [
            'status' => 500,
            'message' => 'Vous devez renseigner les clés username et password'
        ];
        return new JsonResponse($data, 500);
    }

    /** 
     * @Route("/loginutilisateur", name="loginuser", methods={"POST"})
     */
    public function loginuser(Request $request)
    {
        $user = $this->getUser();
        return $this->json([
            'email' => $user->getEmail(),
            'roles' => $user->getRoles()
        ]);
    }
}

