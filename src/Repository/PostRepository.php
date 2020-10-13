<?php

namespace App\Repository;

use App\Entity\Post;
use App\Service\FileManagerServiceInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Security;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository implements PostRepositoryInterface
{
    private $entityManager;
    private $fileManager;
    private $security;

    public function __construct(
        ManagerRegistry $registry,
        EntityManagerInterface $entityManager,
        FileManagerServiceInterface $fileManagerService,
        Security $security
    ) {
        parent::__construct($registry, Post::class);
        $this->entityManager = $entityManager;
        $this->fileManager = $fileManagerService;
        $this->security = $security;
    }


    /**
     * @return Post
     */
    public function getAllPosts(): array
    {
        return parent::findAll();
    }

    /**
     * @param \App\Repository\inr $postId
     *
     * @return Post
     */
    public function getPost(int $postId): object
    {
        return parent::find($postId);
    }

    /**
     * @param \App\Entity\Post $post
     *
     * @return Post
     */
    public function setCreatePost(Post $post, UploadedFile $file): object
    {
        if ($file) {
            $fileName = $this->fileManager->imagePostUpload($file);
            $post->setImage($fileName);
        }
        $post->setCreatedAtValue();
        $user = $this->security->getUser();
        $post->setCreatedBy($user);
        $post->setUpdateAtValue();
        $post->setIsPublished();
        $this->entityManager->persist($post);
        $this->entityManager->flush();

        return $post;
    }

    /**
     * @param \App\Entity\Post $post
     *
     * @return Post
     */
    public function setUpdatePost(Post $post, UploadedFile $file): object
    {
        $fileName = $post->getImage();
        if ($file) {

            if ($fileName) {
                $this->fileManager->removePostImage($fileName);
            }
            $fileName = $this->fileManager->imagePostUpload($file);
            $post->setImage($fileName);
        }
        $post->setUpdateAtValue();
        //$this->entityManager->persist($post);
        $this->entityManager->flush();
    }

    /**
     * @param \App\Entity\Post $post
     *
     * @return mixed
     */
    public function setDeletePost(Post $post)
    {
        $fileName = $post->getImage();
        if($fileName){
            $this->fileManager->removePostImage($fileName);
        }
        $this->entityManager->remove($post);
        $this->entityManager->flush();
    }
}
