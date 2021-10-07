<?php

namespace App\DataFixtures;

use App\Factory\CommentFactory;
use App\Factory\PostFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager): void
    {
        CommentFactory::createMany(100, function() {
            return [
                'post' => PostFactory::random()
            ];
        });
    }

    public function getDependencies()
    {
        return [
            PostFixtures::class,
        ];
    }
}
