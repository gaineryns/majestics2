<?php
namespace App\Controller;
use App\Entity\Entry;
use App\Entity\Tickets;
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
    public function indexAction(){

        $this->getFileCSV('test', '2017-12-02', '2017-12-02');
        return $this->render('tickets/home.html.twig',[ ]);
    }

    public function getFileCSV($etablissement, $date_debut,$date_fin){


        $header = ["Etablissement", "heure de creation","Nombre de ventes", "Nombre d'entree", "Taux"];
        $tableau =[];
        $tableau[0] = $header;

        $entryRepo = $this->getDoctrine()->getRepository(Entry::class);
        $entry = $entryRepo->allEntryBetween('AIXENPROVENCE',$date_debut,$date_fin);

        $ticketRepo = $this->getDoctrine()->getRepository(Tickets::class);
        $tickets = $ticketRepo->allTicketBetween('Aix en Provence',$date_debut,$date_fin);

        foreach ($entry as $enter){
            $heure= $enter['heure_creation'];
            $nbrAcheteur= 0;
            $nbrEntree = intval($enter['enter'] );
            $taux = 0;

            $tableau[]= ['Aix en Provence', $heure, $nbrAcheteur ,$nbrEntree, $taux ];
        }
            foreach ($tickets as $ticket){
                for ($i=1; $i < 22; $i++){
                    if($ticket['heure_creation'] == $tableau[$i][1]){
                        $tableau[$i][2]= intval($ticket['nombre_acheteur']);
                    }
                    if($tableau[$i][3] == 0){
                        $tableau[$i][4] = 0;
                    }else{
                        $tableau[$i][4] =  str_replace('.', ',',round($tableau[$i][2]/$tableau[$i][3] *100, 2) ). "%";
                    }
                }
            }
            $file = fopen('test1.csv', 'w+');
            foreach ($tableau as $tab){
                fputcsv($file, $tab, ';');
            }

            fclose($file);
        return true;
    }

}