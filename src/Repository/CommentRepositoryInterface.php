<?php


namespace App\Repository;


use App\Entity\Comment;
use App\Entity\Post;

/**
 * Interface CommentRepositoryInterface
 *
 * @package App\Repository
 */
interface CommentRepositoryInterface
{
    /**
     * @return array
     */
    public function getAllComments(): array;

    /**
     * @param int $postId
     *
     * @return array
     */
    public function getAllCommentsBy(int $postId): array;

    /**
     * @param int $commentId
     *
     * @return \App\Entity\Comment
     */
    public function getCommentBy(int $commentId): Comment;

    /**
     * @param int $postId
     *
     * @return array
     */
    public function getAllPublishedCommentsBy(int $postId): array;

    /**
     * @param int    $postId
     * @param string $lastCommentId
     *
     * @return array
     */
    public function getAllLatestComments(int $postId, string $lastCommentId): array;

    /**
     * @param \App\Entity\Comment $comment
     * @param \App\Entity\Post    $post
     *
     * @return \App\Entity\Comment
     */
    public function createComment(Comment $comment, Post $post): Comment;

    /**
     * @param int $postId
     *
     * @return mixed
     */
    public function deleteAllByPostId(int $postId);

    /**
     * @param \App\Entity\Comment $comment
     *
     * @return mixed
     */
    public function setIsHidden(Comment $comment);

    /**
     * @param \App\Entity\Comment $comment
     *
     * @return mixed
     */
    public function setIsPublished(Comment $comment);

    /**
     * @param \App\Entity\Comment $comment
     *
     * @return mixed
     */
    public function setDeleteComment(Comment $comment);
}