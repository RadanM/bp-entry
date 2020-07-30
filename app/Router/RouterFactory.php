<?php

declare(strict_types=1);

namespace App\Router;

use Nette\Application\Routers\{Route, RouteList};
use Nette\StaticClass;

final class RouterFactory
{
	use StaticClass;

	public static function createRouter(): RouteList
	{
        $router = new RouteList();

        $router[] = $admin = new RouteList('Admin');
        $admin[] = new Route('admin/<presenter>/<action>', 'Admin:default');

        $router[] = $web = new RouteList('Web');
        $web[] = new Route('<presenter>/<action>', 'Homepage:default');

        return $router;
	}
}
