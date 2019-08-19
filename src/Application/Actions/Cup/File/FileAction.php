<?php

namespace Application\Actions\Cup\File;

use Application\Actions\Action;
use DateTime;
use Exception;
use Psr\Container\ContainerInterface;

abstract class FileAction extends Action
{
    /**
     * @var \Doctrine\Common\Persistence\ObjectRepository|\Doctrine\ORM\EntityRepository
     */
    protected $fileRepository;

    /**
     * @inheritDoc
     */
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);

        $this->fileRepository = $this->entityManager->getRepository(\Domain\Entities\File::class);
    }
}