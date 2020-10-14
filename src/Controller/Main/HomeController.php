<?php


namespace App\Controller\Main;

use App\Entity\Post;
use App\Repository\CommentRepositoryInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HomeController
 *
 * @package App\Controller\Main
 */
class HomeController extends BaseController
{
    private $commentRepository;

    public function __construct(
        CommentRepositoryInterface $commentRepository
    )
    {
        $this->commentRepository = $commentRepository;
    }

    /**
	 * @Route("/", name="home")
	 */
	public function index()
	{
	    $posts = $this->getDoctrine()->getRepository(Post::class)
            ->findBy([],['id'=>'DESC']);

		$forRender = $this->renderDefault();
        $forRender['title'] = 'Home: all posts';
        $forRender['posts'] = $posts;
		return $this->render('main/index.html.twig', $forRender);
	}
}