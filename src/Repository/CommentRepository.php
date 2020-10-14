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

    /**
     * @param string $entityClass The class name of the entity this repository manages
     */
    public function __construct(
        ManagerRegistry $registry,
        EntityManagerInterface $entityManager
    ) {
        parent::__construct($registry, Comment::class);
        $this->entityManager = $entityManager;
    }


    /**
     * @return array
     */
    public function getAllComments(): array
    {
        return parent::findBy(
            [],
            ['id' => 'DESC']
        );
    }

    /**
     * @param int $postId
     *
     * @return array
     */
    public function getAllCommentsBy(int $postId): array
    {
        return parent::findBy(
            ['post' => $postId],
            ['id' => 'DESC']
        );
    }

    /**
     * @param int $postId
     *
     * @return array
     */
    public function getAllPublishedCommentsBy(int $postId): array
    {
        return parent::findBy(
            [
                'post'         => $postId,
                'is_published' => 1,
            ],
            ['id' => 'DESC']
        );
    }

    /**
     * @param \App\Entity\Comment $comment
     * @param \App\Entity\Post    $post
     *
     * @return \App\Entity\Comment
     */
    public function createComment(Comment $comment, Post $post): Comment
    {

        $comment->setCreatedAtValue();
        $comment->setIsPublished();
        $comment->setPost($post);

        $this->entityManager->persist($comment);
        $this->entityManager->flush();

        return $comment;
    }

    /**
     * Retrieve comments starting from the lastCommentId, that is defined when user open post page.
     * 0 in case no registered comments
     * @param int    $postId
     * @param string $lastCommentId
     *
     * @return array
     */
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


    /**
     * @param int $postId
     */
    public function deleteAllByPostId(int $postId)
    {
        $comments = parent::findBy(['post' => $postId]);
        if(count($comments)){
            foreach ( $comments as $comment){
                $this->entityManager->remove($comment);
            }
            $this->entityManager->flush();
        }


    }

    /**
     * @param \App\Entity\Comment $comment
     */
    public function setIsHidden(Comment $comment)
    {
        $comment->setIsHidden();
        $this->entityManager->persist($comment);
        $this->entityManager->flush();
    }

    /**
     * @param int $commentId
     *
     * @return \App\Entity\Comment
     */
    public function getCommentBy(int $commentId): Comment
    {
        return parent::find($commentId);
    }

    /**
     * @param \App\Entity\Comment $comment
     */
    public function setIsPublished(Comment $comment)
    {
        $comment->setIsPublished();
        $this->entityManager->persist($comment);
        $this->entityManager->flush();
    }

    /**
     * @param \App\Entity\Comment $comment
     */
    public function setDeleteComment(Comment $comment)
    {
        $this->entityManager->remove($comment);
        $this->entityManager->flush();
    }
}
