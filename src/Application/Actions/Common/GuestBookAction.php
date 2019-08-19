<?php

namespace Application\Actions\Common;

use Application\Actions\Action;
use Psr\Container\ContainerInterface;

class GuestBookAction extends Action
{
    /**
     * @var \Doctrine\Common\Persistence\ObjectRepository|\Doctrine\ORM\EntityRepository
     */
    protected $gbookRepository;

    /**
     * @inheritDoc
     */
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);

        $this->gbookRepository = $this->entityManager->getRepository(\Domain\Entities\GuestBook::class);
    }

    protected function action(): \Slim\Http\Response
    {
        if ($this->request->isPost()) {
            $data = [
                'name' => $this->request->getParam('name'),
                'email' => $this->request->getParam('email'),
                'message' => $this->request->getParam('message'),
            ];

            $check = \Domain\Filters\GuestBook::check($data);

            if ($check === true) {
                $model = new \Domain\Entities\GuestBook($data);
                $model->status = \Domain\Types\GuestBookStatusType::STATUS_MODERATE;

                $this->entityManager->persist($model);
                $this->entityManager->flush();

                if (
                    (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH'] != 'xmlhttprequest') && !empty($_SERVER['HTTP_REFERER'])
                ) {
                    $this->response = $this->response->withHeader('Location', $_SERVER['HTTP_REFERER']);
                }

                return $this->respondWithData(['description' => 'Message added']);
            }
        }

        // get list of comments and obfuscate email address
        $list = collect(
            $this->gbookRepository->findBy(
                ['status' => \Domain\Types\GuestBookStatusType::STATUS_WORK],
                [],
                $this->getParameter('guestbook_pagination', 10),
                (int)($this->args['page'] ?? 0)
            )
        )->map(
            function ($el) {
                if ($el->email) {
                    $em = explode('@', $el->email);
                    $name = implode(array_slice($em, 0, count($em) - 1), '@');
                    $len = floor(strlen($name) / 2);

                    $el->email = substr($name, 0, $len) . str_repeat('*', $len) . '@' . end($em);
                }

                return $el;
            }
        );

        return $this->respondRender($this->getParameter('guestbook_template', 'guestbook.twig'), ['messages' => $list]);
    }
}