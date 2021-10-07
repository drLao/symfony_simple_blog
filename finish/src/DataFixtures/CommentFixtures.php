<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Post;
use App\Factory\CommentFactory;
use App\Factory\PostFactory;
use App\Service\RandomTextGeneratorHelper;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CommentFixtures extends Fixture
{
//    private $postFactory;
//
//    public function __construct(PostFactory $postFactory)
//    {
//        $this->postFactory = $postFactory;
//    }

    private $randomTextGeneratorHelper;

    public function __construct(RandomTextGeneratorHelper $randomTextGeneratorHelper)
    {
        $this->randomTextGeneratorHelper = $randomTextGeneratorHelper;
    }


    public function load(ObjectManager $manager): void
    {
        $comment = new Comment();
        $comment->setCommentBody($this->randomTextGeneratorHelper->generateRandomWords(25));
        $comment->setUsername('dr_Lao');


        $post = new Post();
        $post->setTitle($this->randomTextGeneratorHelper->generateRandomWords(7));
        $post->setPostBody($this->randomTextGeneratorHelper->generateRandomWords(45));



        $manager->persist($post);
        $comment->setPost($post);
        $manager->persist($comment);
        $manager->flush();




        //CommentFactory::createMany(3);
    }
}
