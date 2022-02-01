<?php declare(strict_types=1);

namespace App\Application\Middlewares;

use App\Domain\AbstractMiddleware;
use App\Domain\Service\User\Exception\UserNotFoundException;
use App\Domain\Service\User\UserService;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Request;

class AuthorizationMiddleware extends AbstractMiddleware
{
    /**
     * @throws \Exception
     */
    public function __invoke(Request $request, RequestHandlerInterface $handler): \Slim\Psr7\Response
    {
        $data = [
            'uuid' => $request->getCookieParams()['uuid'] ?? null,
            'session' => $request->getCookieParams()['session'] ?? null,
        ];

        if ($data['uuid'] && \Ramsey\Uuid\Uuid::isValid((string) $data['uuid']) && $data['session']) {
            try {
                $userService = $this->container->get(UserService::class);
                $user = $userService->read([
                    'uuid' => $data['uuid'],
                    'status' => \App\Domain\Types\UserStatusType::STATUS_WORK,
                ]);

                if ($user && $user->getSession()) {
                    $hash = $user->getSession()->getHash();

                    if ($data['session'] === $hash) {
                        $request = $request->withAttribute('user', $user);
                    }
                } else {
                    throw new \RuntimeException();
                }
            } catch (\RuntimeException|UserNotFoundException $e) {
                // clear cookie
                setcookie('uuid', '-1', time(), '/');
                setcookie('session', '-1', time(), '/');
            }
        }

        return $handler->handle($request);
    }
}
