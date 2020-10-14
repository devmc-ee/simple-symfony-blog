<?php


namespace App\Controller\Main;


use App\Entity\Comment;
use App\Repository\CommentRepositoryInterface;
use App\Repository\PostRepositoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class CommentController
 *
 * @package App\Controller\Main
 */
class CommentController extends BaseController
{
    private $postRepository;
    private $commentRepository;

    /**
     * CommentController constructor.
     *
     * @param \App\Repository\CommentRepositoryInterface $commentRepository
     * @param \App\Repository\PostRepositoryInterface    $postRepository
     */
    public function __construct(
        CommentRepositoryInterface $commentRepository,
        PostRepositoryInterface $postRepository
    ) {
        $this->commentRepository = $commentRepository;
        $this->postRepository = $postRepository;
    }

    /**
     * Create comment via ajax request (post)
     * Returns all last comments that were added later than the registered  one
     *
     * @Route("/comment/create", name="comment_create")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent());

        $postId = htmlspecialchars($data->post_id);
        $commentContent = htmlspecialchars($data->comment_content);
        $lastCommentId = htmlspecialchars($data->last_comment_id);

        $lastNewComments = '';

        if (empty($commentContent)) {
            return new JsonResponse([]);
        }

        $post = $this->postRepository->getPost($postId);

        $comment = new Comment();
        $comment->setContent($commentContent);
        $comment = $this->commentRepository->createComment($comment, $post);

        $createdAt = $comment->getCreatedAt();

        if ($lastCommentId) {
            $lastNewComments = $this->commentRepository->getAllLatestComments($postId, $lastCommentId);
        }

        //comments_returned_count- the number of comments to be sent for helping to define  whether
        // to json object is iterable or not (on the front-side in js)
        $response = [
            "comments_returned_count" => empty($lastNewComments)
                ? 0 : count($lastNewComments),
            "last_comments"           => empty($lastNewComments)
                ?
                [
                    "id"         => $comment->getId(),
                    "content"    => $comment->getContent(),
                    "created_at" => $createdAt->format('d.m.Y H:i'),
                ] : $lastNewComments,
        ];

        return new JsonResponse($response);
    }

}