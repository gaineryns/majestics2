<?php
namespace App\Controller;
use App\Entity\Entry;
use App\Entity\Tickets;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\AutoFilter\Column;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TicketsController extends Controller
{


    /**
     * @Route("/update", name="data_update")
     */
    public function updateDataAction(){

        $agencies = ['AIXENPROVENCE', 'AVIGNON', 'BONAPARTE', 'BRUXELLES-LOUISE', 'CANNES', 'FRANCS_BOURGEOIS',
            'LAPOMPE', 'LOUVRE', 'LUXEMBOURG', 'LYON', 'MONTPELLIER', 'PASSYHOMME', 'PASSY_FEMME', 'SAINTHONORE', 'SEINE', 'STRASBOURG', 'VICTORHUGO', 'WESTBOURNE' ];

        //$agencies = array_reverse($agencies);

        foreach ($agencies as $agency){
            $finder = new Finder();
            $finder->name('*.dat')->date('since yesterday')->in('ftp://Stanley:StanleyFTPMF75@37.58.138.236/'. $agency);

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

        return $this->render('tickets/home.html.twig',[]);
    }

    /**
     * @Route("/accueil",name="accueil")
     */
    public function indexAction(Request $request, \Swift_Mailer $mailer){

        $datedebut = $request->request->get('datedebut');
        $datefin = $request->request->get('datefin');
        if( $datedebut && $datefin ){
            $name = $this->getAllFileCSV('2017-11-11', '2017-11-11');

            $message = new \Swift_Message('Rapport Majestic Filatures');
            $message->setFrom('team@smartiiz.com')
                ->setTo('steve.yongwo@smartiiz.com')
                ->setBody('attachment test')
            ->attach(\Swift_Attachment::fromPath($name));

            $mailer->send($message);



        }
        //$this->getAllFileCSV('2017-11-03', '2017-11-04');
        //$this->getFileCSV('test', '2017-11-02', '2017-11-02');
        return $this->render('Tickets/home.html.twig',[ 'dated' => $datedebut,
            'datef' => $datefin]);
    }




    public function getAllFileCSV($date_debut,$date_fin){



        /*
         * Correspondance des noms des magasins capteurs <-> tickets
         */
            $agencies = [
                ['capteur'=> 'AIXENPROVENCE', 'magasin'=> 'Aix en Provence'],
                ['capteur'=> 'AVIGNON', 'magasin'=> 'Avignon'],
                ['capteur'=> 'BONAPARTE', 'magasin'=> 'Bonaparte'],
                ['capteur'=> 'BRUXELLES-LOUISE', 'magasin'=> 'Bruxelles-Louise'],
                ['capteur'=> 'BRUXELLES-ANTOINE', 'magasin'=> 'Bruxelles-Antoine'],
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

        $header = [ "Magasin", "Nombre de ventes", "Nombre d'entrées", "Taux de transformation en %"];
        $tableau = [];
        $tableau[0] = $header;


            /*
             * création d'un tableau contenant toutes les données à afficher dans la feuille de calcul
             */
        foreach ($agencies as $agency) {
            /*
             * entête du tableau
             */


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
                $entree_total = 0;
                $ticket_total = 0;

                foreach ($entry as $enter) {
                    $magasin = $enter['etablissement'];

                    foreach ($agencies as $agence){
                        if($magasin == $agence['capteur']){
                            $magasin = $agence['magasin'];
                        }
                    }
                    $nbrAcheteur = 0;
                    $nbrEntree = (intval($enter['enter'])? intval($enter['enter']): 0);
                    $entree_total += $nbrEntree;


                        $taux = 0;




                    $tableau[] = [$magasin, $nbrAcheteur, $nbrEntree, $taux];
                }

                foreach ($tickets as $ticket) {
                    for ($i = 1; $i < count($tableau); $i++) {
                        if ($ticket['etablissement'] == $tableau[$i][0]) {
                            $tableau[$i][1] = (intval($ticket['nombre_acheteur'])?intval($ticket['nombre_acheteur']):0);
                            $ticket_total += $tableau[$i][1];
                        }
                        /*
                         * evitons une division par zero
                         */
                        if ($tableau[$i][2] == 0) {
                            $tableau[$i][3] = 0;
                        } else {
                            $tableau[$i][3] = floatval(round($tableau[$i][1] / $tableau[$i][2] * 100, 2)) ;
                        }
                    }

                }

            }
               // $tableau[] = ["Totaux", $ticket_total, $entree_total, str_replace('.', ',', round(($entree_total? 100*$ticket_total/$entree_total:0),2))." %"];

                /*
                 * pour chaque donnée de magasin stockée dans le $tableau, créons et
                 * enrégistrons ces données dans une feuille de calcul de notre classeur
                 * portant le nom du magasin en cours de traitement
                 */


        }

        $ticketIDF = $ticketRepo->ticketIleFrance([$agencies[7]['magasin'],$agencies[11]['magasin'],
            $agencies[12]['magasin'],$agencies[14]['magasin'],$agencies[2]['magasin'],$agencies[5]['magasin']
            ,$agencies[16]['magasin']
        ],$date_debut,$date_fin);

        foreach ($ticketIDF as $idf){
            $ticketIDF1 = $idf['nombre_acheteur'];
        }

        $entreeIDF = $entryRepo->entreeIleFrance([$agencies[7]['capteur'],$agencies[11]['capteur'],
            $agencies[12]['capteur'],$agencies[14]['capteur']
            ,$agencies[2]['capteur'],$agencies[5]['capteur'],$agencies[16]['capteur']],$date_debut,$date_fin);

        foreach ($entreeIDF as $Eidf){
            $entreeIDF1 = $Eidf['enter'];
        }

        $tableau[]= ['Ile-de-france', $ticketIDF1, $entreeIDF1, ($entreeIDF1 == 0) ? 0: floatval(round(($ticketIDF1/$entreeIDF1)*100,2))];



        $oneMoreSheet= $spreadsheet->createSheet(1);
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
        $oneMoreSheet->getColumnDimension('D')->setWidth(30);

        $oneMoreSheet->getRowDimension('1')->setRowHeight(40);

        $oneMoreSheet->setTitle('magasin');

        $oneMoreSheet->setAutoFilter($spreadsheet
            ->getActiveSheet()->calculateWorksheetDataDimension());

        /*
         * filtrons les informations à afficher pour un meilleur rendu
         */

        $p++;



        $oneMoreSheet->getStyle('A1:A17')->getFont()->setBold(true);
        $oneMoreSheet->getStyle('A1:D17')->getBorders()->getAllBorders()
            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);





        $Cumulentree = $this->getDoctrine()->getRepository(Entry::class)
            ->CumulinfoEntry($date_debut,$date_fin);
        $CumulTickets = $this->getDoctrine()->getRepository(Tickets::class)
            ->cumulinfoticket($date_debut, $date_fin);

        $Cumulentree_total = 0;
        $Cumulticket_total =0;

        $headerC = [ "Magasin(s)","Date et heure", "Nombre de ventes", "Nombre d'entrées", "Taux de transformation en %"];
        $tableauC = [];
        $tableauC[0] = $headerC;

        foreach ($Cumulentree as $enter) {
            $heure = $enter['heure_creation'];
            $nbrAcheteur = 0;
            $nbrEntree = (intval($enter['enter'])? intval($enter['enter']): 0);
            $Cumulentree_total += $nbrEntree;


                $taux = 0;




            foreach($agencies as $agence){
                if($agence['capteur'] == $enter['etablissement']){

                    $etablissement = $agence['magasin'];

                }
            }
            $tableauC[] = [$etablissement ,$heure, $nbrAcheteur, $nbrEntree, $taux];
        }

        foreach ($CumulTickets as $ticket) {
            for ($i = 1; $i < count($tableauC); $i++) {
                if (($ticket['heure_creation'] == $tableauC[$i][1])&& $ticket['etablissement'] == $tableauC[$i][0]) {
                    $tableauC[$i][2] = (intval($ticket['nombre_acheteur'])?intval($ticket['nombre_acheteur']):0);
                    $Cumulticket_total += $tableauC[$i][2];

                    /*
                * evitons une division par zero
                */
                    if (($tableauC[$i][3] == 0)|| ($tableauC[$i][3] == '') || empty($tableauC[$i][2])) {
                        $tableauC[$i][4] = 0;
                    } else {
                        $tableauC[$i][4] = floatval(round($tableauC[$i][2] / $tableauC[$i][3] * 100));
                    }
                }

            }

        }


        //$tableautotaux = ["Totaux", "=sous.total(9;D4:D2000)", $Cumulentree_total, str_replace('.', ',', round(($Cumulentree_total? 100*$Cumulticket_total/$Cumulentree_total:0),2))." %"];


        /*   $tableauC = array_reverse($tableauC);
          $tableauC[] = $tableautotaux;
          $tableauC[] = $tableautotaux;
          $tableauC = array_reverse($tableauC);*/

        $cumulsheet = $spreadsheet->createSheet(17);
            $cumulsheet->fromArray(
                $tableauC,  // The data to set
                 null,'A5'       // Array values with this value will not be set
            // Top left coordinate of the worksheet range where
            //    we want to set these values (default is A1)
            );


            $cumulsheet->setCellValue('B1', 'Nombre de ventes' );
            $cumulsheet->setCellValue('C1', 'Nombre d\'entrées');
            $cumulsheet->setCellValue('D1', 'Taux de transformation en %');

            $cumulsheet->setCellValue('A2', "Récapitulatif magasins France" );
            $cumulsheet->setCellValue('B2', '=SUM(C4:C2000)' );
            $cumulsheet->setCellValue('C2', '=SUM(D4:D2000)');
            $cumulsheet->setCellValue('D2', '=ROUND(((B2/C2)*100),2)');


            $cumulsheet->setCellValue('A3', "Récapitulatif magasin(s) sélectionné(s)" );
            $cumulsheet->setCellValue('B3', '=SUBTOTAL(109,C4:C2000)' );
            $cumulsheet->setCellValue('C3', '=SUBTOTAL(109,D4:D2000)');
            $cumulsheet->setCellValue('D3', '=ROUND(((B3/C3)*100),2)');


            /*
             * Style de la feuille de calcul du tableau de cumul
             */

        $styleArray = array(
            'font' => array(

            ),
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' =>\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,

            ),
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ),
            ),
        );

        $cumulsheet->getRowDimension('1')->setRowHeight(30);
        $cumulsheet->getRowDimension('2')->setRowHeight(45);
        $cumulsheet->getRowDimension('3')->setRowHeight(45);
        $cumulsheet->getRowDimension('5')->setRowHeight(30);


        $cumulsheet->getStyle('A5:E2000')->getBorders()->getAllBorders()
            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        $cumulsheet->getStyle('A1:D3')->applyFromArray($styleArray);
        $cumulsheet->getStyle('A1:D3')->getAlignment()->setWrapText(true);

        $cumulsheet->getStyle('B1:D1')->getFill()
            ->setFillType('\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID')
            ->getStartColor()->setARGB('dddddd');

        $cumulsheet->getStyle('A2:D2')->getFill()
            ->setFillType('\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID')
            ->getStartColor()->setARGB('eeeeee');

        $cumulsheet->getStyle('A3:D3')->getFill()
            ->setFillType('\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID')
            ->getStartColor()->setARGB('dddddd');

        $cumulsheet->getStyle('A5:E5')->getFill()
            ->setFillType('\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID')
            ->getStartColor()->setARGB('dddddd');

        $cumulsheet->getStyle('B1:D1')->getFont()->setBold(true);
        $cumulsheet->getStyle('A2:A3')->getFont()->setBold(true);
      /*  $cumulsheet->getStyle('B1:D1')->applyFromArray($styleArray);
        $cumulsheet->getStyle('B1:D1')->getAlignment()->setWrapText(true);*/

        /*
                 * style de la feuille de calcul
                 */
        $cell_st =[
            'font' =>['bold' => true],
            'alignment' =>['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER],
            'borders'=>['allBorders' =>['style'=> \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM]]
        ];
        $cumulsheet->getStyle('A5:E5')->applyFromArray($cell_st);

//set columns width
        $cumulsheet->getColumnDimension('A')->setWidth(20);
        $cumulsheet->getColumnDimension('B')->setWidth(20);
        $cumulsheet->getColumnDimension('C')->setWidth(20);
        $cumulsheet->getColumnDimension('D')->setWidth(25);
        $cumulsheet->getColumnDimension('E')->setWidth(25);

        /*$cumulsheet->getRowDimension('1')->setRowHeight(40);*/

        $cumulsheet->setTitle('info gene');



        $cumulsheet->setAutoFilter('A5:E2000');


/*

        $autoFilter1 = $cumulsheet->getAutoFilter();

        $columnfilter1 = $autoFilter1->getColumn('A');


        $columnfilter1->setFilterType(Column::AUTOFILTER_FILTERTYPE_FILTER)
            ->createRule()
            ->setRule(Column\Rule::AUTOFILTER_COLUMN_RULE_EQUAL,
                $tableauC[3][0]);
*/

        $writer = new Xlsx($spreadsheet);
        $fxls ='Rapport-'.$date_debut.'.xlsx';
        $writer->save($fxls);

        return $fxls;
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