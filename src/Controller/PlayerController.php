<?php

namespace App\Controller;

use App\Entity\Player;
use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\SoccerService;
use Symfony\Component\Routing\Annotation\Route;

class PlayerController extends AbstractController
{
    /**
     * @Route("/api/player", name="player" , methods={"GET"})
     */
    public function index()
    {
        $players = $this->getDoctrine()->getRepository(Player::class)->findAll();
        $playerArray = [];
        if($players != null){
            foreach ($players as $player){
                $temp['firstName'] = $player->getFirstName();
                $temp['lastName'] = $player->getLastName();
                $temp['imageUri'] = $player->getImageUri();
                $playerArray[] = $temp;
            }
        }
        return $this->json(['status' => 'success','data' => ['players' => $playerArray]]);
    }

    /**
     * @Route("/api/player", name="create-player", methods={"POST"})
     * @param $request \Symfony\Component\HttpFoundation\Request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function createTeam(\Symfony\Component\HttpFoundation\Request $request)
    {
        $firstName = $request->get('firstName');
        $lastName = $request->get('lastName');
        $imageUri = $request->get('imageUri');
        $player = new Player();
        $player->setFirstName($firstName.time());
        $player->setLastName($lastName);
        $player->setImageUri($imageUri);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($player);
        $entityManager->flush();

        return $this->json(['status' => 'success','data' => ['id' => $player->getId()]]);
    }

    /**
     * @Route("/api/create-team-player", name="create-team-player", methods={"POST"})
     */
    public function createTeamPlayers(SoccerService $soccerService,\Symfony\Component\HttpFoundation\Request $request){
        $requestData = json_decode($request->getContent(),true);
        $result = $soccerService->createTeamPlayers($requestData['team'], $requestData['players']);
        return $this->json(['status' => 'success', 'data' => $result ]);
    }
}
