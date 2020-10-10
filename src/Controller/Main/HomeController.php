<?php


namespace App\Controller\Main;

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
		$forRender = $this->renderDefault();

		return $this->render('main/index.html.twig', $forRender);
	}
}