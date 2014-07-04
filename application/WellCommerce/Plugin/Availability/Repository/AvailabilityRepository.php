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
namespace WellCommerce\Plugin\Availability\Repository;

use WellCommerce\Core\Component\Repository\AbstractRepository;
use WellCommerce\Plugin\Availability\Model\Availability;
use WellCommerce\Plugin\Availability\Model\AvailabilityTranslation;

/**
 * Class AvailabilityRepository
 *
 * @package WellCommerce\Plugin\Availability\Repository
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class AvailabilityRepository extends AbstractRepository implements AvailabilityRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function all()
    {
        return Availability::with('translation')->get();
    }

    /**
     * {@inheritdoc}
     */
    public function find($id)
    {
        return Availability::with('translation')->find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function delete($id)
    {
        $availability = $this->find($id);
        $availability->delete();
        $this->dispatchEvent(AvailabilityRepositoryInterface::POST_DELETE_EVENT, $availability);
    }

    /**
     * {@inheritdoc}
     */
    public function save(array $data, $id = null)
    {
        $this->transaction(function () use ($data, $id) {

            $availability = Availability::firstOrCreate([
                'id' => $id
            ]);

            $data = $this->dispatchEvent(AvailabilityRepositoryInterface::PRE_SAVE_EVENT, $availability, $data);

            $availability->update($data);

            foreach ($this->getLanguageIds() as $language) {

                $translation = AvailabilityTranslation::firstOrCreate([
                    'availability_id' => $availability->id,
                    'language_id'     => $language
                ]);

                $translationData = $translation->getTranslation($data, $language);
                $translation->update($translationData);
            }

            $this->dispatchEvent(AvailabilityRepositoryInterface::POST_SAVE_EVENT, $availability, $data);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function getAllAvailabilityToSelect()
    {
        return $this->all()->toSelect('id', 'translation.name', $this->getCurrentLanguage());
    }
}
