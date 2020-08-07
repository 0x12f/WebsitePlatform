<?php declare(strict_types=1);

namespace App\Application\Actions\Cup\GuestBook;

use App\Domain\Service\GuestBook\GuestBookService;

class GuestBookListAction extends GuestBookAction
{
    protected function action(): \Slim\Http\Response
    {
        $list = $this->guestBookService->read();

        return $this->respondWithTemplate('cup/guestbook/index.twig', ['list' => $list]);
    }
}
