<?php

namespace App\DataFixtures;

use App\Factory\PostFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use App\Service\RandomPostGenerator;

class PostFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
       PostFactory::createMany(10);

       PostFactory::new()->unpublishedPosts()->many(5)->create();


    }
}
