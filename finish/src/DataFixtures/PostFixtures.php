<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use App\Service\RandomPostGenerator;

class PostFixtures extends Fixture
{

    private $randomPostGenerator;

    public function __construct(RandomPostGenerator $randomPostGenerator)
    {
        $this->randomPostGenerator = $randomPostGenerator;
    }

    public function load(ObjectManager $manager)
    {
        $postToCreate = $this->randomPostGenerator->createRandomPost();

        $manager->persist($postToCreate);
        $manager->flush();
    }
}
