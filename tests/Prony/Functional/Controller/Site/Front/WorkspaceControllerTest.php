<?php

namespace Prony\Tests\Functional\Controller\Site\Front;

use Doctrine\ORM\EntityManager;
use Prony\Entity\Board;
use Prony\Entity\Status;
use Prony\Entity\Tag;
use Prony\Entity\Workspace;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\AbstractBrowser;
use Symfony\Component\DomCrawler\Crawler;

class WorkspaceControllerTest extends WebTestCase
{
    private ?EntityManager $entityManager;

    private ?AbstractBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $domain = $this::$kernel->getContainer()->getParameter('domain');
        $this->client->setServerParameters(['HTTP_HOST' => $domain]);

        $this->entityManager = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    /**
     * @test
     */
    public function it_correctly_shows_workspace_delete_page()
    {
        $workspace = $this->getWorkspace();
        $this->assertInstanceOf(Workspace::class, $workspace);
        $crawler = $this->makeRequestToDeletePage($workspace);

        $this->assertStringContainsStringIgnoringCase('Are you sure? This action cannot be undone. Enter the name of this workspace below to confirm.', $crawler->html());
        $this->assertStringContainsStringIgnoringCase('name="prony_delete_workspace[workspace_name]"', $crawler->html());
        $this->assertStringContainsStringIgnoringCase('value="Delete"', $crawler->html());
    }

    /**
     * @test
     */
    public function it_shows_error_messages_for_wrong_workspace_name()
    {
        $workspace = $this->getWorkspace();
        $this->assertInstanceOf(Workspace::class, $workspace);

        $crawler = $this->submitForm($workspace, '');
        $this->assertStringContainsStringIgnoringCase('This value should not be blank.', $crawler->html());

        $crawler = $this->submitForm($workspace, 'Wrong name');
        $this->assertStringContainsStringIgnoringCase('Please enter correct workspace name', $crawler->html());
    }

    /**
     * @test
     */
    public function it_allows_to_delete_workspace()
    {
        $workspace = $this->getWorkspace();
        $this->assertInstanceOf(Workspace::class, $workspace);
        $boards = $workspace->getBoards();
        $statuses = $workspace->getStatuses();
        $tags = $workspace->getTags();

        $crawler = $this->submitForm($workspace, $workspace->getName());
        $this->assertStringContainsStringIgnoringCase('Workspace has been deleted', $crawler->html());

        $workspace = $this->getWorkspace();
        $this->assertNull($workspace);

        foreach ($boards as $board) {
            $entity = $this->getEntityById(Board::class, $board->getId());
            $this->assertNull($entity);
        }
        foreach ($statuses as $status) {
            $entity = $this->getEntityById(Status::class, $status->getId());
            $this->assertNull($entity);
        }
        foreach ($tags as $tag) {
            $entity = $this->getEntityById(Tag::class, $tag->getId());
            $this->assertNull($entity);
        }
    }

    private function getWorkspace(): ?Workspace
    {
        /** @var Workspace $workspace */
        $workspace = $this->entityManager
            ->getRepository(Workspace::class)
            ->findOneBy(['name' => 'Workspace 1']);

        return $workspace;
    }

    private function submitForm(Workspace $workspace, string $value): Crawler
    {
        $crawler = $this->makeRequestToDeletePage($workspace);
        $form = $crawler->selectButton('Delete')->form();
        $form['prony_delete_workspace[workspace_name]'] = $value;
        $this->client->followRedirects(true);
        $crawler = $this->client->submit($form);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        return $crawler;
    }

    private function makeRequestToDeletePage(Workspace $workspace): Crawler
    {
        $crawler = $this->client->request('GET', "/client/workspace/{$workspace->getId()}/delete");
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        return $crawler;
    }

    /**
     * @param string $entityName
     * @param mixed $id
     * @return object|null
     */
    private function getEntityById(string $entityName, $id)
    {
        return $this->entityManager
            ->getRepository($entityName)
            ->findOneBy(['id' => $id]);
    }
}
