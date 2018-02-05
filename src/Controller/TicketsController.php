<?php
namespace App\Controller;
use App\Entity\Entry;
use App\Entity\Tickets;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
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

    /**
     * @Route("/test")
     */
    public function index2Action(){


//object of the Spreadsheet class to create the excel data
        $spreadsheet = new Spreadsheet();

//add some data in excel cells
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Domain')
            ->setCellValue('B1', 'Category')
            ->setCellValue('C1', 'Nr. Pages');


        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A2', 'CoursesWeb.net')
            ->setCellValue('B2', 'Web Development')
            ->setCellValue('C2', '4000');

        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A3', 'MarPlo.net')
            ->setCellValue('B3', 'Courses & Games')
            ->setCellValue('C3', '15000');




//set style for A1,B1,C1 cells
        $cell_st =[
            'font' =>['bold' => true],
            'alignment' =>['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            'borders'=>['bottom' =>['style'=> \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM]]
        ];
        $spreadsheet->getActiveSheet()->getStyle('A1:C1')->applyFromArray($cell_st);

//set columns width
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(16);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(18);

        $spreadsheet->getActiveSheet()->setAutoFilter($spreadsheet
            ->getActiveSheet()->calculateWorksheetDataDimension());

        $spreadsheet->getActiveSheet()->setTitle('Simple'); //set a title for Worksheet

        $oneMoreSheet= $spreadsheet->createSheet(1);
        $oneMoreSheet->setCellValue('A1', 'yes');


        $oneMoreSheet->setTitle("test");


        $oneMoreSheet= $spreadsheet->createSheet(2);
        $oneMoreSheet->setCellValue('A1', 'yes');


        $oneMoreSheet->setTitle("test2");


//make object of the Xlsx class to save the excel file
        $writer = new Xlsx($spreadsheet);
        $fxls ='excel-file_2.xlsx';
        $writer->save($fxls);


        /*$spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Hello World !');



        $writer = new Xlsx($spreadsheet);
        $writer->save('hello world.xlsx');
*/
        return $this->render('Tickets/home.html.twig',[ ]);
    }


    public function getAllFileCSV($date_debut,$date_fin){



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
            ['capteur'=> 'VICTORHUGO', 'magasin'=> 'Victor Hugo'],            //['capteur'=> 'WESTBOURNE', 'magasin'=> '']
        ];


        $p=0;

        $spreadsheet = new Spreadsheet();


        foreach ($agencies as $k=>$agency) {
            $header = [ "Date et heure", "Nombre de ventes", "Nombre d'entrées", "Taux de transformation"];
            $tableau = [];
            $tableau[0] = $header;

            $entryRepo = $this->getDoctrine()->getRepository(Entry::class);
            $entry = $entryRepo->allEntryBetween($agency['capteur'], $date_debut, $date_fin);

            $ticketRepo = $this->getDoctrine()->getRepository(Tickets::class);
            $tickets = $ticketRepo->allTicketBetween($agency['magasin'], $date_debut, $date_fin);


            if(empty($tickets) || empty($entry)){
                continue;
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


                dump($tableau);
                $oneMoreSheet= $spreadsheet->createSheet($p);
                $oneMoreSheet->fromArray(
                    $tableau
                );


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

                $oneMoreSheet->setTitle($agency['magasin']);

                $oneMoreSheet->setAutoFilter($spreadsheet
                    ->getActiveSheet()->calculateWorksheetDataDimension());
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
        $fxls ='Rapport.xlsx';
        $writer->save($fxls);

        dump();
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