<?php
namespace App\Controller;
use App\Entity\Entry;
use App\Entity\Tickets;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\AutoFilter\Column;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
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

        $agencies = array_reverse($agencies);

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


        //$this->getAllCsvCumul('2017-11-04', '2017-11-04');
        $this->getAllFileCSV('2017-11-03', '2017-11-04');
        //$this->getFileCSV('test', '2017-11-02', '2017-11-02');
        return $this->render('Tickets/home.html.twig',[ ]);
    }



    public function getAllFileCSV($date_debut,$date_fin){



        /*
         * Correspondance des noms des magasins capteurs <-> tickets
         */
            $agencies = [
                ['capteur'=> 'AIXENPROVENCE', 'magasin'=> 'Aix en Provence'],
                ['capteur'=> 'AVIGNON', 'magasin'=> 'Avignon'],
                ['capteur'=> 'BONAPARTE', 'magasin'=> 'Bonaparte'],
                ['capteur'=> 'BRUXELLES-LOUISE', 'magasin'=> 'Bruxelles-Antoine'],
                ['capteur'=> 'CANNES', 'magasin'=> 'Cannes'],
                ['capteur'=> 'FRANCS_BOURGEOIS', 'magasin'=> 'Francs Bourgeois'],
                ['capteur'=> 'LAPOMPE', 'magasin'=> 'La Pompe'],
                ['capteur'=> 'LOUVRE', 'magasin'=> 'Louvre'],
                ['capteur'=> 'LUXEMBOURG', 'magasin'=> 'Luxembourg Vtl'],
                ['capteur'=> 'LYON', 'magasin'=> 'Lyon'],
                ['capteur'=> 'MONTPELLIER', 'magasin'=> 'Montpellier'],
                ['capteur'=> 'PASSYHOMME', 'magasin'=> 'Passy H'],
                ['capteur'=> 'PASSY_FEMME', 'magasin'=> 'Passy F'],
                ['capteur'=> 'SAINTHONORE', 'magasin'=> 'Saint Honore'],
                ['capteur'=> 'SEINE', 'magasin'=> 'rue de Seine'],
                ['capteur'=> 'STRASBOURG', 'magasin'=> 'Strasbourg'],
                ['capteur'=> 'VICTORHUGO', 'magasin'=> 'Victor Hugo'],
                //['capteur'=> 'WESTBOURNE', 'magasin'=> '']
            ];


        /*
         * pointeur pour les indexes des différents onglets de notre classeur
         */
            $p=0;

        /*
         * instanciation de notre fichier de calcul
         */
            $spreadsheet = new Spreadsheet();


            /*
             * création d'un tableau contenant toutes les données à afficher dans la feuille de calcul
             */
        foreach ($agencies as $k=>$agency) {
            /*
             * entête du tableau
             */
            $header = [ "Date et heure", "Nombre de ventes", "Nombre d'entrées", "Taux de transformation"];
            $tableau = [];
            $tableau[0] = $header;

            /*
             * données capteurs du magasin en cours de traitement durant la période définie
             */
            $entryRepo = $this->getDoctrine()->getRepository(Entry::class);
            $entry = $entryRepo->allEntryBetween($agency['capteur'], $date_debut, $date_fin);

            /*
             * données tickets du magasin en cours de traitement durant la période définie
             */
            $ticketRepo = $this->getDoctrine()->getRepository(Tickets::class);
            $tickets = $ticketRepo->allTicketBetween($agency['magasin'], $date_debut, $date_fin);

            /*
             * s'il n'y a pas de données de capteur le traitement passe au traiment
             * d'un autre magasin
             * sinon récupération des différentes informations et les stocker dans le
             * tableau "$tableau"
             */
            if( empty($entry)){
                continue;
            }else {

                foreach ($entry as $enter) {
                    $heure = $enter['heure_creation'];
                    $nbrAcheteur = 0;
                    $nbrEntree = (intval($enter['enter'])? intval($enter['enter']): 0);
                    $taux = 0;


                    $tableau[] = [$heure, $nbrAcheteur, $nbrEntree, $taux];
                }

                foreach ($tickets as $ticket) {
                    for ($i = 1; $i < count($tableau); $i++) {
                        if ($ticket['heure_creation'] == $tableau[$i][0]) {
                            $tableau[$i][1] = (intval($ticket['nombre_acheteur'])?intval($ticket['nombre_acheteur']):0);
                        }
                        /*
                         * evitons une division par zero
                         */
                        if ($tableau[$i][2] == 0) {
                            $tableau[$i][3] = 0;
                        } else {
                            $tableau[$i][3] = str_replace('.', ',', round($tableau[$i][1] / $tableau[$i][2] * 100, 2)) . " %";
                        }
                    }

                }


                /*
                 * pour chaque donnée de magasin stockée dans le $tableau, créons et
                 * enrégistrons ces données dans une feuille de calcul de notre classeur
                 * portant le nom du magasin en cours de traitement
                 */

                $oneMoreSheet= $spreadsheet->createSheet($p);
                $oneMoreSheet->fromArray(
                    $tableau

                );


                /*
                 * style de la feuille de calcul
                 */
                $cell_st =[
                    'font' =>['bold' => true],
                    'alignment' =>['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
                    'borders'=>['bottom' =>['style'=> \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM]]
                ];
                $oneMoreSheet->getStyle('A1:D1')->applyFromArray($cell_st);

//set columns width
                $oneMoreSheet->getColumnDimension('A')->setWidth(20);
                $oneMoreSheet->getColumnDimension('B')->setWidth(20);
                $oneMoreSheet->getColumnDimension('C')->setWidth(20);
                $oneMoreSheet->getColumnDimension('D')->setWidth(25);

                $oneMoreSheet->getRowDimension('1')->setRowHeight(40);

                $oneMoreSheet->setTitle($agency['magasin']);

                 $oneMoreSheet->setAutoFilter($spreadsheet
                    ->getActiveSheet()->calculateWorksheetDataDimension());

                 /*
                  * filtrons les informations à afficher pour un meilleur rendu
                  */
                 $autoFilter = $oneMoreSheet->getAutoFilter();

                 $columnfilter = $autoFilter->getColumn('D');

                 $columnfilter->setFilterType(Column::AUTOFILTER_FILTERTYPE_CUSTOMFILTER)
                     ->createRule()
                     ->setRule(Column\Rule::AUTOFILTER_COLUMN_RULE_EQUAL,
                              '*%')
                     ->setRuleType(Column\Rule::AUTOFILTER_RULETYPE_CUSTOMFILTER);

                 $p++;

            }


        }


        $spreadsheet->createSheet(8)
            ->fromArray(
                ["test"]  // The data to set
                        // Array values with this value will not be set
            // Top left coordinate of the worksheet range where
            //    we want to set these values (default is A1)
            )->setTitle("info gene");
        $writer = new Xlsx($spreadsheet);
        $fxls ='Rapport-'.$date_debut.'.xlsx';
        $writer->save($fxls);

        return true;
    }

    public function getFileCSV($etablissement, $date_debut,$date_fin){


        $header = ["Etablissement", "heure de creation","Nombre de ventes", "Nombre d'entree", "Taux de transformation"];
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
                        $tableau[$i][4] =  str_replace('.', ',',round($tableau[$i][2]/$tableau[$i][3] *100, 2) ). " %";
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




    public function getAllCsvCumul($date_debut,$date_fin ){
        $header = ["heure de creation", "Nombre de ventes", "Nombre d'entree", "Taux de transformation"];
        $tableau = [];
        $tableau[0] = $header;

        $entryRepo = $this->getDoctrine()->getRepository(Entry::class);
        $entry = $entryRepo->CumulinfoEntry($date_debut, $date_fin);

        $ticketRepo = $this->getDoctrine()->getRepository(Tickets::class);
        $tickets = $ticketRepo->cumulinfoticket( $date_debut, $date_fin);




        if(empty($tickets) || empty($entry)){

        }else {
            foreach ($entry as $enter) {
                $heure = $enter['heure_creation'];
                $nbrAcheteur = 0;
                $nbrEntree = intval($enter['enter']);
                $taux = 0;

                $tableau[] = [$heure, $nbrAcheteur, $nbrEntree, $taux];
            }

            foreach ($tickets as $ticket) {
                for ($i = 1; $i < count($tableau); $i++) {
                    if ($ticket['heure_creation'] == $tableau[$i][0]) {
                        $tableau[$i][1] = intval($ticket['nombre_acheteur']);
                    }
                    if ($tableau[$i][2] == 0) {
                        $tableau[$i][3] = 0;
                    } else {
                        $tableau[$i][3] = str_replace('.', ',', round($tableau[$i][1] / $tableau[$i][2] * 100, 2)) . " %";
                    }
                }
            }
            $file = fopen('info_cumul-' . $date_debut . '.csv', 'w+');
            foreach ($tableau as $tab) {
                fputcsv($file, $tab, ';');
            }


            fclose($file);
        }
    }

}