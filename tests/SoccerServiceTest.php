<?php
/**
 * Created by PhpStorm.
 * User: candidate
 * Date: 19/3/19
 * Time: 12:30 PM
 */

namespace App\Tests;

use App\Service\SoccerService;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;

class SoccerServiceTest extends TestCase
{

    public function testValidateByIds()
    {
        $mockEntityManager = $this->createMock(EntityManager::class);
        $soccerService = new SoccerService($mockEntityManager);

        $return = $soccerService->validateByIds([1,2],[1,2]);
        $this->assertSame([],$return);

        $return = $soccerService->validateByIds([3,2],[1,2]);
        $this->assertNotEquals([],$return)  ;
    }
}
