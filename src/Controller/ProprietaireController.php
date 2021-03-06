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

class ProprietaireController extends AbstractController
{
    private $em;
    private $proprietaireRepository;
    private $bienRepository;
    private $localisationRepository;

    public function __construct(
        EntityManagerInterface $em)
    {
        $this->em=$em;
        $this->proprietaireRepository=$this->em->getRepository(Proprietaire::class);
        $this->bienRepository=$this->em->getRepository(Bien::class);
        $this->localisationRepository=$this->em->getRepository(Localisation::class);
    }

    #[Route('/proprietaire', name: 'list_proprietaire')]
    public function index(): Response
    {
        $data['proprietaires'] = $this->proprietaireRepository->findAll();
        return $this->render('proprietaire/list.html.twig',
            $data
        );
    }

    #[Route('/proprietaire/add/{id}', name: 'add_proprietaire')]
    public function add($id): Response
    {
        $data['proprietaire'] = $this->proprietaireRepository->find($id);
        return $this->render('proprietaire/add.html.twig',
            $data
        );
    }

    #[Route('/proprietaire/persiste', name: 'persiste_proprietaire')]
    public function persiste(Request $request): Response
    {
        if($request->isMethod("POST")) {
            if ($this->isCsrfTokenValid('proprietaire', $request->request->get('proprietaire_token'))) {
                $proprietaire = new Proprietaire();
                $id=$request->request->get('id');
                if ( $id!= 0)
                {
                    $proprietaire = $this->proprietaireRepository->find($id);
                }
                else
                {
                    $proprietaire->setNumero($request->request->get('numero'));
                    $proprietaire->setNom($request->request->get('nom'));
                    $proprietaire->setPrenom($request->request->get('prenom'));
                }
                $proprietaire->setTelephone($request->request->get('telephone'));

                if ( $id==0) {
                    $this->em->persist($proprietaire);
                }
                $this->em->flush();
                $this->addFlash(
                    'notice', 'Propri??taire Enregistr?? avec succ??s'
                );
                return $this->redirectToRoute('list_proprietaire');
            }
        }

        return $this->redirectToRoute('add_proprietaire');
    }

    #[Route('/proprietaire/delete/{id}', name: 'delete_proprietaire')]
    public function delete($id)
    {
        $p=$this->proprietaireRepository->find($id);
        if($p!=null)
        {
            if($p->getBiens()->count() > 0)
            {
                $this->addFlash(
                    'warning', 'Suppression impossible veiller supprimer ses Biens avant tout'
                );
                return $this->redirectToRoute('list_proprietaire');
            }
            $this->em->remove($p);
            $this->em->flush();
        }
        return $this->redirectToRoute('list_proprietaire');
    }

    #[Route('/proprietaire/bien/persiste', name: 'persiste_bien')]
    public function persisteBien(Request $request): Response
    {
        if($request->isMethod("POST")) {
            if ($this->isCsrfTokenValid('bien', $request->request->get('bien_token'))) {
                $bien = new Bien();
                $id=$request->request->get('id');
                $type=$request->request->get('type');
                $proprietaire = $this->proprietaireRepository->find($request->request->get('idP'));
                if ( $id!= 0)
                {
                    $bien = $this->bienRepository->find($id);
                }
                else
                {
                    $contrat = new Contrat();
                    $contrat->setReference($request->request->get('numero_contrat'));
                    $bien->setContrat($contrat);

                    $localisation = new Localisation();
                    $localisation->setRue($request->request->get('rue'));
                    $localisation->setCodePostal($request->request->get('code_postal'));
                    $localisation->setLocaliste($request->request->get('localite'));
                    $bien->setLocalisation($localisation);

                    if($type == "Maison"){
                        $maison = new Maison();
                        $maison->setEtat($request->request->get('etat'));
                        $maison->setPrix($request->request->get('prix'));
                        $bien->setMaison($maison);
                    }
                    if($type == "Appartement"){
                        $appartement = new Appartement();
                        $appartement->setLoyerMensuel($request->request->get('loyer'));
                        $appartement->setMontantCaution($request->request->get('caution'));
                        $appartement->setNbrChambre($request->request->get('chambres'));
                        $appartement->setTypeBail($type);
                        $bien->setAppartement($appartement);
                    }
                    if($type == "Terrain"){
                        $terrain = new Terrain();
                        $terrain->setPrix($request->request->get('prix'));
                        $terrain->setSuperficie($request->request->get('superficie'));
                        $bien->setTerrain($terrain);
                    }
                    if($type == "Studio"){
                        $studio = new Studio();
                        $studio->setLoyerMensuel($request->request->get('loyer'));
                        $studio->setMontantCaution($request->request->get('caution'));
                        $studio->setSuperficie($request->request->get('superficie'));
                        $bien->setStudio($studio);
                    }

                    $bien->setLibelle($request->request->get('libelle'));
                    $bien->setDate(new \DateTime(date("Y-m-d")));
                    $bien->setStatut(true);//Dispo
                    $bien->setNumero($type."-".$proprietaire->getNumero()."-".trim($contrat->getReference()));
                    $user = $this->getUser();
                    $bien->setAgent($user);
                    $bien->setProprietaire($proprietaire);
                }
                $bien->setBesoin($request->request->get('besoin'));

                if ( $id==0) {
                    $this->em->persist($bien->getLocalisation());
                    $this->em->persist($bien);
                }
                $this->em->flush();
                $this->addFlash(
                    'notice', 'Bien Enregistr?? avec succ??s'
                );
                return $this->redirectToRoute('list_bien',['id'=>$request->request->get('idP')]);
            }
        }
        return $this->redirectToRoute('list_bien',['id'=>$request->request->get('idP')]);
    }
}
