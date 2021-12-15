<?php

namespace App\Controller;

use App\Entity\Appartement;
use App\Entity\Bien;
use App\Entity\Contrat;
use App\Entity\Localisation;
use App\Entity\Maison;
use App\Entity\Proprietaire;
use App\Entity\Studio;
use App\Entity\Terrain;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BienController extends AbstractController
{
    private $em;
    private $bienRepository;
    private $proprietaireRepository;

    public function __construct(
        EntityManagerInterface $em)
    {
        $this->em=$em;
        $this->bienRepository = $this->em->getRepository(Bien::class);
        $this->proprietaireRepository = $this->em->getRepository(Proprietaire::class);
    }
    #[Route('/bien/{id}', name: 'list_bien')]
    public function index($id): Response
    {
        $data['proprietaire'] = null;
        if ( $id!= 0)
        {
            $proprietaire = $this->proprietaireRepository->find($id);
            $data['proprietaire'] = $proprietaire;
            $data['biens'] = $proprietaire->getBiens();
        }
        else{
            $data['biens'] = $this->bienRepository->findAll();
        }
        $data['types'] = array("Maison", "Appartement", "Terrain", "Studio");

        return $this->render('bien/list.html.twig',
            $data
        );
    }


    #[Route('/bien/add/{id}/{idB}', name: 'add_bien')]
    public function add($id,$idB): Response
    {
        $data['bien'] = $this->bienRepository->find($idB);
        $data['proprietaire'] = $this->proprietaireRepository->find($id);
        $data['types'] = array("Maison", "Appartement", "Terrain", "Studio");
        $data['besoins'] = array("Location", "Vente");
        return $this->render('bien/add.html.twig',
            $data
        );
    }
}
