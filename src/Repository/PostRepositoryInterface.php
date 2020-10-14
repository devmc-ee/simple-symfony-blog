<?php


namespace App\Repository;


use App\Entity\Post;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Interface PostRepositoryInterface
 *
 * @package App\Repository
 */
interface PostRepositoryInterface
{
    /**
     * @return array
     */
    public function getAllPosts(): array;

    /**
     * @param int $postId
     *
     * @return Post
     */
    public function getPost(int $postId): Post;

    /**
     * @param \App\Entity\Post $post
     *
     * @return Post
     */
    public function setCreatePost(Post $post, UploadedFile $file): Post;

    /**
     * @param \App\Entity\Post                                         $post
     *
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile|null $file
     *
     * @return Post
     */
    public function setUpdatePost(Post $post, ?UploadedFile $file): Post;

    /**
     * @param \App\Entity\Post $post
     *
     * @return mixed
     */
    public function setDeletePost(Post $post) ;

    /**
     * @param string $query
     *
     * @return array
     */
    public function searchBy(string $query):array;
}