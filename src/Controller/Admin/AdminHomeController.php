<?php


namespace App\Controller\Admin;


use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminHomeController
 *
 * @package App\Controller\Admin
 */
class AdminHomeController extends AdminBaseController
{
	/**
	 * @Route("/admin", name="admin_home")
	 * @return \Symfony\Component\HttpFoundation\Response
	 *
	 */
	public function index()
	{
        return $this->redirectToRoute('admin_posts');
	}

}