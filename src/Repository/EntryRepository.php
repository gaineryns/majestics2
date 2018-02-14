<?php

namespace App\Repository;

use App\Entity\Entry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class EntryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Entry::class);
    }

    public function entreeIleFrance($etablissement, $start, $end){
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT sum(e.entree) as enter 
              FROM App\Entity\Entry e
              WHERE (date(e.datetime)  BETWEEN  :start and :fin ) and (e.magasin = :etablissement1
              or e.magasin = :etablissement2 or e.magasin = :etablissement3 or e.magasin = :etablissement4)
              
            '
        )->setParameter('start', $start)
            ->setParameter('fin', $end)
            ->setParameter('etablissement1', $etablissement[0] )
            ->setParameter('etablissement2', $etablissement[1] )
            ->setParameter('etablissement3', $etablissement[2] )
            ->setParameter('etablissement4', $etablissement[3] );

        // returns an array of Product objects
        return $query->execute();

    }




    public function allEntryBetween($etablissement, $start, $end){
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT e.magasin as etablissement,sum(e.entree) as enter 
              FROM App\Entity\Entry e
              WHERE (date(e.datetime)  BETWEEN  :start and :fin ) and e.magasin = :etablissement
              GROUP by e.magasin
            '
        )->setParameter('start', $start)
        ->setParameter('fin', $end)
         ->setParameter('etablissement', $etablissement );

        // returns an array of Product objects
        return $query->execute();

    }

    public function CumulinfoEntry( $start, $end){
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT e.magasin as etablissement,date_format(e.datetime, \'%d/%m/%Y - %Hh\') as heure_creation ,sum(e.entree) as enter 
              FROM App\Entity\Entry e
              WHERE (date(e.datetime)  BETWEEN  :start and :fin ) 
              GROUP by  etablissement, heure_creation
              order by  etablissement, heure_creation
            '
        )->setParameter('start', $start)
            ->setParameter('fin', $end);

        // returns an array of Product objects
        return $query->execute();

    }

}
