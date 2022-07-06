<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Rental Entity
 *
 * @property int $id
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property int|null $created_by
 * @property int|null $modified_by
 * @property \Cake\I18n\FrozenTime|null $deleted_at
 * @property int $version
 * @property string|null $uuid
 * @property int $film_id
 * @property int $customer_id
 * @property \Cake\I18n\FrozenTime $rental_date
 * @property \Cake\I18n\FrozenTime|null $return_date
 * @property string|null $lov_rental_status
 *
 * @property \App\Model\Entity\Film $film
 * @property \App\Model\Entity\Customer $customer
 */
class Rental extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected $_accessible = [
        '*' => false
    ];
}
