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



    public function allTicketBetween($etablissement, $start, $end){
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT t.etablissement,date_format(t.heureDeCreation, \'%d/%m/%Y - %Hh\') as heure_creation ,  count(t.numero) as nombre_acheteur
            FROM App\Entity\Tickets t
            WHERE (date(t.heureDeCreation) BETWEEN  :start and :fin ) and t.etablissement = :etablissement 
            GROUP by heure_creation, t.etablissement
            order by heure_creation, t.etablissement
            '
        )->setParameter('start', $start)
        ->setParameter('fin', $end )
            ->setParameter('etablissement', $etablissement );

        // returns an array of Product objects
        return $query->execute();

    }

}

