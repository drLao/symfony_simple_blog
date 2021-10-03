<?php

namespace App\Controller;

use App\Entity\Post;
use App\Service\RandomPostGenerator;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

use App\Repository\PostRepository;

use Psr\Log\LoggerInterface;
use Sentry\State\HubInterface;

use App\Service\MarkdownHelper;

class PostController extends AbstractController
{

    private $logger;
    private $isDebugEnabled;
    private $postRepository;

    public function __construct(
        LoggerInterface $logger,
        PostRepository $postRepository,
        bool $isDebugEnabled
    ) {
        $this->logger = $logger;
        $this->isDebugEnabled = $isDebugEnabled;
        $this->postRepository = $postRepository;
    }

    /**
     *
     * @Route("/posts/new", name="app_posts_new")
     *
     * @param \App\Service\RandomPostGenerator $randomPostGenerator
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newPost(
        RandomPostGenerator $randomPostGenerator,
        EntityManagerInterface $entityManager): Response
    {
        $postToCreate = $randomPostGenerator->createRandomPost();

        $entityManager->persist($postToCreate);
        $entityManager->flush();

        return new Response(
            sprintf(
                "New post has #%d, slug %s",
                $postToCreate->getId(),
                $postToCreate->getSlug()
            )
        );
    }

    /**
     * @Route("/", name="app_homepage", methods="GET")
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

        $arrayOfPostPropsToTwig = $postFromDb->getPostInArrayOfStrings();

        $parsedPostText = $markdownHelper->parse($arrayOfPostPropsToTwig["postText"]);

        $arrayOfPostPropsToTwig["postText"] = $parsedPostText;
        $arrayOfPostPropsToTwig["postComments"] = $postComments;

        return $this->render('post/post.html.twig', $arrayOfPostPropsToTwig);
    }

    /**
     * @Route ("/posts/{slug}/vote", name="app_posts_vote", methods="POST")
     */
    public function postVote(Post $post,
        Request $request,
        EntityManagerInterface $entityManager): Response
    {
        $direction = $request->request->get('direction');

        if ($direction === 'up') {
            $post->upVote();
        } elseif ($direction === 'down') {
            $post->downVote();
        } else {
            return $this->render('404.html.twig');
        }

        $entityManager->flush();

        return $this->redirectToRoute(
            'app_posts_show',
            ['slug' => $post->getSlug()]);
    }
}
