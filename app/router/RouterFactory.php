<?php

namespace App;

use Nette;
use Nette\Application\Routers\RouteList;
use Nette\Application\Routers\Route;


class RouterFactory
{

	/**
	 * @return Nette\Application\IRouter
	 */
	public static function createRouter()
	{
		$router = new RouteList();
		//$router[] = new Route('/login', 'Common:Login:default');
		//$router[] = new Route('/cats', 'Common:Cat:list');
		//$router[] = new Route('/colors', 'Color:list');
		//$router[] = new Route('/depozitums', 'Depozitum:list');
		$adminRoutes = new RouteList("Admin");
		$adminRoutes[] = new Route("admin[/<presenter>][/<action>][/<id>]", "Common:Homepage:default");

		$router[] = $adminRoutes;

		$router[] = new Route('[<presenter>][/<action>][/<id>]', 'Common:Homepage:default');
		//$router[] = new Route('admin/[<presenter>][/<action>][/<id>]', 'Common:Homepage:default');



		return $router;
	}

}
