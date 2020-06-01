<?php

namespace Grelo\Core;

use Grelo\Exceptions\PrefixException;
use Grelo\Exceptions\RouteNotFoundException;

class ResourceRoute
{
    protected $slug;
    protected $controller;

    /**
     * @param $slug
     * @param string|null $controller
     * @param string|null $prefix
     * @throws RouteNotFoundException
     */
    static public function add($slug, $controller = null, $prefix = null)
    {

        $founded = false;

        if (!$controller) {
            $controller = ucfirst($slug);
        }

        if ($prefix !== null) {
            $prefixes = ['', 'ajax'];
        }

        if (is_string($prefix)) {
            $prefixes = [$prefix];
        } else if (is_array($prefix)) {
            $prefixes = $prefix;
        } else {
            throw new PrefixException();
        }


        foreach ($prefixes as $prefix) {

            $router = new Router($prefix);

            $router->get("/$slug", function () use ($controller, $prefix) {
                Loader::to("$controller#{$prefix}index");
            });

            $router->get("/$slug/create", function () use ($controller, $prefix) {
                Loader::to("$controller#{$prefix}create");
            });

            $router->post("/$slug", function () use ($controller, $prefix) {
                Loader::to("$controller#{$prefix}store");
            });

            $router->get("/$slug/([\w]+)*", function ($id) use ($controller, $prefix) {
                Loader::to("$controller#{$prefix}show", $id);
            });

            $router->get("/$slug/([\w]+)*/edit", function ($id) use ($controller, $prefix) {
                Loader::to("$controller#{$prefix}edit", $id);
            });

            $router->put("/$slug/([\w]+)*", function ($id) use ($controller, $prefix) {
                Loader::to("$controller#{$prefix}update", $id);
            });

            $router->delete("/$slug/([\w]+)*", function ($id) use ($controller, $prefix) {
                Loader::to("$controller#{$prefix}destroy", $id);
            });

            try {
                $router->route();
                $founded = true;
            } catch (RouteNotFoundException $e) {}
        }

        if (!$founded) {
            throw new RouteNotFoundException('Ruta no encontrada');
        }

    }
    
}
