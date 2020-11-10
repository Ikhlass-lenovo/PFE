<?php

namespace App\Repository;
use \Datetime;
use \Date;

use App\Entity\GpsHisto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method GpsHisto|null find($id, $lockMode = null, $lockVersion = null)
 * @method GpsHisto|null findOneBy(array $criteria, array $orderBy = null)
 * @method GpsHisto[]    findAll()
 * @method GpsHisto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GpsHistoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GpsHisto::class);
    }

       public function  findByIDUser($id_user){
           return $this->createQueryBuilder('m')
            ->select('m.nom_user','m.latitude_gps', 'm.longitude_gps', 'm.altitude_gps','m.date_install','m.user')
            ->where('m.user IN (:id_user)')
             ->innerJoin('m.user' ,'p')   
             ->setParameter('id_user', $id_user)
            
            ->orderBy('m.nom_user', 'DESC')      
            ->getQuery()
            ->getResult();
        }
    public function findByGps()
    {
        return $this->createQueryBuilder('s')
        ->select('s')
        ->orderBy('s.date_install', 'DESC')
        ->getQuery()
        ->setFirstResult(0)
        ->setMaxResults(25)
        ->getResult();
	    
    } 
   

    function findItemsCreatedBetweenTwoDates($beginDateTime,$endDateTime,$id_user)
    {
        return $this->createQueryBuilder('m')
        ->select('m.latitude_gps', 'm.longitude_gps', 'm.altitude_gps','m.date_install')
        ->where('m.user IN (:iduser)')
        ->innerJoin('m.user' ,'p')   
        ->setParameter('iduser', $id_user)   
         /*->where('m.nom_user =  :nom_user')
        ->setParameter('nom_user', $nom_user)   */   
        ->andwhere('m.date_install BETWEEN (:beginDate) AND (:endDate)')
        ->setParameter('beginDate', $beginDateTime)
        ->setParameter('endDate', $endDateTime)
        ->getQuery()
        ->getResult();
}
/*******Select latitude_Gps & longitude_Gps By Today */
function findByToday($id_user)
{
  

    $starttoday = date('Y-m-d 00:00:00');
    $endtoday = date('Y-m-d 23:59:00');
 
        return $this->createQueryBuilder('m')
        ->select('m.nom_user','m.latitude_gps', 'm.longitude_gps', 'm.altitude_gps','m.date_install')
        /*->where('m.nom_user =  :nom_user')
        ->setParameter('nom_user', $nom_user)*/   
        ->where('m.user IN (:iduser)')
        ->innerJoin('m.user' ,'p')   
        ->setParameter('iduser', $id_user)   
        ->andwhere('m.date_install BETWEEN (:starttoday) AND (:endtoday)')
       
        ->setParameter('starttoday', $starttoday)
        ->setParameter('endtoday', $endtoday)        
        ->orderBy('m.user', 'DESC')      
        ->getQuery()
        ->getResult();
}
/*******Select latitude_Gps & longitude_Gps By Yesterday  */
function findByYesterday($id_user)

    {   $yesterday = date("Y-m-d",strtotime("-1 days"));
        $starttoday = date('Y-m-d 00:00:00');
        return $this->createQueryBuilder('m')
        ->select('m.nom_user','m.latitude_gps', 'm.longitude_gps', 'm.altitude_gps','m.date_install')
       /* ->where('m.nom_user =  :nom_user')
        ->setParameter('nom_user', $nom_user)*/
         ->where('m.user IN (:iduser)')
        ->innerJoin('m.user' ,'p')   
        ->setParameter('iduser', $id_user)     
        ->andwhere('m.date_install BETWEEN (:yesterday) AND (:starttoday)')
        ->setParameter('yesterday', $yesterday)
        ->setParameter('starttoday', $starttoday)

        ->orderBy('m.user', 'DESC')      
        ->getQuery()
        ->getResult();
}
/*******Select latitude_Gps & longitude_Gps Before Week   */
function findGpsBeforeWeek($id_user)

    { 
        
        
        $lastweek_ini = new DateTime("last week monday");
        $lastweek_end = new DateTime("last week sunday");
        
       

        return $this->createQueryBuilder('m')
        ->select('m.nom_user','m.latitude_gps', 'm.longitude_gps', 'm.altitude_gps','m.date_install')
        /*->where('m.nom_user =  :nom_user')
        ->setParameter('nom_user', $nom_user)*/
       ->where('m.user IN (:iduser)')
        ->innerJoin('m.user' ,'p')   
        ->setParameter('iduser', $id_user)      
        ->andwhere('m.date_install BETWEEN (:lastweek_ini) AND (:lastweek_end)')
       
        ->setParameter('lastweek_ini', $lastweek_ini)
        ->setParameter('lastweek_end', $lastweek_end) 
        ->orderBy('m.user', 'DESC')      
        ->getQuery()
        ->getResult();
}
/*******Select latitude_Gps & longitude_Gps Before Month  */
function findGpsBeforeMonth($id_user)

    {   $month_ini = new DateTime("first day of last month");
        $month_end = new DateTime("last day of last month");
        
       /* echo $month_ini->format('Y-m-d'); 
        echo $month_end->format('Y-m-d');*/




        return $this->createQueryBuilder('m')
        ->select('m.nom_user','m.latitude_gps', 'm.longitude_gps', 'm.altitude_gps','m.date_install')
        /*->where('m.user =  :nom_user')
        ->setParameter('nom_user', $nom_user)*/
     ->where('m.user IN (:iduser)')
        ->innerJoin('m.user' ,'p')   
        ->setParameter('iduser', $id_user)      
        ->andwhere('m.date_install BETWEEN  (:month_ini) and (:month_end)')
        ->setParameter('month_ini', $month_ini)
        ->setParameter('month_end', $month_end)

        ->orderBy('m.user', 'DESC')      
        ->getQuery()
        ->getResult();
}
/*******Select latitude_Gps & longitude_Gps Current Week */
function findGpsCurrentWeek($id_user)

    {   
           $monday = strtotime("last monday");
            $monday = date('w', $monday)==date('w') ? $monday+7*86400 : $monday;

             $sunday = strtotime(date("Y-m-d",$monday)." +6 days");

             $this_week_start = date("Y-m-d",$monday);
             $this_week_end = date("Y-m-d",$sunday);

/*echo "Current week range from $this_week_start to $this_week_end ";*/
        /*$premierJour = strftime("%A - %d/%M/%Y", strtotime("this week")); 
        dd($premierJour);*/

        return $this->createQueryBuilder('m')
        ->select('m.nom_user','m.latitude_gps', 'm.longitude_gps', 'm.altitude_gps','m.date_install')
        ->where('m.user IN (:iduser)')
        ->innerJoin('m.user' ,'p')   
        ->setParameter('iduser', $id_user)
        /*->where('m.nom_user =  :nom_user')
        ->setParameter('nom_user', $nom_user)*/       
        ->andwhere('m.date_install BETWEEN (:this_week_start) and (:this_week_end)')
        ->setParameter('this_week_start', $this_week_start)
        ->setParameter('this_week_end', $this_week_end)

        ->orderBy('m.nom_user', 'DESC')      
        ->getQuery()
        ->getResult();
}
/*******Select latitude_Gps & longitude_Gps Current Week */
function findGpsCurrentMonth($id_user)

    {   
        $month_ini = date("Y-m-1"); // hard-coded '01' for first day
        $month_end  = date("Y-m-t");
       
/*echo "Current week range from $this_week_start to $this_week_end ";*/
        /*$premierJour = strftime("%A - %d/%M/%Y", strtotime("this week")); 
        dd($premierJour);*/

        return $this->createQueryBuilder('m')
        ->select('m.nom_user','m.latitude_gps', 'm.longitude_gps', 'm.altitude_gps','m.date_install')
        ->where('m.user IN (:iduser)')
        ->innerJoin('m.user' ,'p')   
        ->setParameter('iduser', $id_user)
        /*->where('m.nom_user =  :nom_user')
        ->setParameter('nom_user', $nom_user)*/       
        ->andwhere('m.date_install BETWEEN  (:month_ini) and (:month_end)')
        ->setParameter('month_ini', $month_ini)
        ->setParameter('month_end', $month_end)

        ->orderBy('m.nom_user', 'DESC')      
        ->getQuery()
        ->getResult();
}


}