<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Factory\CommentFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CommentFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        CommentFactory::createMany(3);
    }
}
