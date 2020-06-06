<?php declare(strict_types=1);

namespace App\Domain;

use App\Domain\Exceptions\HttpBadRequestException;
use Psr\Container\ContainerInterface;
use RuntimeException;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;

abstract class AbstractPlugin extends AbstractComponent
{
    public const NAME          = '';
    public const TITLE         = '';
    public const DESCRIPTION   = '';
    public const AUTHOR        = '';
    public const AUTHOR_EMAIL  = '';
    public const AUTHOR_SITE   = '';
    public const VERSION       = '1.0';

    /**
     * @var \Slim\Router
     */
    private $router;

    /**
     * @var Twig
     */
    private $renderer;

    /**
     * @var string
     */
    private $templateFolder;

    /**
     * @var array
     */
    private $handledRoutes = [];

    /**
     * @var array
     */
    private $settingsField = [];

    /**
     * @var array
     */
    private $toolbars = [];

    /**
     * @var bool
     */
    public $routes = false;

    /**
     * @var bool
     */
    public $navigation = false;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);

        if (empty(static::NAME) || empty(static::TITLE) || empty(static::AUTHOR)) {
            throw new RuntimeException('Plugin credentials have empty fields');
        }

        $this->container[static::NAME] = $this;
        $this->router = $this->container->get('router');
        $this->renderer = $container->get('view');
    }

    public function getCredentials($field = null)
    {
        $credentials = [
            'title' => static::TITLE,
            'description' => static::DESCRIPTION,
            'author' => static::AUTHOR,
            'author_email' => static::AUTHOR_EMAIL,
            'author_site' => static::AUTHOR_SITE,
            'name' => static::NAME,
            'version' => static::VERSION,
        ];

        if (in_array($field, array_keys($credentials), true)) {
            return $credentials[$field];
        }

        return $credentials;
    }

    protected function setTemplateFolder($path): void
    {
        if (realpath($path) !== false) {
            $this->renderer->getLoader()->addPath($path);
        }
    }

    public function getTemplateFolder()
    {
        return $this->templateFolder;
    }

    protected function setHandledRoute(...$name): void
    {
        $this->routes = true;
        $this->handledRoutes = array_merge($this->handledRoutes, $name);
    }

    public function getRoute()
    {
        return $this->handledRoutes;
    }

    protected function addTwigExtension($extension): void
    {
        $this->renderer->addExtension(new $extension($this->container, $this));
    }

    protected function addSettingsField($params = []): void
    {
        $default = [
            'label' => '',
            'description' => '',
            'type' => 'text',
            'name' => '',
            'args' => [
                'disabled' => false,
                'readonly' => false,
                'value' => null,
                'placeholder' => '',
                'options' => [],
                'selected' => null,
                'checked' => null,
            ],
            'message' => '',
            'prefix' => '',
            'postfix' => '',
        ];
        $params = array_merge($default, $params);
        $params['name'] = static::NAME . '[' . $params['name'] . ']';

        $this->settingsField[$params['name']] = $params;
    }

    public function getSettingsFields()
    {
        return $this->settingsField;
    }

    protected function addToolbarItem($params = []): void
    {
        $default = [
            'twig' => '',
            'html' => '',
        ];
        $params = array_merge($default, $params);

        $this->toolbars[] = $params;
    }

    public function getToolbarItem()
    {
        return $this->toolbars;
    }

    protected function enableNavigationItem($params = [])
    {
        $default = [
            'handler' => function (Request $req, Response $res) {
                return $res->withHeader('Content-Type', 'text/plain')->write(
                    'This is empty route for plugin: ' . static::NAME . PHP_EOL .
                    'Change "handler" key in function arguments enableNavigationItem(["handler" => ??]).'
                );
            },
        ];
        $params = array_merge($default, $params);

        $this->navigation = true;

        return $this->router
            ->map(['get', 'post'], '/cup/plugin/' . static::NAME, $params['handler'])
            ->add(new \App\Application\Middlewares\CupMiddleware($this->container));
    }

    public function isNavigationItemEnabled()
    {
        return $this->navigation;
    }

    /**
     * @param array $params
     *
     * @return \Slim\Interfaces\RouteInterface|\Slim\Route
     */
    protected function map($params)
    {
        $default = [
            'methods' => ['get', 'post'],
            'pattern' => '',
            'handler' => function (Request $req, Response $res) {
                return $res->withHeader('Content-Type', 'text/plain')->write(
                    'This is empty route for plugin: ' . static::NAME . PHP_EOL .
                    'Change "handler" key in function arguments map(["methods" => "..", "pattern" => "..", "handler" => ??]).'
                );
            },
        ];
        $params = array_merge($default, $params);

        if (!is_array($params['methods'])) {
            $params['methods'] = [$params['methods']];
        }

        return $this->router->map($params['methods'], $params['pattern'], $params['handler']);
    }

    /**
     * Функция выполнится ДО обработки выбранной группы роутов
     *
     * @param Request  $request
     * @param Response $response
     * @param string   $routeName
     *
     * @return Response
     */
    public function before(Request $request, Response $response, string $routeName): Response
    {
        return $response;
    }

    /**
     * Функция выполнится ПОСЛЕ обработки выбранной группы роутов
     *
     * @param Request  $request
     * @param Response $response
     * @param string   $routeName
     *
     * @return Response
     */
    public function after(Request $request, Response $response, string $routeName): Response
    {
        return $response;
    }

    /**
     * @param string $template
     * @param array  $data
     *
     * @throws HttpBadRequestException
     * @throws \RunTracy\Helpers\Profiler\Exception\ProfilerException
     *
     * @return string
     */
    protected function render($template, array $data = [])
    {
        try {
            \RunTracy\Helpers\Profiler\Profiler::start('plugin render (%s)', $template);
            $rendered = $this->renderer->fetch($template, $data);
            \RunTracy\Helpers\Profiler\Profiler::finish('plugin render (%s)', $template);

            return $rendered;
        } catch (\Twig\Error\LoaderError $exception) {
            throw new RuntimeException($exception->getMessage());
        }
    }
}
