<?php


namespace App\Controller\Main;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class BaseController
 *
 * @package App\Controller\Main
 */
class BaseController extends AbstractController
{
	/**
	 * @return array
	 */
	public function renderDefault()
	{
		return [
			'title' => 'Home:'
		];
		
	}
}