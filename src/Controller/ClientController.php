<?php

namespace App\Controller;

use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClientController extends AbstractController
{
    private $em;
    private $clientRepository;
    public function __construct(
        EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->clientRepository = $this->em->getRepository(Client::class);
    }
    #[Route('/client', name: 'list_client')]
    public function index(): Response
    {
        $data['clients'] = $this->clientRepository->findAll();
        return $this->render('client/list.html.twig', $data);
    }
    #[Route('/client/add/{id}', name: 'add_client')]
    public function add($id): Response
    {
        $data['client'] = $this->clientRepository->find($id);
        return $this->render('client/add.html.twig',
            $data
        );
    }

    #[Route('/client/persiste', name: 'persiste_client')]
    public function persiste(Request $request): Response
    {
        if($request->isMethod("POST")) {
            if ($this->isCsrfTokenValid('client', $request->request->get('client_token'))) {
                $client = new Client();
                $id=$request->request->get('id');
                if ( $id!= 0)
                {
                    $client = $this->clientRepository->find($id);
                }
                else
                {
                    $client->setNom($request->request->get('nom'));
                    $client->setPrenom($request->request->get('prenom'));
                }
                $client->setTelephone($request->request->get('telephone'));
                $client->setAdresse($request->request->get('adresse'));

                if ( $id==0) {
                    $this->em->persist($client);
                }
                $this->em->flush();
                $this->addFlash(
                    'notice', 'Client Enregistré avec succés'
                );
                return $this->redirectToRoute('list_client');
            }
        }

        return $this->redirectToRoute('add_client');
    }
}
