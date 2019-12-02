<?php

namespace App\Domain\Filters\Catalog;

use Alksily\Validator\Filter;
use Alksily\Validator\Traits\FilterRules;
use App\Domain\Filters\Traits\CatalogFilterRules;
use App\Domain\Filters\Traits\CommonFilterRules;

class Category extends Filter
{
    use FilterRules;
    use CommonFilterRules;
    use CatalogFilterRules;

    /**
     * Check page model data
     *
     * @param array $data
     *
     * @return array|bool
     */
    public static function check(array &$data)
    {
        $filter = new self($data);

        $filter
            ->addGlobalRule($filter->leadTrim())
            ->attr('parent')
                ->addRule($filter->leadStr())
                ->addRule($filter->checkStrlenBetween(0, 36))
                ->addRule($filter->CheckUUID())
            ->attr('title')
                ->addRule($filter->leadStr())
                ->addRule($filter->checkStrlenBetween(0, 255))
            ->attr('description')
                ->addRule($filter->leadStr())
                ->addRule($filter->checkStrlenBetween(0, 10000))
            ->attr('address')
                ->addRule($filter->ValidAddress())
                ->addRule($filter->InsertParentCategoryAddress())
                ->addRule($filter->UniqueCategoryAddress())
                ->addRule($filter->checkStrlenBetween(0, 255))
            ->attr('field1')
                ->addRule($filter->leadStr())
                ->addRule($filter->leadTrim())
            ->attr('field2')
                ->addRule($filter->leadStr())
                ->addRule($filter->leadTrim())
            ->attr('field3')
                ->addRule($filter->leadStr())
                ->addRule($filter->leadTrim())
            ->attr('product')
                ->addRule($filter->ValidProductFieldNames())
            ->attr('pagination')
                ->addRule($filter->leadInteger())
            ->attr('children')
                ->addRule($filter->leadBoolean())
            ->attr('order')
                ->addRule($filter->leadInteger())
                ->addRule($filter->leadMin(1))
            ->attr('meta')
                ->addRule($filter->ValidMeta())
            ->attr('template')
                ->addRule($filter->ValidTemplate())
            ->attr('external_id')
                ->addRule($filter->leadStr());

        return $filter->run();
    }
}
