<?php

declare(strict_types=1);

namespace Talav\UserBundle\EventListener\Tests;

use AppBundle\Entity\Media;
use PHPUnit\Framework\TestCase;
use Prony\Entity\Board;
use Prony\Entity\Status;
use Prony\Entity\Workspace;

final class WorkspaceTest extends TestCase
{
    /**
     * @test
     */
    public function it_does_not_add_the_same_object_twice()
    {
        $workspace = new Workspace();
        $board1 = new Board();
        $workspace->addBoard($board1);
        $workspace->addBoard($board1);
        $this->assertEquals(1, $workspace->getBoards()->count());
    }

    /**
     * @test
     */
    public function it_correctly_normalizes_board_positions()
    {
        $workspace = new Workspace();
        $board1 = new Board();
        $workspace->addBoard($board1);
        $board2 = new Board();
        $workspace->addBoard($board2);
        $workspace->normalizeBoards($board2);
        $this->assertEquals(1, $board1->getPosition());
        $this->assertEquals(0, $board2->getPosition());
    }

    /**
     * @test
     */
    public function it_correctly_allows_board_update()
    {
        $workspace = new Workspace();
        $board1 = new Board();
        $board1->setName("Board 1");
        $board1->setPosition(1);
        $workspace->addBoard($board1);
        $board2 = new Board();
        $board2->setName("Board 2");
        $board2->setPosition(0);
        $workspace->addBoard($board2);
        $this->assertEquals(1, $board1->getPosition());
        $this->assertEquals(0, $board2->getPosition());
        $board1->setPosition(0);
        $workspace->normalizeBoards($board1);
        $this->assertEquals(0, $board1->getPosition());
        $this->assertEquals(1, $board2->getPosition());
    }

    /**
     * @test
     */
    public function it_does_not_add_the_same_status_twice()
    {
        $workspace = new Workspace();
        $status1 = new Status();
        $workspace->addStatus($status1);
        $workspace->addStatus($status1);
        $this->assertEquals(1, $workspace->getStatuses()->count());
    }

    /**
     * @test
     */
    public function it_correctly_normalizes_status_positions()
    {
        $workspace = new Workspace();
        $status1 = new Status();
        $workspace->addStatus($status1);
        $status2 = new Status();
        $workspace->addStatus($status2);
        $workspace->normalizeStatuses($status2);
        $this->assertEquals(1, $status1->getPosition());
        $this->assertEquals(0, $status2->getPosition());
    }
}