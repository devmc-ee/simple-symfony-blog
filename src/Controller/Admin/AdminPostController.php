<?php


namespace App\Controller\Admin;


use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminPostController
 *
 * @package App\Controller\Admin
 */
class AdminPostController extends AdminBaseController
{
    private $postRepository;

    public function __construct(PostRepositoryInterface $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    /**
     * @Route("/admin/posts", name="admin_posts")
     */
    public function index()
    {
        $forRender = $this->renderDefault();
        $forRender['title'] = 'Admin: all posts';
        $forRender['posts'] = $this->postRepository->getAllPosts();

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
            $file = $form->get('image')->getData();
            $this->postRepository->setCreatePost($post, $file);
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

    /**
     * @Route("admin/posts/edit/{id}", name="admin_post_edit")
     * @param int                                       $id
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function update(int $id, Request $request)
    {
        $post = $this->postRepository->getPost($id);
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('save')->isClicked()) {
                $file = $form->get('image')->getData();

               $this->postRepository->setUpdatePost($post, $file);
                $this->addFlash('success', 'Post has been updated!');
            }
            if ($form->get('delete')->isClicked()) {
                $this->postRepository->setDeletePost($post);
                $this->addFlash('success', 'Post has been deleted!');
            }


            return $this->redirectToRoute('admin_posts');
        }

        $forRender = $this->renderDefault();
        $forRender['title'] = 'Updating post';
        $forRender['form'] = $form->createView();

        return $this->render('admin/posts/form.html.twig', $forRender);
    }
}