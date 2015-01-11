<?php
/*
 * WellCommerce Open-Source E-Commerce Platform
 *
 * This file is part of the WellCommerce package.
 *
 * (c) Adam Piotrowski <adam@wellcommerce.org>
 *
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 */

namespace WellCommerce\Bundle\CoreBundle\Form\Resolver;

use WellCommerce\Bundle\CoreBundle\Collection\AbstractCollection;

/**
 * Class AbstractResolver
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
abstract class AbstractResolver extends AbstractCollection
{
    /**
     * Adds new service to collection
     *
     * @param string $alias
     * @param string $serviceId
     */
    public function add($alias, $serviceId)
    {
        $this->items[$alias] = $serviceId;
    }
}
