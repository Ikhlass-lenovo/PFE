<?php
namespace App\Controller;
use \Datetime;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerBuilder;
use App\Repository\GpsHistoRepository;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\UserBundle\Form\Factory\FactoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

use App\Entity\GpsHisto;
use App\Entity\User;
use App\Entity\Travailleur;
use App\Form\TravailleurType;
use Symfony\Component\Form\FormEvent;
/**
  
 *Travailleurcontroller.
 *@Route("/travailleur",name="api_travailleur")
 */
class TravailleurController extends FOSRestController
{
    
    /**public function __construct(EventDispatcherInterface $eventDispatcher, FactoryInterface $formFactory, UserManagerInterface $userManager, TokenStorageInterface $tokenStorage)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->formFactory     = $formFactory;
        $this->userManager     = $userManager;
        $this->tokenStorage    = $tokenStorage;
    }**/
     /**
     * @Route("/test1", name="test1")
     */
    public function index()
    {
        return $this->render('test/index.html.twig', [
            'controller_name' => 'TravailleurController',
        ]);
    }
    /** 
     * Lists all Travailleurs.
      * @Rest\Get("/travailleurs")
      *
      * @return Response
     */
    public function getTravailleur()
    {
     $repository = $this->getDoctrine()->getRepository(Travailleur::class);
    $travailleurs = $repository->findall();
    return $this->handleView($this->view($travailleurs));
    }

    /**
   * Create Travailleur.
   * @Rest\Post("/travailleur")
   *
   * @return Response
   */
  public function postTravailleur(Request $request)
  {
    $travailleur = new Travailleur();
    $form = $this->createForm(TravailleurType::class, $travailleur);

    $data = json_decode($request->getContent(), true);
    $form->submit($data);

    if ($form->isSubmitted() && $form->isValid()) {
      $em = $this->getDoctrine()->getManager();
      $em->persist($travailleur);
      $em->flush();
      return $this->handleView($this->view(['status' => 'ok'], Response::HTTP_CREATED));
    }
    return $this->handleView($this->view($form->getErrors()));

}
/**
     * @Rest\Post("/addgps")
     */
    public function saveGpsHisto(Request $request)
    {    
        
        
        $entityManager = $this->getDoctrine()->getManager();

        $gps = new GpsHisto();
        $gps->setIdAndroid($request->get('id_android'));
        $gps->setIpWan($request->get('ip_wan'));
        $gps->setIpMac($request->get('ip_mac'));
        $gps->setNomUser($request->get('nom_user'));
        $gps->setNomMachine($request->get('nom_machine'));
        $gps->setLocalisation($request->get('localisation'));
        $gps->setIpLan($request->get('ip_lan'));
        $gps->setDateInstall(new Datetime($request->get('date_install')));
        $gps->setDateUpdate(new Datetime($request->get('date_update')));
        $gps->setLatitudeGps($request->get('latitude_gps'));
        $gps->setLongitudeGps($request->get('longitude_gps'));
        $gps->setAltitudeGps($request->get('altitude_gps'));
        $gps->setAccuaracyGps($request->get('accuaracy_gps'));
        $gps->setProviderGps($request->get('provider_gps'));
        $gps->setBearingGps($request->get('bearing_gps'));
        $gps->setSpeedGps($request->get('speed_gps'));
        $gps->setElapsedrealtimeannosGps($request->get('elapsedrealtimeannos_gps'));

        $user =new User ;
        $user->setNom($request->get('nom'));
        $gps->setUser($user);
//   $gps->setUser($user->getUser());
        //$gps->setUser($request->get('id_user'));
        #=$this->getDoctrine()->getRepository(User::class)->find($request->get('id_user'));
        #$gps->setId;          

        $entityManager->persist($gps);
        $entityManager->flush();
        return  new Response(null, Response::HTTP_OK);
        
    }

     
}
