<?php
declare(strict_types=1);

namespace AuthenticationApi;

use Authentication\AuthenticationService;
use Authentication\Middleware\AuthenticationMiddleware;
use AuthenticationApi\Service\UserAuthenticationService;
use Cake\Core\BasePlugin;
use Cake\Core\ContainerInterface;
use Cake\Core\PluginApplicationInterface;
use Cake\Http\Middleware\BodyParserMiddleware;
use Cake\Http\MiddlewareQueue;
use Cake\Routing\RouteBuilder;
use Cake\Console\CommandCollection;

/**
 * Plugin for AuthenticationApi
 */
class Plugin extends BasePlugin
{
    /**
     * @inheritDoc
     */
    public function bootstrap(PluginApplicationInterface $app): void
    {
        parent::bootstrap($app);
    }

    /**
     * @inheritDoc
     */
    public function routes(RouteBuilder $routes): void
    {
        $routes->plugin('AuthenticationApi', ['path' => '/admin/auth'], function (RouteBuilder $builder) {
            $authService = (new UserAuthenticationService())->getService(new AuthenticationService());
            $authMiddleware = new AuthenticationMiddleware($authService);

            $builder->registerMiddleware('body', new BodyParserMiddleware());
            $builder->registerMiddleware('auth', $authMiddleware);
            $builder->applyMiddleware('body','auth');
            $builder->setExtensions(['json','xml']);
            $builder->connect('/', [
                'plugin' => 'AuthenticationApi', 'controller' => 'Swagger', 'action' => 'index'
            ]);
            $builder->resources('Login', [
                'path' => '/login',
                'only' => ['login'],
                'map' => [
                    'login' => [
                        'method' => 'post',
                        'path' => null,
                        'action' => 'login'
                    ]
                ]
            ]);
            $builder->fallbacks();
        });

        $routes->connect('/admin/auth/contexts/*', [
            'plugin' => 'MixerApi/JsonLdView', 'controller' => 'JsonLd', 'action' => 'contexts'
        ]);
        $routes->connect('/admin/auth/vocab', [
            'plugin' => 'MixerApi/JsonLdView', 'controller' => 'JsonLd', 'action' => 'vocab'
        ]);

        parent::routes($routes);
    }

    /**
     * @inheritDoc
     */
    public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
    {
        return $middlewareQueue;
    }

    /**
     * @inheritDoc
     */
    public function console(CommandCollection $commands) : CommandCollection
    {
        // Add your commands here

        $commands = parent::console($commands);

        return $commands;
    }

    /**
     * @inheritDoc
     */
    public function services(ContainerInterface $container): void
    {
        $container->add(UserAuthenticationService::class);
    }
}
