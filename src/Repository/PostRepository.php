<?php

namespace App\Repository;

use App\Entity\Post;
use App\Service\FileManagerServiceInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
    private $commentRepository;

    /**
     * @param string $entityClass The class name of the entity this repository manages
     */
    public function __construct(
        ManagerRegistry $registry,
        EntityManagerInterface $entityManager,
        FileManagerServiceInterface $fileManagerService,
        Security $security,
        CommentRepositoryInterface $commentRepository
    ) {
        parent::__construct($registry, Post::class);
        $this->entityManager = $entityManager;
        $this->fileManager = $fileManagerService;
        $this->security = $security;
        $this->commentRepository = $commentRepository;
    }


    /**
     * @return array
     */
    public function getAllPosts(): array
    {
        return parent::findBy(
            [],
            ['id' => 'DESC']
        );
    }

    /**
     * @param int $postId
     *
     * @return Post
     */
    public function getPost(int $postId): Post
    {
        return parent::find($postId);
    }

    /**
     * @param \App\Entity\Post $post
     *
     * @return Post
     */
    public function setCreatePost(Post $post, UploadedFile $file): Post
    {
        if ($file) {
            $fileName = $this->fileManager->imagePostUpload($file);
            $post->setImage($fileName);
        }

        $user = $this->security->getUser();
        $post->setCreatedBy($user);
        $post->setCreatedAtValue();
        $post->setUpdateAtValue();
        $post->setIsPublished();

        $this->entityManager->persist($post);
        $this->entityManager->flush();

        return $post;
    }

    /**
     * @param \App\Entity\Post                                    $post
     *
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     *
     * @return Post
     */
    public function setUpdatePost(Post $post, ?UploadedFile $file): Post
    {

        if ($file) {
            $fileName = $post->getImage();
            if ($fileName) {
                $this->fileManager->removePostImage($fileName);
            }
            $fileName = $this->fileManager->imagePostUpload($file);
            $post->setImage($fileName);
        }
        $post->setUpdateAtValue();

        $this->entityManager->flush();

        return $post;
    }

    /**
     * @param \App\Entity\Post $post
     *
     */
    public function setDeletePost(Post $post)
    {
        $fileName = $post->getImage();
        if ($fileName) {
            $this->fileManager->removePostImage($fileName);
        }

        $this->commentRepository->deleteAllByPostId($post->getId());

        $this->entityManager->remove($post);
        $this->entityManager->flush();
    }

    /**
     * @param string $query
     *
     * @return array
     */
    public function searchBy(string $query): array
    {
        return $this->createQueryBuilder('p')
                    ->orWhere('p.title LIKE :query')
                    ->orWhere('p.content LIKE :query')
                    ->setParameter('query', '%'.$query.'%')
                    ->getQuery()
                    ->getResult();

    }
}
