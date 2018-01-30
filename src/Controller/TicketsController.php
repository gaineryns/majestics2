<?php
namespace App\Controller;
use App\Entity\Entry;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Routing\Annotation\Route;

class TicketsController extends Controller
{
    /**
     * @Route("/update", name="data_update")
     */
    public function updateDataAction(){

        $agencies = ['AIXENPROVENCE', 'AVIGNON', 'BONAPARTE', 'BRUXELLES-LOUISE', 'CANNES', 'FRANCS_BOURGEOIS',
            'LAPOMPE', 'LOUVRE', 'LUXEMBOURG', 'LYON', 'MONTPELLIER', 'PASSYHOMME', 'PASSY_FEMME', 'SAINTHONORE', 'SEINE', 'STRASBOURG', 'VICTORHUGO', 'WESTBOURNE' ];
        foreach ($agencies as $agency){
            $finder = new Finder();
            $finder->name('*.dat')->in('ftp://Stanley:StanleyFTPMF75@37.58.138.236/'. $agency);

            foreach ($finder as $file){


                $lecture = $file->openFile('r');
                while(!$lecture->eof()){
                    $entree_array= explode("|", $lecture->fgets());

                    $repo = $this->getDoctrine()->getRepository(Entry::class);
                    $check_entry = $repo->findBy(
                        [
                            'magasin' => $agency,
                            'datetime' => new \DateTime($entree_array[6])
                        ]
                    );

                    if($check_entry) {
                        break;
                    }else{
                        $entree_entity = new Entry();
                        $entree_entity->setCategory($entree_array[0]);
                        $entree_entity->setDivisionMagasin($entree_array[1]);
                        $entree_entity->setIdPorte($entree_array[2]);
                        $entree_entity->setMagasin($agency);
                        $entree_entity->setDescription($entree_array[4]);
                        $entree_entity->setType($entree_array[5]);
                        $entree_entity->setDatetime(new \DateTime($entree_array[6]));
                        $entree_entity->setEntree($entree_array[7]);
                        $entree_entity->setSortie($entree_array[8]);
                        $entree_entity->setEntreeTotale($entree_array[9]);
                        $entree_entity->setSortieTotale($entree_array[10]);
                        $entree_entity->setIp($entree_array[11]);

                        $em = $this->getDoctrine()->getManager();
                        $em->persist($entree_entity);
                        $em->flush();
                    }
                }
                /**/

            }

        }
        var_dump("fertig");
        return $this->render('tickets/home.html.twig',[]);
    }

    /**
     * @Route("/",name="homepage")
     */
    public function getFileCSVAction(){


        return $this->render('tickets/home.html.twig');
    }

}