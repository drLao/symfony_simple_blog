<?php

namespace App\Controller;

use App\Entity\Post;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Sentry\State\HubInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;
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
    public function newPost(EntityManagerInterface $entityManager): Response
    {
        $post = new Post();

        $post->setTitle("Test title for post")
            ->setSlug("test-slug-for-post-".random_int(0, 1000));
        $post->setPostBody(<<<EOF
fieri  hauriret  week  continent  vimus  call  violet  figistrus  postquam  caracterem  tangerent  risu  frigus  
      frigus  sublust  violet  week  sublust  fieri  gavisus  perveniunt  fera  fuge  omnis  continent  latere  current 
      tangerent  pascuntur  sublust  postquam  frigus  adepto  castra  call  figistrus  call  call  fieri  dirige       
      dentium  continent  vitae  hauriret  continent  obtinuit  adepto  current  sensi  fera  risu  hauriret  risu  aer 
      hauriret  exercitium  hauriret  perveniunt  fera  latere  continent  posuit  festinate  sublust  tunica           
      caracterem  nasum  fieri  adepto  fieri  vitae  ferrum  week  mortuus  perveniunt  frigus  gavisus  celebre       
      repentino  gavisus  vimus  obtinuit  perveniunt  mortuus  frigus  latere  castra  dirige  adepto  aer  omnis      
      mortuus  sensi  postquam  week  ferrum  pascuntur  obtinuit  proeorous  perveniunt
EOF);

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
 1       ]);
    }


}
