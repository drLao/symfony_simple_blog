<?php

namespace App\Service;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;


class PostServiceHelper
{


    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function fetchPostFromDbBySlug(string $slugOfPostToFind): Post
    {
        $postFromDbBySlug = $this->entityManager->getRepository(Post::class)->findOneBy(['slug' => $slugOfPostToFind]);
        return $postFromDbBySlug;
    }

    public function fetchAllPostsFromDb(): array
    {
        $allPostFromDb = $this->entityManager->getRepository(Post::class)->findAllPostsThatHaveBeenPosted();
        return $allPostFromDb;
    }

    public function getPostInArrayOfStrings(Post $postToArrayOfStrings): array
    {
        $postedAt = $postToArrayOfStrings->getPostedAt();

        if (!$postedAt) {
            $postedAt = "Not posted yet...";
        } else {
            $postedAt = $postedAt->format("Y-m-d H:i:s");
        }

        $postInArrayOfStrings = [
            'postTitle' => $postToArrayOfStrings->getTitle(),
            'postSlug' => $postToArrayOfStrings->getSlug(),
            'postedAt' => $postedAt,
            'postText' => $postToArrayOfStrings->getPostBody(),
            'postVotes' => $this->getVotesInString($postToArrayOfStrings),
        ];

        return $postInArrayOfStrings;
    }

    public function getVotesInString(Post $post): string
    {
        $votesCount = $post->getPostVotes();

        $prefix = $votesCount >= 0 ? " + " : " - ";

        return sprintf(
            '%s %d',
            $prefix,
            abs($votesCount));
    }

}