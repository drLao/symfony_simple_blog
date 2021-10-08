<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

use Doctrine\ORM\EntityManagerInterface;

use Psr\Log\LoggerInterface;
use Sentry\State\HubInterface;

use App\Entity\Post;

use App\Service\MarkdownHelper;
use App\Service\PostServiceHelper;
use App\Service\RandomPostGenerator;


class PostController extends AbstractController
{

    private $logger;
    private $isDebugEnabled;
    private $postServiceHelper;

    public function __construct(
        LoggerInterface $logger,
        PostServiceHelper $postServiceHelper,
        bool $isDebugEnabled
    ) {
        $this->logger = $logger;
        $this->isDebugEnabled = $isDebugEnabled;
        $this->postServiceHelper = $postServiceHelper;
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
        $allPosts = $this->postServiceHelper->fetchAllPostsFromDb();

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

        $postFromDb = $this->postServiceHelper->fetchPostFromDbBySlug($slug);

        if (!$postFromDb) {
            $sentry = $sentryHub->getClient();
            $sentry->captureMessage("User found 404 page!");
            $sentry->flush();

            return $this->render('404.html.twig');
        }

        $arrayOfPostPropsToTwig = $this->postServiceHelper->getPostInArrayOfStrings($postFromDb);

        $parsedPostText = $markdownHelper->parse($arrayOfPostPropsToTwig["postText"]);

        $postComments = $postFromDb->getComments();

        $arrayOfPostPropsToTwig["postText"] = $parsedPostText;
        $arrayOfPostPropsToTwig["postId"] = $postFromDb->getId();
        $arrayOfPostPropsToTwig["postComments"] = $postComments;

        return $this->render('post/post.html.twig', $arrayOfPostPropsToTwig);
    }

    /**
     * @Route ("/posts/{id}/vote", name="app_posts_vote", methods="POST")
     */
    public function postVote(Post $post,
        Request $request,
        EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);
        $direction = $data['directionOfVotePost'];

        dump($direction);
        if ($direction === 'up') {
            $post->upVotePost();
        } elseif ($direction === 'down') {
            $post->downVotePost();
        } else {
            return $this->render('404.html.twig');
        }

        $entityManager->persist($post);
        $entityManager->flush();

        return $this->json(['votes' => $post->getPostVotes()]);

//        $direction = $request->request->get('direction');
//
//        if ($direction === 'up') {
//            $post->upVotePost();
//        } elseif ($direction === 'down') {
//            $post->downVotePost();
//
//        $entityManager->flush();
//
//        return $this->redirectToRoute(
//            'app_posts_show',
//            ['slug' => $post->getSlug()]);
    }
}
