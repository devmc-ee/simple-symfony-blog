<?php


namespace App\Repository;


use App\Entity\Post;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface PostRepositoryInterface
{
    /**
     * @return Post
     */
    public function getAllPosts(): array;

    /**
     * @param \App\Repository\inr $postId
     *
     * @return Post
     */
    public function getPost(int $postId): object;

    /**
     * @param \App\Entity\Post $post
     *
     * @return Post
     */
    public function setCreatePost(Post $post, UploadedFile $file): object;

    /**
     * @param \App\Entity\Post                                         $post
     *
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile|null $file
     *
     * @return Post
     */
    public function setUpdatePost(Post $post, ?UploadedFile $file): object;

    /**
     * @param \App\Entity\Post $post
     * @param string           $fileName
     *
     * @return mixed
     */
    public function setDeletePost(Post $post) ;
}