<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Customer Entity
 *
 * @property int $id
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property int|null $created_by
 * @property int|null $modified_by
 * @property \Cake\I18n\FrozenTime|null $deleted_at
 * @property int $version
 * @property string|null $uuid
 * @property string|null $firstname
 * @property string|null $lastname
 * @property string|null $email
 * @property string|null $phone
 * @property int|null $address_id
 * @property string|null $lov_customer_status
 *
 * @property \App\Model\Entity\Address $address
 * @property \App\Model\Entity\Rental[] $rentals
 */
class Customer extends Entity
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
        'created' => true,
        'modified' => true,
        'created_by' => true,
        'modified_by' => true,
        'deleted_at' => true,
        'version' => true,
        'uuid' => true,
        'firstname' => true,
        'lastname' => true,
        'email' => true,
        'phone' => true,
        'address_id' => true,
        'lov_customer_status' => true,
        'address' => true,
        'rentals' => true,
    ];
}
