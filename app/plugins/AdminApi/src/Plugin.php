<?php
declare(strict_types=1);

namespace AdminApi;

use Authentication\AuthenticationServiceInterface;
use Authentication\AuthenticationServiceProviderInterface;
use Authentication\Middleware\AuthenticationMiddleware;
use AuthenticationApi\JwtAuthService;
use Cake\Core\BasePlugin;
use Cake\Http\MiddlewareQueue;
use Cake\Routing\RouteBuilder;
use MixerApi\Rest\Lib\AutoRouter;
use MixerApi\Rest\Lib\Route\ResourceScanner;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Plugin for AdminApi
 */
class Plugin extends BasePlugin implements AuthenticationServiceProviderInterface
{
    /**
     * Plugin name.
     *
     * @var string
     */
    protected $name = 'AdminApi';

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
     * Register container services
     *
     * @var bool
     */
    protected $servicesEnabled = false;

    /**
     * @inheritDoc
     */
    public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
    {
        $middlewareQueue
            // Add the AuthenticationMiddleware. It should be
            // after routing and body parser.
            ->add(new AuthenticationMiddleware($this));

        // Cross Site Request Forgery (CSRF) Protection Middleware
        // https://book.cakephp.org/4/en/controllers/middleware.html#cross-site-request-forgery-csrf-middleware
        //->add(new CsrfProtectionMiddleware([
        //    'httponly' => true,
        //]));

        return $middlewareQueue;
    }

    /**
     * @inheritDoc
     */
    public function routes(RouteBuilder $routes): void
    {
        $routes->plugin('AdminApi', ['path' => '/api/admin'], function (RouteBuilder $builder) {
            $builder->setExtensions(['json','xml']);
            (new AutoRouter($builder, new ResourceScanner('AdminApi\Controller')))->buildResources();
            $builder->connect('/', [
                'plugin' => 'AdminApi', 'controller' => 'Swagger', 'action' => 'index'
            ]);
            $builder->fallbacks();
        });

        $routes->connect('/api/admin/contexts/*', [
            'plugin' => 'MixerApi/JsonLdView', 'controller' => 'JsonLd', 'action' => 'contexts'
        ]);
        $routes->connect('/api/admin/vocab', [
            'plugin' => 'MixerApi/JsonLdView', 'controller' => 'JsonLd', 'action' => 'vocab'
        ]);

        parent::routes($routes);
    }

    /**
     * @inheritDoc
     * @throws \MixerApi\JwtAuth\Exception\JwtAuthException
     */
    public function getAuthenticationService(ServerRequestInterface $request): AuthenticationServiceInterface
    {
        return JwtAuthService::create();
    }
}
