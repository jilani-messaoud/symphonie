<?php

namespace App\Test\Controller;

use App\Entity\Projec;
use App\Repository\ProjecRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProjecControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private ProjecRepository $repository;
    private string $path = '/projec/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Projec::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Projec index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'projec[nomprojet]' => 'Testing',
            'projec[description]' => 'Testing',
            'projec[duree]' => 'Testing',
            'projec[prix]' => 'Testing',
        ]);

        self::assertResponseRedirects('/projec/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Projec();
        $fixture->setNomprojet('My Title');
        $fixture->setDescription('My Title');
        $fixture->setDuree('My Title');
        $fixture->setPrix('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Projec');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Projec();
        $fixture->setNomprojet('My Title');
        $fixture->setDescription('My Title');
        $fixture->setDuree('My Title');
        $fixture->setPrix('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'projec[nomprojet]' => 'Something New',
            'projec[description]' => 'Something New',
            'projec[duree]' => 'Something New',
            'projec[prix]' => 'Something New',
        ]);

        self::assertResponseRedirects('/projec/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getNomprojet());
        self::assertSame('Something New', $fixture[0]->getDescription());
        self::assertSame('Something New', $fixture[0]->getDuree());
        self::assertSame('Something New', $fixture[0]->getPrix());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Projec();
        $fixture->setNomprojet('My Title');
        $fixture->setDescription('My Title');
        $fixture->setDuree('My Title');
        $fixture->setPrix('My Title');

        $this->repository->save($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/projec/');
    }
}
