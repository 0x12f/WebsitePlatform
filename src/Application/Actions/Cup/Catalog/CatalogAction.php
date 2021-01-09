<?php declare(strict_types=1);

namespace App\Application\Actions\Cup\Catalog;

use App\Domain\AbstractAction;
use App\Domain\Entities\Catalog\Product;
use App\Domain\Entities\Catalog\ProductAttribute;
use App\Domain\Service\Catalog\AttributeService as CatalogAttributeService;
use App\Domain\Service\Catalog\CategoryService as CatalogCatalogService;
use App\Domain\Service\Catalog\Exception\AttributeNotFoundException;
use App\Domain\Service\Catalog\OrderService as CatalogOrderService;
use App\Domain\Service\Catalog\ProductService as CatalogProductService;
use App\Domain\Service\Notification\NotificationService;
use App\Domain\Service\User\UserService;
use Illuminate\Support\Collection;
use Psr\Container\ContainerInterface;

abstract class CatalogAction extends AbstractAction
{
    /**
     * @var UserService
     */
    protected UserService $userService;

    /**
     * @var CatalogCatalogService
     */
    protected CatalogCatalogService $catalogCategoryService;

    /**
     * @var CatalogProductService
     */
    protected CatalogProductService $catalogProductService;

    /**
     * @var CatalogAttributeService
     */
    protected CatalogAttributeService $catalogAttributeService;

    /**
     * @var CatalogOrderService
     */
    protected CatalogOrderService $catalogOrderService;

    /**
     * @var NotificationService
     */
    protected NotificationService $notificationService;

    /**
     * {@inheritdoc}
     */
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);

        $this->userService = UserService::getWithContainer($container);
        $this->catalogCategoryService = CatalogCatalogService::getWithContainer($container);
        $this->catalogProductService = CatalogProductService::getWithContainer($container);
        $this->catalogAttributeService = CatalogAttributeService::getWithContainer($container);
        $this->catalogOrderService = CatalogOrderService::getWithContainer($container);
        $this->notificationService = NotificationService::getWithContainer($container);
    }

    /**
     * @param array   $attributes
     * @param Product $product
     *
     * @throws AttributeNotFoundException
     * @return Product
     */
    protected function processProductAttributes(array $attributes, Product $product)
    {
        foreach ($product->getAttributes() as $attribute) {
            $this->entityManager->remove($attribute);
        }

        foreach ($attributes as $uuid => $value) {
            if ($value) {
                $attribute = (new ProductAttribute())
                    ->setProduct($product)
                    ->setAttribute($this->catalogAttributeService->read(['uuid' => $uuid]))
                    ->setValue($value);

                $this->entityManager->persist($attribute);
            }
        }

        return $product;
    }

    /**
     * @param bool $list
     * if false return key:value
     * if true return key:list
     *
     * @return Collection
     */
    protected function getMeasure($list = false)
    {
        $measure = $this->parameter('catalog_measure');
        $result = [];

        if ($measure) {
            preg_match_all('/([\w\d]+)\:\s?([\w\d]+)\;\s?([\w\d]+)\;\s?([\w\d]+)(?>\s|$)/u', $measure, $matches);

            foreach ($matches[1] as $index => $key) {
                $result[$key] = $list ? [$matches[2][$index], $matches[3][$index], $matches[4][$index]] : $matches[2][$index];
            }
        }

        return collect($result);
    }
}
