<?php

namespace App\Controller;

use Knp\Bundle\MarkdownBundle\MarkdownParserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;

class PostsController extends AbstractController
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
                         MarkdownParserInterface $markdownParser,
                         CacheInterface $cache): Response
    {
        $postComments = [
            'Make sure your cat is sitting `purrrfectly` still 🤣',
            'Honestly, I like furry shoes better than MY cat',
            'Maybe... try saying the spell backwards?',
        ];
        $postText = "I've been turned into a cat, any __thoughts__ on how to turn back?\n While I'm adorable, I don't really care for cat food.";
        $parsedPostText = $cache->get('markdown_'.md5($postText), function () use ($postText, $markdownParser) {
            return $markdownParser->transformMarkdown($postText);
        });


        return $this->render('post/post.html.twig', [
            'post' => ucwords(str_replace('-', ' ', $slug)),
            'postText' => $parsedPostText,
            'postComments' => $postComments,
 1       ]);
    }
}
