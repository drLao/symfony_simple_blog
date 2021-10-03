<?php

namespace App\Service;

use App\Entity\Post;

class RandomPostGenerator
{

    private $randomTextGeneratorHelper;

    public function __construct(RandomTextGeneratorHelper $randomTextGeneratorHelper)
    {
        $this->randomTextGeneratorHelper = $randomTextGeneratorHelper;
    }

    public function createRandomPost(): Post
    {
        $createdRandomPost = new Post();

        $titleForPost = $this
            ->randomTextGeneratorHelper
            ->generateRandomWords(random_int(3, 6));
        $slugForPost = trim(
            str_replace("  ", "-", $titleForPost)
        );
        $textForPost = $this
            ->randomTextGeneratorHelper
            ->generateRandomWords(random_int(40, 150));

        $createdRandomPost->setTitle($titleForPost)
            ->setSlug($slugForPost)
            ->setPostBody($textForPost);

        if (random_int(1, 50) > 20) {
            $createdRandomPost->setPostedAt(new \DateTime(sprintf('-%d days', random_int(1, 100))));
        }

        return $createdRandomPost;
    }
}