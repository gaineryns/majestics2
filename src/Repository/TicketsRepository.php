<?php
/**
 * Created by PhpStorm.
 * User: Iakaa
 * Date: 31/01/2018
 * Time: 11:12
 */

namespace App\Repository;


use App\Entity\Tickets;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TicketsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Tickets::class);
    }



    public function ticketlist(){
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT  t.etablissement
            FROM App\Entity\Tickets t
            WHERE t.nature like \'FFO\'
            
            '
        );
        return $query->execute();
    }

    public function ticketIleFrance($etablissement, $start, $end){
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT   count(t.numero) as nombre_acheteur
            FROM App\Entity\Tickets t
            WHERE (t.heureDeCreation BETWEEN  :start and :fin ) and (t.etablissement = :etablissement1 
             or t.etablissement = :etablissement2  or t.etablissement = :etablissement3 
             or t.etablissement = :etablissement4 or t.etablissement = :etablissement5 or t.etablissement = :etablissement6
             or t.etablissement = :etablissement7) and t.nature like \'FFO\'
            
            '
        )->setParameter('start', $start. " 00:00:00")
            ->setParameter('fin', $end." 23:59:59")
            ->setParameter('etablissement1', $etablissement[0] )
            ->setParameter('etablissement2', $etablissement[1] )
            ->setParameter('etablissement3', $etablissement[2] )
            ->setParameter('etablissement4', $etablissement[3] )
            ->setParameter('etablissement5', $etablissement[4] )
            ->setParameter('etablissement6', $etablissement[5] )
            ->setParameter('etablissement7', $etablissement[6] );

        // returns an array of Product objects
        return $query->execute();

    }

    public function allTicketBetween($etablissement, $start, $end){
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT lower(t.etablissement) as etablissement,  count(t.numero) as nombre_acheteur
            FROM App\Entity\Tickets t
            WHERE (t.heureDeCreation BETWEEN :start and :fin)  and lower(t.etablissement) = :etablissement  and t.nature like \'FFO\'
            GROUP by t.etablissement
            order by t.etablissement
            '
        )->setParameter('start', $start. " 00:00:00")
            ->setParameter('fin', $end." 23:59:59")
            ->setParameter('etablissement', strtolower($etablissement) );

        // returns an array of Product objects
        return $query->execute();

    }

    public function cumulinfoticket( $start, $end){
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT t.etablissement as etablissement,t.heureDeCreation as heure_creation, count(t.numero) as nombre_acheteur
            FROM App\Entity\Tickets t
            WHERE (t.heureDeCreation BETWEEN :start and :fin)  and t.nature like \'FFO\'
            GROUP by  t.etablissement, t.heureDeCreation
            
            '
        )->setParameter('start', $start. " 00:00:00")
            ->setParameter('fin', $end." 23:59:59" );

        // returns an array of Product objects
        return $query->execute();

    }

}

