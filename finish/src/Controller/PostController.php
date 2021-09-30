<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Service\MarkdownHelper;

class PostController extends AbstractController
{
    /**
     * @Route("/", name="app_homepage")
     */
    public function homepage(): Response
    {
        return $this->render('post/homepage.html.twig');
    }

    /**
     * @Route("/posts/{slug}", name="app_posts_show")
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function show(string $slug,
        MarkdownHelper $markdownHelper): Response
    {

        $postComments = [
            'Make sure your cat is sitting `purrrfectly` still 🤣',
            'Honestly, I like furry shoes better than MY cat',
            'Maybe... try saying the spell backwards?',
        ];
        $postText = "I've been turned into a cat, any __thoughts__ on how to turn back?\n While I'm adorable, I don't really care for cat food.";

        $parsedPostText = $markdownHelper->parse($postText);

        return $this->render('post/post.html.twig', [
            'post' => ucwords(str_replace('-', ' ', $slug)),
            'postText' => $parsedPostText,
            'postComments' => $postComments,
 1       ]);
    }
}
