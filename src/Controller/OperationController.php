<?php

namespace App\Controller;

use App\Entity\Bien;
use App\Entity\Client;
use App\Entity\Operation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OperationController extends AbstractController
{
    private $em;
    private $operationRepository;
    private $clientRepository;
    private $bienRepository;

    public function __construct(
        EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->operationRepository = $this->em->getRepository(Operation::class);
        $this->clientRepository = $this->em->getRepository(Client::class);
        $this->bienRepository = $this->em->getRepository(Bien::class);
    }
    #[Route('/operation/{id}', name: 'list_operation')]
    public function index($id): Response
    {
        $data['client'] = null;
        if($id != 0)
        {
            $client = $this->clientRepository->find($id);
            $data['client'] = $client;
            $data['operations'] = $client->getOperations();
        }
        else
        {
            $data['operations'] = $this->operationRepository->findAll();
        }
        $data['types'] = array("Location", "Achat");
        return $this->render('operation/list.html.twig',$data);
    }

    #[Route('/operation/add/{id}/{idO}', name: 'add_operation')]
    public function add($id,$idO): Response
    {
        $data['operation'] = $this->operationRepository->find($idO);
        $data['client'] = $this->clientRepository->find($id);
        $data['besoins'] = array("Location", "Achat");
        $data['bienLocation'] = $this->bienRepository->findBy(['besoin'=>"Location", 'statut'=>true]);
        $data['bienAchat'] = $this->bienRepository->findBy(['besoin'=>"Vente", 'statut'=>true]);
        return $this->render('operation/add.html.twig',
            $data
        );
    }

    #[Route('/operation/persiste', name: 'persiste_operation')]
    public function persiste(Request $request): Response
    {
        return $this->redirectToRoute('list_operation');
    }
}
