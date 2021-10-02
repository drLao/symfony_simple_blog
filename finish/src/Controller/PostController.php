<?php

namespace App\Controller;

use App\Entity\Post;

use App\Service\RandomTextGeneratorHelper;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Sentry\State\HubInterface;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

use App\Service\MarkdownHelper;

class PostController extends AbstractController
{

    private $logger;
    private $isDebugEnabled;

    public function __construct(LoggerInterface $logger, bool $isDebugEnabled)
    {
        $this->logger = $logger;
        $this->isDebugEnabled = $isDebugEnabled;
    }

    /**
     *
     * @Route("/posts/new")
     *
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function newPost(EntityManagerInterface $entityManager, RandomTextGeneratorHelper $randomTextGeneratorHelper): Response
    {
        $post = new Post();

        $textForPost = $randomTextGeneratorHelper->generateRandomWords(random_int(40, 150));

        $post->setTitle("Test title for post")
            ->setSlug("test-slug-for-post-".random_int(0, 1000))
            ->setPostBody($textForPost);

        if (random_int(1, 50) > 20) {
            $post->setPostedAt(new \DateTime(sprintf('-%d days', random_int(1, 100))));
        }

        $entityManager->persist($post);
        $entityManager->flush();

        return new Response(sprintf(
            "New post has #%d, slug %s",
            $post->getId(),
            $post->getSlug()
        ));
    }

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
        MarkdownHelper $markdownHelper,
        HubInterface $sentryHub): Response
    {

        if ($this->isDebugEnabled) {
            $this->logger->info("debug mode enabled");
        }

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
        ]);
    }
}
