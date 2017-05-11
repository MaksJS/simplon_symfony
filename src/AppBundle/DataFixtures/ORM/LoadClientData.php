<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Client;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class LoadClientData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $client = new Client();
        $client->setDesignation('GDF');
        $client->setEmail('admin@gdf.com');
        $client->setWebsite('http://www.gdf.com');

        $manager->persist($client);

        $client = new Client();
        $client->setDesignation('EDF');
        $client->setEmail('admin@edf.com');
        $client->setWebsite('http://www.edf.com');

        $manager->persist($client);
        $manager->flush();
    }

    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 3;
    }
}