<?php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Routing\Annotation\Route;

class TicketsController extends Controller
{
    /**
     * @Route("/", name="Homepage")
     */
    public function indexAction(){
        $finder = new Finder();
        $finder->in('ftp://Stanley:StanleyFTPMF75@37.58.138.236/AIXENPROVENCE');
        foreach ($finder as $file){
            var_dump($file->getContents());
        }

        return $this->render('tickets/home.html.twig',[]);
    }
}