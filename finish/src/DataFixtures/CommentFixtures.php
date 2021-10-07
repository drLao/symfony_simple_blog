<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Post;
use App\Factory\CommentFactory;
use App\Factory\PostFactory;
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

    public function load(ObjectManager $manager): void
    {
        $comment = new Comment();
        $comment->setCommentBody('This question is the best? I wish... I knew the answer.');
        $comment->setUsername('weaverryan');


        $post = new Post();
        $post->setTitle('How to un-disappear your wallet.');
        $post->setPostBody('... I should not have done this...');



        $manager->persist($post);
        $comment->setPost($post);
        $manager->persist($comment);
        $manager->flush();




        //CommentFactory::createMany(3);
    }
}
