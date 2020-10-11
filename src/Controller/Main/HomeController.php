<?php


namespace App\Controller\Main;

use App\Entity\Post;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HomeController
 *
 * @package App\Controller\Main
 */
class HomeController extends BaseController
{
	/**
	 * @Route("/", name="home")
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
}