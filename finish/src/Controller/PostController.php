<?php

namespace App\Controller;

use App\Service\RandomPostGenerator;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

use App\Entity\Post;
use App\Repository\PostRepository;

use Psr\Log\LoggerInterface;
use Sentry\State\HubInterface;

use App\Service\RandomTextGeneratorHelper;
use App\Service\MarkdownHelper;

class PostController extends AbstractController
{

    private $logger;
    private $isDebugEnabled;
    private $postRepository;

    public function __construct(LoggerInterface $logger,
        bool $isDebugEnabled,
        RandomTextGeneratorHelper $randomTextGeneratorHelper,
        PostRepository $postRepository)
    {
        $this->logger = $logger;
        $this->isDebugEnabled = $isDebugEnabled;
        $this->postRepository = $postRepository;
    }

    /**
     *
     * @Route("/posts/new")
     *
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function newPost( RandomPostGenerator $randomPostGenerator,
        EntityManagerInterface $entityManager): Response
    {
        $postToCreate = $randomPostGenerator->createRandomPost();

        $entityManager->persist($postToCreate);
        $entityManager->flush();

        return new Response(sprintf(
            "New post has #%d, slug %s",
            $postToCreate->getId(),
            $postToCreate->getSlug()
        ));
    }

    /**
     * @Route("/", name="app_homepage")
     */
    public function homepage(): Response
    {
        $allPosts = $this->postRepository->findAllPostsThatHaveBeenPosted();

        return $this->render('post/homepage.html.twig', [
            'allPosts' => $allPosts,
            ]
        );
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

        $postFromDb = $this->postRepository->findOneBy(['slug' => $slug]);

        if (!$postFromDb) {
            $sentry = $sentryHub->getClient();
            $sentry->captureMessage("User found 404 page!");
            $sentry->flush();

            return $this->render('404.html.twig');
        }

        $postedAt = $postFromDb->getPostedAt();

        if (!$postedAt) {
            $postedAt = "Not posted yet...";
        } else {
            $postedAt = $postedAt->format("Y-m-d H:i:s");
        }

        $parsedPostText = $markdownHelper->parse($postFromDb->getPostBody());
        return $this->render('post/post.html.twig', [
            'post' => $postFromDb->getTitle(),
            'postedAt' => $postedAt,
            'postText' => $parsedPostText,
            'postComments' => $postComments,
        ]);
    }
}
