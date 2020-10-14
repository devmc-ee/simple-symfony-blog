<?php


namespace App\Repository;


use App\Entity\Comment;
use App\Entity\Post;

interface CommentRepositoryInterface
{
    public function getAllComments():array ;
    public function getAllCommentsBy(int $postId): array;

    public function getCommentBy(int $commentId):Comment ;

    public function getAllLatestComments(int $postId, string $lastCommentId):array ;

    public function createComment(Comment $comment, Post $post): Comment;

    public function deleteAllByPostId(int $postId);

    public function setIsHidden(Comment $comment);

    public function setIsPublished(Comment $comment);
}