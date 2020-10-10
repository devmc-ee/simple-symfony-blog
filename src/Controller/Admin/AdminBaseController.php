<?php


namespace App\Controller\Admin;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class AdminBaseController
 *
 * @package App\Controller\Admin
 */
class AdminBaseController extends AbstractController
{
	/**
	 * @return array
	 */
	public function renderDefault()
	{
		return [
			'title' => 'Admin: default value!'
		];

	}
}