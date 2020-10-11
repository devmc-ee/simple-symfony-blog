<?php


namespace App\Controller\Main;


use App\Entity\Post;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PostController
 *
 * @package App\Controller\Main
 */
class PostController extends BaseController
{
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
     * @Route("/posts/{id}", name="post_show")
     * @param int $id
     */
    public function show(int $id)
    {
        $post = $this->getDoctrine()->getRepository(Post::class)
            ->find($id);
        $forRender = $this->renderDefault();
        $forRender['title'] = $post->getTitle();
        $forRender['post'] = $post;
        return $this->render('main/posts/post.html.twig', $forRender);
    }
}