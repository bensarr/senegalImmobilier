<?php

namespace App\Controller;

use App\Entity\Reglement;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReglementController extends AbstractController
{

    private $em;
    private $reglementRepository;
    public function __construct(
        EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->reglementRepository = $this->em->getRepository(Reglement::class);
    }

        #[Route('/reglement', name: 'reglement')]
    public function index(): Response
    {
        $data['reglements'] = $this->reglementRepository->findAll();
        return $this->render('reglement/list.html.twig', $data);
    }
}
