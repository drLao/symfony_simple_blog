<?php

namespace App\Controller;

use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Psr\Log\LoggerInterface;

class CommentController extends AbstractController
{
    /**
     * @Route("/comments/{id}/vote/", methods="POST", name="app_comments_vote")
     */
    public function commentVote(Comment $comment,
        LoggerInterface $logger,
        Request $request,
        EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $direction = $data['directionOfVoteComment'];

        if ($direction === 'up') {
            $logger->info('Voting up!');
            $comment->upVoteComment();
        } else {
            $logger->info('Voting down!');
            $comment->downVoteComment();
        }

        $entityManager->persist($comment);
        $entityManager->flush();

        return $this->json(['votes' => $comment->getCommentVotes()]);
    }
}
