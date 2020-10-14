<?php

namespace App\Repository;

use App\Entity\Comment;
use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

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


    public function getAllComments(): array
    {
        return parent::findBy(
            [],
            ['id' => 'DESC']
        );
    }

    public function getAllCommentsBy(int $postId): array
    {
        return parent::findBy(
            ['post' => $postId],
            ['id' => 'DESC']
        );
    }

    public function createComment(Comment $comment, Post $post): Comment
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

    public function setIsHidden(Comment $comment)
    {
        $comment->setIsHidden();
        $this->entityManager->persist($comment);
        $this->entityManager->flush();
    }

    public function getCommentBy(int $commentId): Comment
    {
        return parent::find($commentId);
    }

    public function setIsPublished(Comment $comment)
    {
        $comment->setIsPublished();
        $this->entityManager->persist($comment);
        $this->entityManager->flush();
    }
}
