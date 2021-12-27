<?php

namespace App\Controller;

use App\Entity\Facture;
use App\Entity\Operation;
use App\Entity\Reglement;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FactureController extends AbstractController
{
    private $em;
    private $factureRepository;
    private $operationRepository;
    private $reglementRepository;

    public function __construct(
        EntityManagerInterface $em)
    {
        $this->em=$em;
        $this->factureRepository = $this->em->getRepository(Facture::class);
        $this->operationRepository = $this->em->getRepository(Operation::class);
        $this->reglementRepository = $this->em->getRepository(Reglement::class);
    }
    #[Route('/facture/{id}', name: 'list_facture')]
    public function index($id): Response
    {
        $data['operation'] = null;
        if ( $id!= 0)
        {
            $operation = $this->operationRepository->find($id);
            $data['operation'] = $operation;
            $data['factures'] = $operation->getFactures();
        }
        else{
            $data['factures'] = $this->factureRepository->findAll();
        }

        return $this->render('facture/list.html.twig',
            $data
        );
    }

    #[Route('/facture/add/{id}/{idF}', name: 'add_facture')]
    public function add($id,$idF): Response
    {
        $data['facture'] = $this->factureRepository->find($idF);
        $data['operation'] = $this->operationRepository->find($id);
        return $this->render('facture/add.html.twig',
            $data
        );
    }

    #[Route('/facture/regler/{id}', name: 'regler_facture')]
    public function regler_facture(Request $request, $id): Response
    {
        $facture = $this->factureRepository->find($id);
        $montant = $request->request->get('montantRecu');
        if($montant != $facture->getMontant())
        {
            $this->addFlash(
                'notice', 'Réglement non effectué erreur sur le montant'
            );
            return $this->redirectToRoute('add_facture',['id'=>$facture->getOperation()->getId(),'idF'=> $id]);
        }
        $reglement = new Reglement();
        $reglement->setFacture($facture);
        $reglement->setNum($facture->getNum());
        $reglement->setMontant($facture->getMontant());
        $reglement->setAgent($this->getUser());
        $reglement->setDate(new \DateTime(date("Y-m-d", strtotime('NOW'))));
        $facture->setEtat(true);

        $this->em->persist($reglement);

        $this->em->flush();
        $this->addFlash(
            'notice', 'Facture Réglée avec succés'
        );
        return $this->redirectToRoute('list_facture',['id'=>$facture->getOperation()->getId()]);
    }

    #[Route('/facture/delete/{id}', name: 'delete_facture')]
    public function delete_facture($id): Response
    {
        $f = $this->factureRepository->find($id);
        if($f!=null)
        {
            if($f->getReglement() != null)
            {
                $this->addFlash(
                    'warning', 'Suppression impossible veiller supprimer ses Operations avant tout'
                );
                return $this->redirectToRoute('list_facture',['id'=>$f->getOperation()->getId()]);

            }
            $this->em->remove($f);
            $this->em->flush();
        }
        return $this->redirectToRoute('list_facture',['id'=>$f->getOperation()->getId()]);
    }
}
