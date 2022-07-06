<?php
declare(strict_types=1);

namespace PublicApi;

use Cake\Core\BasePlugin;
use Cake\Core\ContainerInterface;
use Cake\Core\PluginApplicationInterface;
use Cake\Http\MiddlewareQueue;
use Cake\Routing\RouteBuilder;
use MixerApi\Rest\Lib\AutoRouter;
use MixerApi\Rest\Lib\Route\ResourceScanner;
use Psr\Http\Message\ServerRequestInterface;


/**
 * Plugin for PublicApi
 */
class Plugin extends BasePlugin
{
    /**
     * Plugin name.
     *
     * @var string
     */
    protected $name = 'PublicApi';

    /**
     * Do bootstrapping or not
     *
     * @var bool
     */
    protected $bootstrapEnabled = false;

    /**
     * Console middleware
     *
     * @var bool
     */
    protected $consoleEnabled = false;

    /**
     * Enable middleware
     *
     * @var bool
     */
    protected $middlewareEnabled = false;

    /**
     * Register container services
     *
     * @var bool
     */
    protected $servicesEnabled = false;

    /**
     * Add routes for the plugin.
     *
     * If your plugin has many routes and you would like to isolate them into a separate file,
     * you can create `$plugin/config/routes.php` and delete this method.
     *
     * @param \Cake\Routing\RouteBuilder $routes The route builder to update.
     * @return void
     */
    public function routes(RouteBuilder $routes): void {
        $routes->plugin('PublicApi', ['path' => '/api/public'], function (RouteBuilder $builder) {
            $builder->setExtensions(['json', 'xml']);
            $builder->connect('/', [
                'plugin' => 'PublicApi', 'controller' => 'Swagger', 'action' => 'index'
            ]);
            (new AutoRouter($builder))->buildResources();
            $builder->resources('FilmReports', [
                'only' => ['by-rating', 'by-category'],
                'map'  => [
                    'by-rating'   => [
                        'method' => 'GET',
                        'action' => 'byRating',
                    ],
                    'by-category' => [
                        'method' => 'GET',
                        'action' => 'byCategory',
                    ]
                ]
            ]);
            $builder->resources('Actors', [
                'only' => ['view', 'index', 'view-films'],
                'map'  => [
                    'view-films' => [
                        'method' => 'get',
                        'action' => 'viewFilms',
                        'path'   => ':id/films'
                    ]
                ]
            ]);
            $builder->resources('Films', [
                'only' => ['view', 'index', 'view-actors'],
                'map'  => [
                    'view-actors' => [
                        'method' => 'get',
                        'action' => 'viewActors',
                        'path'   => ':id/actors'
                    ]
                ]
            ]);

            $builder->fallbacks();
        });

        $routes->connect('/api/public/contexts/*', [
            'plugin' => 'MixerApi/JsonLdView', 'controller' => 'JsonLd', 'action' => 'contexts'
        ]);
        $routes->connect('/api/public/vocab', [
            'plugin' => 'MixerApi/JsonLdView', 'controller' => 'JsonLd', 'action' => 'vocab'
        ]);

        parent::routes($routes);
    }

}
