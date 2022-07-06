<?php
declare(strict_types=1);

namespace Webapps;

use Cake\Core\BasePlugin;
use Cake\Routing\RouteBuilder;

/**
 * Plugin for Spa
 */
class Plugin extends BasePlugin
{
    /**
     * Plugin name.
     *
     * @var string
     */
    protected $name = 'Webapps';

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
        $routes->scope('/', function (RouteBuilder $builder){
            $builder->connect('/', ['controller' => 'Spa', 'action' => 'viewHomepage', 'plugin' => 'Webapps']);
            $builder->connect('/{controller}/{action}/*', ['controller' => 'Spa', 'action' => 'viewHomepage',  'plugin' => 'Webapps']);
            $builder->connect('/{controller}/{action}', ['controller' => 'Spa', 'action' => 'viewHomepage',  'plugin' => 'Webapps']);
            $builder->connect('/{controller}', ['controller' => 'Spa', 'action' => 'viewHomepage',  'plugin' => 'Webapps']);

            $builder->connect('/**', ['controller' => 'Spa', 'action' => 'viewHomepage',  'plugin' => 'Webapps']);
        });
        parent::routes($routes);
    }
}
