<?php declare(strict_types=1);

namespace App\Domain\Service\Form;

use App\Domain\AbstractService;
use App\Domain\Entities\Form\Data as FromData;
use App\Domain\Repository\Form\DataRepository as FormDataRepository;
use App\Domain\Service\Form\Exception\FormDataNotFoundException;
use App\Domain\Service\Form\Exception\MissingMessageValueException;
use Ramsey\Uuid\Uuid;
use Tightenco\Collect\Support\Collection;

class DataService extends AbstractService
{
    /**
     * @var FormDataRepository
     */
    protected $service;

    protected function init(): void
    {
        $this->service = $this->entityManager->getRepository(FromData::class);
    }

    /**
     * @param array $data
     *
     * @throws MissingMessageValueException
     *
     * @return FromData
     */
    public function create(array $data = []): FromData
    {
        $default = [
            'form_uuid' => Uuid::NIL,
            'message' => '',
            'date' => 'now',
        ];
        $data = array_merge($default, $data);

        if (!$data['message']) {
            throw new MissingMessageValueException();
        }

        $form = (new FromData)
            ->setFormUuid($data['form_uuid'])
            ->setMessage($data['message'])
            ->setDate($data['date']);

        $this->entityManager->persist($form);
        $this->entityManager->flush();

        return $form;
    }

    /**
     * @param array $data
     *
     * @throws FormDataNotFoundException
     *
     * @return Collection|FromData
     */
    public function read(array $data = [])
    {
        $default = [
            'uuid' => null,
            'form_uuid' => null,
        ];
        $data = array_merge($default, static::$default_read, $data);

        $criteria = [];

        if ($data['uuid'] !== null) {
            $criteria['uuid'] = $data['uuid'];
        }
        if ($data['form_uuid'] !== null) {
            $criteria['form_uuid'] = $data['form_uuid'];
        }

        switch (true) {
            case !is_array($data['uuid']) && $data['uuid'] !== null:
            case !is_array($data['title']) && $data['title'] !== null:
            case !is_array($data['address']) && $data['address'] !== null:
                $formData = $this->service->findOneBy($criteria);

                if (empty($formData)) {
                    throw new FormDataNotFoundException();
                }

                return $formData;

            default:
                return collect($this->service->findBy($criteria, $data['order'], $data['limit'], $data['offset']));
        }
    }

    /**
     * @param FromData|string|Uuid $entity
     * @param array                $data
     *
     * @throws FormDataNotFoundException
     *
     * @return FromData
     */
    public function update($entity, array $data = []): FromData
    {
        switch (true) {
            case is_string($entity) && Uuid::isValid($entity):
            case is_object($entity) && is_a($entity, Uuid::class):
                $entity = $this->service->findOneByUuid((string) $entity);

                break;
        }

        if (is_object($entity) && is_a($entity, FromData::class)) {
            $default = [
                'form_uuid' => null,
                'message' => null,
                'date' => null,
            ];
            $data = array_merge($default, $data);

            if ($data !== $default) {
                if ($data['form_uuid'] !== null) {
                    $entity->setFormUuid($data['form_uuid']);
                }
                if ($data['message'] !== null) {
                    $entity->setMessage($data['message']);
                }
                if ($data['date'] !== null) {
                    $entity->setDate($data['date']);
                }

                $this->entityManager->flush();
            }

            return $entity;
        }

        throw new FormDataNotFoundException();
    }

    /**
     * @param FromData|string|Uuid $entity
     *
     * @throws FormDataNotFoundException
     *
     * @return bool
     */
    public function delete($entity): bool
    {
        switch (true) {
            case is_string($entity) && Uuid::isValid($entity):
            case is_object($entity) && is_a($entity, Uuid::class):
                $entity = $this->service->findOneByUuid((string) $entity);

                break;
        }

        if (is_object($entity) && is_a($entity, FromData::class)) {
            $this->entityManager->remove($entity);
            $this->entityManager->flush();

            return true;
        }

        throw new FormDataNotFoundException();
    }
}
