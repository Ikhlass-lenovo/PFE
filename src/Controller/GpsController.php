<?php

namespace App\Controller;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Entity\GpsHisto;
use App\Form\GpsHistoType;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use \Datetime;
use \Date;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;
use App\Repository\GpsHistoRepository;




class GpsController extends FOSRestController
{
   /**
     * @Rest\Post("/registerUser", name="api_register")
     */
  
   
public function register(ObjectManager $om, UserPasswordEncoderInterface $passwordEncoder, Request $request)
{
   $user = new User();
   $email                  = $request->request->get("email");
   $username                  = $request->request->get("username");
   $password               = $request->request->get("password");
   $passwordConfirmation   = $request->request->get("password_confirmation");
   $nom=  $request->request->get("nom");
   $prenom=  $request->request->get("prenom");

   /*$confirmation_token=  $request->request->get("confirmation_token");*/
   $adresse=  $request->request->get("adresse");

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
       $user->setUsername($username);
       $user->setNom($nom);
       $user->setPrenom($prenom);

       $user->setEmail($email);
       $user->setPassword($encodedPassword);
       /*$user->setConfirmationToken($confirmation_token);*/
       $user->setAdresse($adresse);
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
   
    /**
     * @Rest\Put("/gpshistoup/{id}", name="update_gps")
     */
  
    public function update(Request $request, SerializerInterface $serializer, GpsHisto $gpshisto, ValidatorInterface $validator, EntityManagerInterface $entityManager)
    {
        $gpsUpdate = $entityManager->getRepository(GpsHisto::class)->find($gpsHisto->getId());
        $data = json_decode($request->getContent());
        foreach ($data as $key => $value){
            if($key && !empty($value)) {
                $name = ucfirst($key);
                $setter = 'set'.$name;
                $gpsUpdate->$setter($value);
            }
        }
        $errors = $validator->validate($gpsUpdate);
        if(count($errors)) {  $errors = $serializer->serialize($errors, 'json');
            return new Response($errors, 500, [
                'Content-Type' => 'application/json'
            ]);
        }
        $entityManager->flush();
        $data = [
            'status' => 200,
            'message' => 'Le historique a bien été mis à jour'
        ];
        return new JsonResponse($data);

    }
        
    /** 
     * Lists all GPS
      * @Rest\Get("/Gps")
      *
      * @return Response
     */
    public function getGps()
    {
        $serializer = SerializerBuilder::create()->build();
        $result=$this->getDoctrine()->getRepository(GpsHisto::class)->findAll();
        $jsonObject = $serializer->serialize($result,'json');
        
        return new Response($jsonObject, Response::HTTP_OK , []);
      
    }
    /**
     * @Rest\Get("/gpsbyDate")
     */
    
    public function getgpsbydate(Request $request)
    {
        $date = date('Y-m-d');
        $dids=$this->getDoctrine()->getRepository(GpsHisto::class)->findByGps($date);        
        return View::create($dids, Response::HTTP_OK , []);
    }
    /**
     * @Rest\Get("/gpshisto/{id}",name="show_gps")
     */ 
    
    
    public function show(GpsHisto $gps, GpsHistoRepository $gpsRepository, SerializerInterface $serializer,$id)
    {
        $gps = $gpsRepository->find($id);
        $data = $serializer->serialize($gps, 'json');
        return new Response($data, 200, [
            'Content-Type' => 'application/json'
        ]);
    }

   


/**
     * @Rest\Get("/gpshistorique/{id}",name="show_gpsbyid")
     
     * 
    */
    
    public function showgps(GpsHisto $gps, GpsHistoRepository $gpsRepository, SerializerInterface $serializer,$id,Request $request)
    {  $id = $request->request->get('id_user');
        $gps = $gpsRepository->findBy(['user' =>$id]);
        $data = $serializer->serialize($gps, 'json', SerializationContext::create()->setGroups(array('default')));
    
        return new Response($data, 200, [
            'Content-Type' => 'application/json'
        ]);
    }

 /**
    * @Rest\GET("gpshistori/{nom_user}")
    * @Rest\View(StatusCode=200)
    */ 
    public function showGpsByName(Request $request)
{
   $nom_user = $request->get('nom_user'); 
    $serializer = SerializerBuilder::create()->build();
    $result=$this->getDoctrine()->getRepository(GpsHisto::class)->findBy($nom_user);
       
    $jsonObject = $serializer->serialize($result,'json',SerializationContext::create()->setGroups(array('default')));

    
    return new Response($jsonObject, Response::HTTP_OK );
  
}
/**
     * @Rest\GET("/getPositionGps/{id}")
     */
    public function getPositionGps(Request $request)
    {
        $gps=$this->getDoctrine()->getRepository(GpsHisto::class)->find($request->get('id'));
        
        if (empty($gps)) {
            return new JsonResponse(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }
        
        $formatted = [
            'id_user'=>$request->getUser()->getId(),
            'Latitude_gps' => $gps->getLongitudeGps(),
            'Altitude_gps' => $gps->getLatitudeGps(),
        ];
        
        return new JsonResponse($formatted);
    }
    /**
     * @Rest\POST("/api/gpsbetwenndate/{id_user}")
     * * @Rest\View(StatusCode=200)
    */ 
   
    
    public function getLatAndLong(Request $request, SerializerInterface $serializer)
    {  
        $id_user = $request->get('id_user'); 
       
        $dateB = $request->request->get("beginDateTime");
        $dateE = $request->request->get("endDateTime");
        $beginDateTime = \DateTime::createFromFormat( "Y-m-d H:i:s", date($dateB) );

        $endDateTime = \DateTime::createFromFormat( "Y-m-d H:i:s", date($dateE) );
        $serializer = SerializerBuilder::create()->build();

        $gps=$this->getDoctrine()->getRepository(GpsHisto::class)->findItemsCreatedBetweenTwoDates($beginDateTime,$endDateTime,$id_user);        
       
        $jsonObject = $serializer->serialize($gps,'json',SerializationContext::create()->setGroups(array('default')));

    
        return new Response($jsonObject, Response::HTTP_OK );

    }
   
    

/**
     * List all users.
     *
     * @Rest\Get("/gpshistos/{id}/users")
    
     *
    
     *
     * @return Doctrine\ORM\QueryBuilder
     */
  public function getgpsusers(Request $request )
  {
  
    $gps=$this->getDoctrine()->getRepository(GpsHisto::class)->find($request->get('id'))->getUser();;
   
    $data = $this->get('jms_serializer')->serialize($gps, 'json');
    return new Response($data, 200, [
        'Content-Type' => 'application/json'
    ]);
}
  
/**
     * List all gpss 
     *
     * @Rest\Get("/api/gpshistoriq/{id}")
    
    
     *
     * @return Doctrine\ORM\QueryBuilder
     */
    public function getgbsById(Request $request)
    {
      $serializer = SerializerBuilder::create()->build();
      $result=$this->getDoctrine()->getRepository(GpsHisto::class)->find($request->get('id'));
      $jsonObject = $serializer->serialize($result,'json', SerializationContext::create()->setGroups(array('default')));
      
      return new Response($jsonObject, Response::HTTP_OK , []);
    
  }
  /**
    * @Rest\GET("/api/gpsAujourdhui/{id_user}")
    * @Rest\View(StatusCode=200)
    */ 
    public function showGpsAujourdhuiById(Request $request)
{
   $id_user = $request->get('id_user'); 
    $serializer = SerializerBuilder::create()->build();
    $result=$this->getDoctrine()->getRepository(GpsHisto::class)->findByToday($id_user);
       
    $jsonObject = $serializer->serialize($result,'json',SerializationContext::create()->setGroups(array('default')));

    
    return new Response($jsonObject, Response::HTTP_OK );
  
}
 
 /**
    * @Rest\GET("/api/gpsHier/{id_user}")
    * @Rest\View(StatusCode=200)
    */ 
    public function showGpsYesterdayById(Request $request)
{
   $id_user = $request->get('id_user'); 
    $serializer = SerializerBuilder::create()->build();
    $result=$this->getDoctrine()->getRepository(GpsHisto::class)->findByYesterday($id_user);
       
    $jsonObject = $serializer->serialize($result,'json',SerializationContext::create()->setGroups(array('default')));

    
    return new Response($jsonObject, Response::HTTP_OK );
  
}
/**
    * @Rest\GET("/api/gpsSemaine/{id_user}")
    * @Rest\View(StatusCode=200)
    */ 
    public function showGpsWeekById(Request $request)
{
   $id_user = $request->get('id_user'); 
    $serializer = SerializerBuilder::create()->build();
    $result=$this->getDoctrine()->getRepository(GpsHisto::class)->findGpsBeforeWeek($id_user);
       
    $jsonObject = $serializer->serialize($result,'json',SerializationContext::create()->setGroups(array('default')));

    
    return new Response($jsonObject, Response::HTTP_OK );
  
}
/**
    * @Rest\GET("/api/gpsMois/{id_user}")
    * @Rest\View(StatusCode=200)
    */ 
    public function showGpsBeforeMonthByName(Request $request)
{
   $id_user = $request->get('id_user'); 
    $serializer = SerializerBuilder::create()->build();
    $result=$this->getDoctrine()->getRepository(GpsHisto::class)->findGpsBeforeMonth($id_user);
       
    $jsonObject = $serializer->serialize($result,'json',SerializationContext::create()->setGroups(array('default')));

    
    return new Response($jsonObject, Response::HTTP_OK );
  
}
  /**
    * @Rest\GET("/api/gpsMoisCourrant/{id_user}")
    * @Rest\View(StatusCode=200)
    */ 
    public function showGpsCurrentMonth(Request $request)
{
   $id_user = $request->get('id_user'); 
    $serializer = SerializerBuilder::create()->build();
    $result=$this->getDoctrine()->getRepository(GpsHisto::class)->findGpsCurrentMonth($id_user);
       
    $jsonObject = $serializer->serialize($result,'json',SerializationContext::create()->setGroups(array('default')));

    
    return new Response($jsonObject, Response::HTTP_OK );
  
}
 /**
    * @Rest\GET("/api/gpsSemaineCourrant/{id_user}")
    * @Rest\View(StatusCode=200)
    */ 
    public function showGpsCurrentWeekById(Request $request)
{
   $id_user = $request->get('id_user'); 
    $serializer = SerializerBuilder::create()->build();
    $result=$this->getDoctrine()->getRepository(GpsHisto::class)->findGpsCurrentWeek($id_user);
       
    $jsonObject = $serializer->serialize($result,'json',SerializationContext::create()->setGroups(array('default')));

    
    return new Response($jsonObject, Response::HTTP_OK );
  
}
/**
     * List all gpss 
     *
     * @Rest\Get("/gpshistoriqByName/{nom_user}")
    
    
     *
     * @return Doctrine\ORM\QueryBuilder
     */
    public function getgbsByName(Request $request)
    {
        $nom_user = $request->get('nom_user'); 
        $serializer = SerializerBuilder::create()->build();
        $result=$this->getDoctrine()->getRepository(GpsHisto::class)->findBy(['nom_user' =>$nom_user]);
           
        $jsonObject = $serializer->serialize($result,'json',SerializationContext::create()->setGroups(array('default')));
    
        
        return new Response($jsonObject, Response::HTTP_OK );
      
    
  }
  /**
    * @Rest\GET("/api/gpshistoriAll")
    * @Rest\View(StatusCode=200)
    */ 
    public function showGpsById(Request $request)
{
   /*$id_user = $request->get('id_user'); */
   $limit = $request->query->get('limit', 5);
   $page = $request->query->get('page', 1);
    $serializer = SerializerBuilder::create()->build();
    $result=$this->getDoctrine()->getRepository(GpsHisto::class)->findBy(array(), array('date_install' => 'ASC'));;
   $res= array();
    
       foreach($result as $item){
           array_push($res,array(
            "id"=>$item->getId(),
            "id_android"=> $item->getIdAndroid(),
            "ip_wan"=>  $item->getIpWan(),
            "ip_mac"=> $item->getIpMac(),
            "nom_user"=> $item->getNomUser(),
            "nom_machine"=> $item->getNomMachine(),
            "localisation"=>  $item->getLocalisation(),
            "date_update"=> $item->getDateUpdate(),
            "ip_lan"=>  $item->getIpLan(),
            "date_install"=>  $item->getDateInstall(),
            "latitude_gps"=> $item->getLatitudeGps(),
            "longitude_gps"=> $item->getLongitudeGps(),
            "altitude_gps"=>$item->getAltitudeGps(),
            "accuracy_gps"=> $item->getAccuracyGps(),
            "provider_gps"=> $item->getProviderGps(),
            "bearing_gps"=>$item->getBearingGps(),
            "speed_gps"=>$item->getSpeedGps(),
            "elapsedrealtimeannos_gps"=>$item->getElapsedrealtimeannosGps(),
            "id_user"=>$item->getUser()->getId() ,
            "user_email"=>$item->getUser()->getEmail() ,

           ));
       }
    $jsonObject = $serializer->serialize($res,'json',SerializationContext::create()->setGroups(array('default')));

    
    return new Response($jsonObject, Response::HTTP_OK );
  
}
  /**
    * @Rest\GET("/api/gpshistoriByIdUser/{id_user}")
    * @Rest\View(StatusCode=200)
    */ 
    public function showGpsById_User(Request $request)
{
   $id_user = $request->get('id_user'); 
    $serializer = SerializerBuilder::create()->build();
    $result=$this->getDoctrine()->getRepository(GpsHisto::class)->findByIDUser(['user'=>$id_user]);
       
    $jsonObject = $serializer->serialize($result,'json',SerializationContext::create()->setGroups(array('default')));

    
    return new Response($jsonObject, Response::HTTP_OK );
  
}
/****vrai */
/**
    * @Rest\GET("gpsByUser/{id_user}")
    * @Rest\View(StatusCode=200)
    */ 
public function showyId(Request $request)
{
   $id_user = $request->get('id_user'); 
    $serializer = SerializerBuilder::create()->build();
    $result=$this->getDoctrine()->getRepository(GpsHisto::class)->findBy(['user'=>$id_user]);
    $jsonObject = $serializer->serialize($result,'json');

    return new Response($jsonObject, Response::HTTP_OK );
}
 

    /**
    * @Route("/api/gps/distance/{id_user}", methods={"GET"})
    */
    public function distanceToday($id_user, GpsHistoRepository $GpsHistoRepository ) {
        //$data = les lignes de l'utilisateur d'it $userId
        $data = $GpsHistoRepository->findByToday($id_user);
        if(count($data)>0){
            $distance=0;
            $time=0;
            $start=$data[0];
            $end=end($data);
          
            
                
                         $point1 = [
                            "lat"=>$start["latitude_gps"],
                            "lon"=>$start["longitude_gps"]
                        ];
    
                        $point2 = [
                          "lat"=>$end["latitude_gps"],
                          "lon"=>$end["longitude_gps"]
                        ];
                        $distance=$distance+ $this->getDistance($point1, $point2);
                        return $this->json([$distance]);
    
                    }
                    else { return $this->json([0]);}
     
} 
 public function getDistance($point1, $point2)
 {    
       $theta = $point1["lon"] - $point2["lon"];
        $dist = sin(deg2rad($point1["lat"])) * sin(deg2rad($point2["lat"])) + cos(deg2rad($point1["lat"])) * cos(deg2rad($point2["lat"])) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);

        $miles = $dist * 60 * 1.1515;

        $km =  $miles * 1.609344;

        return $km;
 }
 
    /**
    * @Route("/api/gpsyesterday/distance/{id_user}", methods={"GET"})
    */
    public function distanceYesterday($id_user, GpsHistoRepository $GpsHistoRepository ) {
        //$data = les lignes de l'utilisateur d'it $userId
        $data = $GpsHistoRepository->findByYesterday($id_user);
        if(count($data)>0){
            $distance=0;
            $time=0;
            $start=$data[0];
            $end=end($data);
          $date1=$start["date_install"];
          $date2=$end["date_install"];

                
                         $point1 = [
                            "lat"=>$start["latitude_gps"],
                            "lon"=>$start["longitude_gps"]
                        ];
    
                        $point2 = [
                          "lat"=>$end["latitude_gps"],
                          "lon"=>$end["longitude_gps"]
                        ];
                        $distance=$distance+ $this->getDistance($point1, $point2);
                        $time=$time+ $this->getdifftime($date1, $date2);
                        return $this->json([$distance]);

                        /*return $this->json(["distance "=>$distance,"time"=>$time]);*/
    
                    }
                    else { return $this->json([0]);}
     
 }
 /**
    * @Route("/api/gpssemainecourant/distance/{id_user}", methods={"GET"})
    */
    public function distancecuurentweek($id_user, GpsHistoRepository $GpsHistoRepository ) {
        //$data = les lignes de l'utilisateur d'it $userId
        $data = $GpsHistoRepository-> findGpsCurrentWeek($id_user);
        if(count($data)>0){
            $distance=0;
            $time=0;
            $start=$data[0];
            $end=end($data);
          
            
                
                         $point1 = [
                            "lat"=>$start["latitude_gps"],
                            "lon"=>$start["longitude_gps"]
                        ];
    
                        $point2 = [
                          "lat"=>$end["latitude_gps"],
                          "lon"=>$end["longitude_gps"]
                        ];
                        $distance=$distance+ $this->getDistance($point1, $point2);
                        return $this->json([$distance]);
    
                    }
                    else { return $this->json([0]);}
     
}
 /**
    * @Route("/api/gpsmois/distance/{id_user}", methods={"GET"})
    */
    public function cuurentmonth($id_user, GpsHistoRepository $GpsHistoRepository ) {
        //$data = les lignes de l'utilisateur d'it $userId
        $data = $GpsHistoRepository->findGpsCurrentMonth($id_user);
        $serializer = SerializerBuilder::create()->build();
        if(count($data)>0){
        $distance=0;
        $time=0;
        $start=$data[0];
        $end=end($data);
      
        
            
                     $point1 = [
                        "lat"=>$start["latitude_gps"],
                        "lon"=>$start["longitude_gps"]
                    ];

                    $point2 = [
                      "lat"=>$end["latitude_gps"],
                      "lon"=>$end["longitude_gps"]
                    ];
                    $distance=$distance+ $this->getDistance($point1, $point2);
                    return $this->json([$distance]);

                }
                else { return $this->json([0]);}
 

        
}
 /**
    * @Route("/api/gpsmoisprecedent/distance/{id_user}", methods={"GET"})
    */
    public function lastmonth($id_user, GpsHistoRepository $GpsHistoRepository ) {
        //$data = les lignes de l'utilisateur d'it $userId
        $data = $GpsHistoRepository-> findGpsBeforeMonth($id_user);
        $serializer = SerializerBuilder::create()->build();
        if(count($data)>0){
        $distance=0;
        $time=0;
        $start=$data[0];
        $end=end($data);
      
        
            
                     $point1 = [
                        "lat"=>$start["latitude_gps"],
                        "lon"=>$start["longitude_gps"]
                    ];

                    $point2 = [
                      "lat"=>$end["latitude_gps"],
                      "lon"=>$end["longitude_gps"]
                    ];
                    $distance=$distance+ $this->getDistance($point1, $point2);
                    return $this->json([$distance]);

                }
                else { return $this->json([0]);}
 

        
}
/**
    * @Route("/api/gpslastweek/distance/{id_user}", methods={"GET"})
    */
    public function lastweek($id_user, GpsHistoRepository $GpsHistoRepository ) {
        //$data = les lignes de l'utilisateur d'it $userId
        $data = $GpsHistoRepository-> findGpsBeforeWeek($id_user);
        $serializer = SerializerBuilder::create()->build();
        if(count($data)>0){
        $distance=0;
        $time=0;
        $start=$data[0];
        $end=end($data);
      
        
            
                     $point1 = [
                        "lat"=>$start["latitude_gps"],
                        "lon"=>$start["longitude_gps"]
                    ];

                    $point2 = [
                      "lat"=>$end["latitude_gps"],
                      "lon"=>$end["longitude_gps"]
                    ];
                    $distance=$distance+ $this->getDistance($point1, $point2);
                    return $this->json([$distance]);

                }
                else { return $this->json([0]);}
 

        
}



 public function getdifftime($date1,$date2)
 {/*$d1 = $date1->format('Y-m-d H:i:s');
    $d2=$date2->format('Y-m-d H:i:s');
    $d3=strtotime('d1');
    $d4=strtotime('d2');
    date1->getTimestamp()*/



   

// Declare and define two dates 
$diff = abs($date1->getTimestamp()-$date2->getTimestamp());

  
  
// To get the year divide the resultant date into 
// total seconds in a year (365*60*60*24) 
$years = floor($diff / (365*60*60*24));  
  
  
// To get the month, subtract it with years and 
// divide the resultant date into 
// total seconds in a month (30*60*60*24) 
$months = floor(($diff - $years * 365*60*60*24) 
                               / (30*60*60*24));  
  
  
// To get the day, subtract it with years and  
// months and divide the resultant date into 
// total seconds in a days (60*60*24) 
$days = floor(($diff - $years * 365*60*60*24 -  
             $months*30*60*60*24)/ (60*60*24)); 
  
  
// To get the hour, subtract it with years,  
// months & seconds and divide the resultant 
// date into total seconds in a hours (60*60) 
$hours = floor(($diff - $years * 365*60*60*24  
       - $months*30*60*60*24 - $days*60*60*24) 
                                   / (60*60));  
  
  
// To get the minutes, subtract it with years, 
// months, seconds and hours and divide the  
// resultant date into total seconds i.e. 60 
$minutes = floor(($diff - $years * 365*60*60*24  
         - $months*30*60*60*24 - $days*60*60*24  
                          - $hours*60*60)/ 60);  
  
  
// To get the minutes, subtract it with years, 
// months, seconds, hours and minutes  
$seconds = floor(($diff - $years * 365*60*60*24  
         - $months*30*60*60*24 - $days*60*60*24 
                - $hours*60*60 - $minutes*60));  
  
return $hours;
 }
}
    



