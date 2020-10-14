<?php


namespace App\Controller\Admin;


use App\Repository\CommentRepositoryInterface;
use Symfony\Component\Routing\Annotation\Route;

class AdminCommentController extends AdminBaseController
{
    private $commentRepository;

    public function __construct(CommentRepositoryInterface $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    /**
     * @Route("/admin/comments/", name="admin_comments")
     */
    public function index()
    {
        $forRender = $this->renderDefault();
        $forRender['title'] = 'Admin: all comments';
        $forRender['comments'] = $this->commentRepository->getAllComments();

        return $this->render('admin/comments/index.html.twig', $forRender);
    }

    /**
     * @Route("/admin/comment/hide/{commentId}", name="admin_comment_hide")
     * @param int $commentId
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function hideComment(int $commentId)
    {
        $comment = $this->commentRepository->getCommentBy($commentId);
        $this->commentRepository->setIsHidden($comment);
        $this->addFlash('success', "The comment #$commentId is hidden now!");

        return $this->redirectToRoute('admin_comments');
    }

    /**
     * @Route("/admin/comment/publish/{commentId}", name="admin_comment_publish")
     * @param int $commentId
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function publishComment(int $commentId)
    {
        $comment = $this->commentRepository->getCommentBy($commentId);
        $this->commentRepository->setIsPublished($comment);
        $this->addFlash('success', "The comment #$commentId is published now!");

        return $this->redirectToRoute('admin_comments');
    }

    /**
     * @Route("/admin/comment/delete/{commentId}", name="admin_comment_delete")
     * @param int $commentId
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteComment(int $commentId)
    {
        $comment = $this->commentRepository->getCommentBy($commentId);
        $this->commentRepository->setDeleteComment($comment);
        $this->addFlash('success', "The comment #$commentId was deleted!");

        return $this->redirectToRoute('admin_comments');
    }
}