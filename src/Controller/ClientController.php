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

class ClientController extends AbstractController
{
    private $em;
    private $clientRepository;
    private $operationRepository;
    private $bienRepository;

    public function __construct(
        EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->clientRepository = $this->em->getRepository(Client::class);
        $this->operationRepository = $this->em->getRepository(Operation::class);
        $this->clientRepository = $this->em->getRepository(Client::class);
        $this->bienRepository = $this->em->getRepository(Bien::class);
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

    #[Route('/client/delete/{id}', name: 'delete_client')]
    public function delete($id)
    {
        $c=$this->clientRepository->find($id);
        if($c!=null)
        {
            if($c->getOperations() != null)
            {
                $this->addFlash(
                    'warning', 'Suppression impossible veiller supprimer ses Operations avant tout'
                );
                return $this->redirectToRoute('list_client');
            }
            $this->em->remove($c);
            $this->em->flush();
        }
        return $this->redirectToRoute('list_client');
    }
    #[Route('/client/operation/persiste', name: 'persiste_operation')]
    public function persisteOperation(Request $request): Response
    {
        if($request->isMethod("POST")) {
            if ($this->isCsrfTokenValid('operation', $request->request->get('operation_token'))) {
                $operation = new Operation();
                $id=$request->request->get('id');
                $client = $this->clientRepository->find($request->request->get('idC'));
                if ( $id!= 0)
                {
                    $bien = $this->operationRepository->find($id);
                }
                else
                {
                    $operation->setDate(new \DateTime(date("Y-m-t")));
                    $operation->setClient($client);
                    $operation->setType($request->request->get('besoin'));
                    $bien = $this->bienRepository->find($request->request->get('bien'));
                    $operation->setBien($bien);
                    $operation->setContrat($bien->getContrat());
                    $operation->setAgent($this->getUser());
                    $bien->setStatut(false);
                }
                if ( $id==0) {
                    $this->em->persist($operation);
                }
                $this->em->flush();
                $this->addFlash(
                    'notice', 'Opération Effectuée avec succés'
                );
                return $this->redirectToRoute('list_operation',['id'=>$request->request->get('idC')]);
            }
        }
        return $this->redirectToRoute('list_operation');
    }
}
