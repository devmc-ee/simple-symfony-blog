<?php


namespace App\Controller\Admin;


use App\Entity\Post;
use App\Form\PostType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminPostController extends AdminBaseController
{
    /**
     * @Route("/admin/posts", name="admin_posts")
     */
    public function index()
    {
        $posts = $this->getDoctrine()->getRepository(Post::class)
                      ->findAll();
        $forRender = $this->renderDefault();
        $forRender['title'] = 'Admin: posts';
        $forRender['posts'] = $posts;

        return $this->render('admin/posts/index.html.twig', $forRender);
    }

    /**
     * @Route("/admin/posts/create", name="admin_posts_create")
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function create(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $post->setCreatedAtValue();
            $post->setUpdateAtValue();
            $post->setIsPublished();

            $entityManager->persist($post);
            $entityManager->flush();

            $this->addFlash('success', 'Post is created!');

            return $this->redirectToRoute('admin_posts');
        }

        $forRender = $this->renderDefault();
        $forRender['title'] = 'Admin: Create post';
        $forRender['form'] = $form->createView();

        return $this->render('admin/posts/form.html.twig', $forRender);
    }

}