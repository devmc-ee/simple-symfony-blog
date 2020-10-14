<?php


namespace App\Repository;


use App\Entity\Comment;
use App\Entity\Post;

interface CommentRepositoryInterface
{
    public function getAllComments(int $postId):array ;

    public function getAllLatestComments(int $postId, string $lastCommentId):array ;

    public function createComment(Comment $comment, Post $post): object;

    public function deleteAllByPostId(int $postId);
}