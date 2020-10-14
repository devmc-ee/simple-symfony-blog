<?php


namespace App\Controller\Main;


use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use App\Repository\CommentRepositoryInterface;
use App\Repository\PostRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PostController
 *
 * @package App\Controller\Main
 */
class PostController extends BaseController
{
    private $commentRepository;
    private $entityManager;
    private $postRepository;

    /**
     * PostController constructor.
     *
     * @param \App\Repository\CommentRepositoryInterface $commentRepository
     * @param \Doctrine\ORM\EntityManagerInterface       $entityManager
     * @param \App\Repository\PostRepositoryInterface    $postRepository
     */
    public function __construct(
        CommentRepositoryInterface $commentRepository,
        EntityManagerInterface $entityManager,
        PostRepositoryInterface $postRepository
    ) {
        $this->commentRepository = $commentRepository;
        $this->entityManager = $entityManager;
        $this->postRepository = $postRepository;
    }

    /**
     * @Route("/posts", name="posts")
     */
    public function index()
    {
        $posts = $this->getDoctrine()->getRepository(Post::class)
                      ->findAll();

        $forRender = $this->renderDefault();
        $forRender['title'] = 'Home: all posts';
        $forRender['posts'] = $posts;

        return $this->render('main/index.html.twig', $forRender);

    }

    /**
     * @Route("/posts/{postId}", name="post_show")
     *
     * @param int                                       $postId
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(int $postId, Request $request)
    {
        $post = $this->getDoctrine()->getRepository(Post::class)
                     ->find($postId);

        $comment = new Comment();
        $commentsForm = $this->createForm(CommentType::class, $comment);
        $commentsForm->handleRequest($request);

        //just in case if the form request via http method, and not via ajax request
        if ($commentsForm->isSubmitted() && $commentsForm->isValid()) {

            if (empty(($commentsForm->get('content')->getData()))) {
                return $this->redirectToRoute('post_show', ['postId' => $postId]);
            }

            $this->commentRepository->createComment($comment, $post);
            $this->addFlash('success', 'Comment is added!');

            return $this->redirectToRoute('post_show',['postId'=>$postId]);
        }

        $comments = $this->commentRepository->getAllPublishedCommentsBy($postId);

        $forRender = $this->renderDefault();
        $forRender['title'] = 'Post: '.$post->getTitle();
        $forRender['post'] = $post;
        $forRender['comments'] = $comments;
        $forRender['commentForm'] = $commentsForm->createView();

        return $this->render('main/posts/post.html.twig', $forRender);
    }

    /**
     * @Route("search/posts/", name="search_posts")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function search(Request $request)
    {
        $query = $request->query->get('q');
        $searchResult = $this->postRepository->searchBy($query);

        $forRender = $this->renderDefault();
        $forRender['title'] = "Search results for: <i>$query</i>";
        $forRender['searchResult'] = $searchResult;

        return $this->render('main/posts/search.html.twig', $forRender);
    }
}