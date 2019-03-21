<?php

namespace App\Controller;

use App\Entity\Team;
use App\Form\TeamType;
use App\Service\SoccerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormInterface;

class TeamController extends AbstractController
{
    /**
     * @Route("/api/team", name="team", methods={"GET"})
     * @param EntityManagerInterface $entityManager
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function listAction(EntityManagerInterface $entityManager,SoccerService $soccerService)
    {
        $teams = $entityManager->getRepository(Team::class)->findAll();
        $teamArray = [];
        if($teams != null){
            foreach ($teams as $team){
                $temp['name'] = $team->getName();
                $temp['uri'] = $team->getLogoUri();
                $temp['players'] = $soccerService->getPlayerOfTeam($team->getId());
                $teamArray[] = $temp;
            }
        }
        return $this->json(['status' => 'success','teams' => $teamArray]);
    }

    /**
     * @Route("/api/team", name="create-team", methods = {"POST"})
     * @param $request \Symfony\Component\HttpFoundation\Request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function createTeam(Request $request)
    {
        $team = new Team();
        $form = $this->createForm(TeamType::class, $team);
        $this->processForm($request, $form);
        if($form->isValid()){
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($team);
            $entityManager->flush();
            $status = 'success';
            $id = $team->getId();
             return $this->json(['status' => $status,'data' => $id]);
        }else{
            $status = 'failed';
            $errors = $this->getErrorsFromForm($form);
            return $this->json(['status' => $status,'error' => $errors]);
        }
    }

    /**
     * @Route("/api/team-player/{id}", name="get-team", methods={"GET"})
     */

    public function getPlayerInTeam($id, SoccerService $soccerService){
        $teamPlayers = $soccerService->getPlayerOfTeam($id);
        return $this->json(['status' => 'success','players' => $teamPlayers]);
    }

    private function processForm(Request $request, FormInterface $form)
    {
        $data = json_decode($request->getContent(), true);
        $clearMissing = $request->getMethod() != 'PATCH';
        $form->submit($data, $clearMissing);
    }

    private function getErrorsFromForm(FormInterface $form)
    {
        $errors = array();
        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }
        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                if ($childErrors = $this->getErrorsFromForm($childForm)) {
                    $errors[$childForm->getName()] = $childErrors;
                }
            }
        }
        return $errors;
    }

    /**
     * @Route("/api/team/{id}", name="delete-team", methods={"DELETE"})
     */
    public function deleteTeam($id, EntityManagerInterface $entityManager){
        $team = $entityManager->getRepository(Team::class)->find($id);
        if($team != NULL){
            $entityManager->remove($team);
            $entityManager->flush();
            return $this->json(['status' => 'success','message' => "Team is deleted success"]);
        }else{
            return $this->json(['status' => 'failed','message' => "Team not exist or deleted"]);

        }
    }
}
