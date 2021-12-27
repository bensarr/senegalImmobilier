<?php

namespace App\Controller;

use App\Entity\Bien;
use App\Entity\Client;
use App\Entity\Facture;
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
    private $factureRepository;

    public function __construct(
        EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->operationRepository = $this->em->getRepository(Operation::class);
        $this->clientRepository = $this->em->getRepository(Client::class);
        $this->bienRepository = $this->em->getRepository(Bien::class);
        $this->factureRepository = $this->em->getRepository(Facture::class);
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

    #[Route('/operation/delete/{id}', name: 'delete_operation')]
    public function delete($id)
    {
        $o=$this->operationRepository->find($id);
        $clientId = $o->getClient()->getId();
        if($o!=null)
        {
            if($o->getFactures() != null)
            {
                $this->addFlash(
                    'warning', 'Suppression impossible veiller supprimer ses Factures avant tout'
                );
                return $this->redirectToRoute('list_operation', ['id'=>$clientId]);
            }
            if($o->getContrat() != null)
            {
                $o->setContrat(null);
            }
            $o->getBien()->setStatut(true);
            $this->em->remove($o);
            $this->em->flush();
        }
        return $this->redirectToRoute('list_operation', ['id'=>$clientId]);
    }

    #[Route('/operation/facture/persiste', name: 'persiste_facture')]
    public function persiste(Request $request): Response
    {
        if($request->isMethod("POST")) {
            if ($this->isCsrfTokenValid('facture', $request->request->get('facture_token'))) {
                $facture = new Facture();
                $id=intval($request->request->get('id'));
                $operation = $this->operationRepository->find($request->request->get('idO'));
                if ( $id!= 0)
                {
                    $facture = $this->factureRepository->find($id);
                }
                else
                {
                    $facture->setAgent($this->getUser());
                    $facture->setOperation($operation);
                }
                if($operation->getType() == "Location")
                    $facture->setDate(new \DateTime(date("Y-m-d", strtotime($request->request->get('mois')."-"."01"))));
                else
                    $facture->setDate(new \DateTime(date("Y-m-d", strtotime($request->request->get('date')))));
                $facture->setNum("FCT-".$facture->getOperation()->getContrat()->getReference()."-".$facture->getDate()->format("Y-m-d"));
                $facture->setMontant($request->request->get('montant'));
                $facture->setEtat(false);
                if ( $id==0) {
                    $this->em->persist($facture);
                }
                $this->em->flush();
                $this->addFlash(
                    'notice', 'Facture Enregistrér avec succés'
                );
                return $this->redirectToRoute('list_facture',['id'=>$request->request->get('idO')]);
            }
        }
        return $this->redirectToRoute('list_facture',['id'=>$request->request->get('idO')]);
    }
}
