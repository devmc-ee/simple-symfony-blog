<?php

namespace App\Repository;

use App\Entity\Comment;
use App\Entity\Post;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository implements CommentRepositoryInterface
{
    private $entityManager;

    public function __construct(
        ManagerRegistry $registry,
        EntityManagerInterface $entityManager
    ) {
        parent::__construct($registry, Comment::class);
        $this->entityManager = $entityManager;
    }


    public function getAllComments(int $postId): array
    {
        return parent::findBy(
            ['post' => $postId],
            ['id' => 'DESC']
        );
    }

    public function createComment(Comment $comment, Post $post): object
    {

        $comment->setCreatedAtValue();
        $comment->setIsPublished();
        $comment->setPost($post);
        $this->entityManager->persist($comment);
        $this->entityManager->flush();

        return $comment;
    }

    public function getAllLatestComments(int $postId, string $lastCommentId): array
    {
        $lastCommentIdValue = $lastCommentId ?: 0;
        $qb = $this->createQueryBuilder('c')
                                  ->andWhere('c.post = :post_id')
                                  ->andWhere('c.id > :lastCommentId')
                                  ->setParameter('post_id', $postId)
                                  ->setParameter('lastCommentId', $lastCommentIdValue)
                                  ->orderBy('c.id', 'DESC');

        $query = $qb->getQuery();

        return $query->getArrayResult();

    }


    public function deleteAllByPostId(int $postId)
    {

    }
}
