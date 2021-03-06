<?php

namespace App\Controller;

use App\Entity\Roles;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AgentController extends AbstractController
{
    private $em;
    private $agentRepository;
    private $roleRepository;
    private $passwordHasher;
    public function __construct(
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher)
    {
        $this->em=$em;
        $this->passwordHasher=$passwordHasher;
        $this->agentRepository=$this->em->getRepository(User::class);
        $this->roleRepository=$this->em->getRepository(Roles::class);
    }
    #[Route('/agent', name: 'list_agent')]
    public function index(): Response
    {
        $data['agents'] = $this->agentRepository->getAllByRoleNom('ROLE_AGENT');
        return $this->render('agent/list.html.twig',
            $data
        );
    }

    #[Route('/agent/add/{id}', name: 'add_agent')]
    public function add($id): Response
    {
        $data['agent'] = $this->agentRepository->find($id);
        $data['agents'] = $this->agentRepository->getAllByRoleNom('ROLE_AGENT');
        return $this->render('agent/add.html.twig',
            $data
        );
    }

    #[Route('/agent/transferer', name: 'transferer_agent')]
    public function transferer(Request $request): Response
    {
        if($request->isMethod("POST")) {
            if ($this->isCsrfTokenValid('agent', $request->request->get('agent_token'))) {
                $id = $request->request->get('id');
                $agent = $request->request->get('agent');
                $ancien = $this->agentRepository->find($id);
                $nouveau = $this->agentRepository->find($agent);
                if($ancien->getBiens() != null)
                {
                    foreach ($ancien->getBiens() as $b)
                    {
                        $b->setAgent($nouveau);
                    }
                }
                if($ancien->getOperations() != null)
                {
                    foreach ($ancien->getOperations() as $o)
                    {
                        $o->setAgent($nouveau);
                    }
                }
                $this->em->flush();
                $this->addFlash(
                    'warning', 'Dossiers Transf??r??s avec succ??s'
                );
                return $this->redirectToRoute('add_agent', ['id' => $id]);
            }
        }
        return $this->redirectToRoute('list_agent');
    }
    #[Route('/agent/delete/{id}', name: 'delete_agent')]
    public function delete($id)
    {
        $a=$this->agentRepository->find($id);
        if($a!=null)
        {
            $this->em->remove($a);
            $this->em->flush();
        }
        $this->addFlash(
            'notice', 'Agent Supprim?? avec succ??s'
        );
        return $this->redirectToRoute('list_agent');
    }
    #[Route('/agent/persiste', name: 'persiste_agent')]
    public function persiste(Request $request): Response
    {
        if($request->isMethod("POST")) {
            if ($this->isCsrfTokenValid('agent', $request->request->get('agent_token'))) {
                $agent = new User();
                $id=$request->request->get('id');
                if ( $id!= 0)
                {
                    $agent = $this->agentRepository->find($id);
                }
                else
                {
                    $agent->setNom($request->request->get('nom'));
                    $agent->setPrenom($request->request->get('prenom'));
                    $agent->setUsername($request->request->get('username'));
                    $roles = $this->roleRepository->findBy(['nom' => 'ROLE_AGENT']);
                    $agent->setRoles($roles);
                }
                $password = $request->request->get('password');
                if( $password != $request->request->get('password1'))
                {
                    $this->addFlash(
                        'notice', 'Mots de pass pas conformes'
                    );
                    return $this->redirectToRoute("add_agent", ['id' => $id]);

                }
                $agent->setTelephone($request->request->get('telephone'));
                $passwordHashed = $this->passwordHasher->hashPassword(
                    $agent,
                    $password
                );
                $agent->setPassword($passwordHashed);
                if ( $id==0) {
                    $this->em->persist($agent);
                }
                $this->em->flush();
                $this->addFlash(
                    'notice', 'Agent Enregistr?? avec succ??s'
                );
                return $this->redirectToRoute('list_agent');
            }
        }

        return $this->redirectToRoute('add_agent');
    }
}
