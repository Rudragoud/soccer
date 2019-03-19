<?php

namespace App\Controller;

use App\Entity\Team;
use App\Form\TeamType;
use App\Service\SoccerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class TeamController extends AbstractController
{
    /**
     * @Route("/team", name="team")
     * @param EntityManagerInterface $entityManager
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function index(EntityManagerInterface $entityManager)
    {
        $teams = $entityManager->getRepository(Team::class)->findAll();
        $teamArray = [];
        if($teams != null){
            foreach ($teams as $team){
                $temp['name'] = $team->getName();
                $temp['uri'] = $team->getLogoUri();
                $teamArray[] = $temp;
            }
        }
        return $this->json(['status' => 'success','teams' => $teamArray]);
    }

    /**
     * @Route("/create-team", name="create-team")
     * @param $request \Symfony\Component\HttpFoundation\Request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function createTeam(Request $request)
    {
        $name = $request->get('name');
        $logoUri = $request->get('logoUri');
       // $form = $this->createForm(TeamType::class,['name' => $name,'logoUri' => $logoUri]);
        //$form->handleRequest();
       // if($form->isValid()){
            $team = new Team();
            $team->setName($name.time());
            $team->setLogoUri($logoUri);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($team);
            $entityManager->flush();
            $status = 'success';
            $id = $team->getId();
        /*}else{
            $status = 'failed';
            $id = null;
        }*/

        return $this->json(['status' => $status,'teams' => ['id' => $id]]);
    }

    /**
     * @Route("/player-team", name="player-team")
     */
    public function getPlayerInTeam(SoccerService $soccerService){

    }
}
