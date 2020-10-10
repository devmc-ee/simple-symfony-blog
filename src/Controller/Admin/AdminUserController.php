<?php


namespace App\Controller\Admin;


use App\Entity\User;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminUserController
 * - controls users
 *
 * @package App\Controller\Admin
 */
class AdminUserController extends AdminBaseController 
{
	/**
	 * @Route("/admin/users", name="admin_users")
	 * @return \Symfony\Component\HttpFoundation\Response
	 *
	 */
	public function index()
	{
		$users = $this->getDoctrine()->getRepository(User::class)->findAll();

		$forRender = $this->renderDefault();
		$forRender['title'] = 'Admin: users';
		$forRender['users'] = $users;

		return $this->render('admin/users/index.html.twig', $forRender);
	}
}