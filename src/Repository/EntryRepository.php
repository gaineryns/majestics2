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


    public function allEntryBetween($etablissement, $start, $end){
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT date_format(e.datetime, \'%d/%m/%Y - %Hh\') as heure_creation ,sum(e.entree) as enter 
              FROM App\Entity\Entry e
              WHERE (date(e.datetime)  BETWEEN  :start and :fin ) and e.magasin = :etablissement
              GROUP by heure_creation 
            '
        )->setParameter('start', $start)
        ->setParameter('fin', $end)
         ->setParameter('etablissement', $etablissement );

        // returns an array of Product objects
        return $query->execute();

    }

}
