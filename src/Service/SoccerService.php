<?php
/**
 * Created by PhpStorm.
 * User: candidate
 * Date: 19/3/19
 * Time: 11:17 AM
 */

namespace App\Service;


use App\Entity\Player;
use App\Entity\Team;
use Doctrine\ORM\EntityManagerInterface;

class SoccerService
{
    private  $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createTeamPlayers(int $team, $players){
        $team = $this->entityManager->getRepository(Team::class)->find($team);
        if($team instanceof Team){
           $players = $this->validatePayers($players);
           if($players === false){
               return "Player are not found";
           }
           else{
               foreach ($players as $player){
                   $team->addPlayer($player);
               }
               $this->entityManager->persist($team);
               $this->entityManager->flush();
               return "Team is mapped with players";
           }
        }else{
            return "Team is not found";
        }
    }

    public function validatePayers($playerIds){
        $flag = 0;
        $players = $this->entityManager->getRepository(Player::class)->findPlayerIdsExist($playerIds);
        foreach ($players as $player){
            if(!in_array($player->getId(),$playerIds)){
               $flag = 1;
                break;
            }
            $foundIds[] = $player->getId();
        }
        if($flag){
            return false;
        }
        $this->validateByIds($playerIds,$foundIds);
        return $players;
    }

    public function getPlayers($teamId) {
         $result =  $this->entityManager->getRepository(Player::class)->findBy(['team' => $teamId]);
        $players = [];
        if($result != null){
          foreach ($result as $key => $value) {
            $temp['firstName'] = $value->getFirstName();
            $temp['lastName'] = $value->getLastName();
            $players[] = $temp;
          }
        }
    }


   public function validateByIds($playIds, $foundIds){
        return array_diff($playIds,$foundIds);
    }

    public function getPlayerOfTeam($teamId){
        $result = $this->entityManager->getRepository(Player::class)->findBy(array("team" => $teamId));
         $players = [];
        if($result != null){
          foreach ($result as $key => $value) {
            $temp['firstName'] = $value->getFirstName();
            $temp['lastName'] = $value->getLastName();
            $players[] = $temp;
          }
        }
        return $players;
    }
}