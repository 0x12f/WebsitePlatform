<?php

namespace App\Application\Actions\Common\Catalog;

use DateTime;
use Slim\Http\Response;

class CartAction extends CatalogAction
{
    /**
     * @return Response
     * @throws \Doctrine\DBAL\DBALException
     * @throws \App\Domain\Exceptions\HttpBadRequestException
     */
    protected function action(): \Slim\Http\Response
    {
        if ($this->request->isPost()) {
            $data = [
                'delivery' => $this->request->getParam('delivery'),
                'list' => (array)$this->request->getParam('list', []),
                'phone' => $this->request->getParam('phone'),
                'email' => $this->request->getParam('email'),
                'comment' => $this->request->getParam('comment'),
                'shipping' => $this->request->getParam('shipping'),
            ];

            // пользователя заказа
            if (($user = $this->request->getAttribute('user', false)) && $user !== false) {
                $data['user_uuid'] = $user->uuid;
                $data['user'] = $user;
            }

            $check = \App\Domain\Filters\Catalog\Order::check($data);

            if ($check === true) {
                if ($this->isRecaptchaChecked()) {
                    $model = new \App\Domain\Entities\Catalog\Order($data);
                    $this->entityManager->persist($model);

                    // create notify
                    $notify = new \App\Domain\Entities\Notification([
                        'title' => 'Добавлен заказ: ' . $model->serial,
                        'message' => 'Поступил новый заказ, проверьте список заказов',
                        'date' => new DateTime(),
                    ]);
                    $this->entityManager->persist($notify);

                    // send push stream
                    $this->container->get('pushstream')->send([
                        'group' => \App\Domain\Types\UserLevelType::LEVEL_ADMIN,
                        'content' => $notify,
                    ]);

                    $this->entityManager->flush();

                    // if TM is enabled
                    if ($this->getParameter('integration_trademaster_enable', 'off') === 'on') {
                        // add task send to TradeMaster
                        $task = new \App\Domain\Tasks\TradeMaster\SendOrderTask($this->container);
                        $task->execute(['uuid' => $model->uuid]);
                        $this->entityManager->flush();

                        // run worker
                        \App\Domain\Tasks\Task::worker();
                    }

                    if (
                        (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') && !empty($_SERVER['HTTP_REFERER'])
                    ) {
                        $this->response = $this->response->withHeader('Location', '/cart/done/' . $model->uuid)->withStatus(301);
                    }

                    return $this->respondWithData(['redirect' => '/cart/done/' . $model->uuid]);
                } else {
                    $this->addError('grecaptcha', \App\Domain\References\Errors\Common::WRONG_GRECAPTCHA);
                }
            } else {
                $this->addErrorFromCheck($check);
            }
        }

        return $this->respondRender($this->getParameter('catalog_cart_template', 'catalog.cart.twig'));
    }
}