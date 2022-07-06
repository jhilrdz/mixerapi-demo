<?php
declare(strict_types=1);

namespace App\Model\Entity;

use App\Services\HyperMedia;
use Cake\Datasource\EntityInterface;
use Cake\ORM\Entity;
use MixerApi\HalView\HalResourceInterface;
use MixerApi\JsonLdView\JsonLdDataInterface;
use MixerApi\JsonLdView\JsonLdSchema;

/**
 * Actor Entity
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
 *
 * @property \App\Model\Entity\FilmActor[] $film_actors
 */
class Actor extends Entity implements HalResourceInterface, JsonLdDataInterface
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
 */
    protected $_accessible = [
        '*' => false,
    ];

    protected $_hidden = [
        '_joinData',
    ];

    public function _getFirstname(?string $v)
    {
        return h($v);
    }

    public function _getLastname(?string $v)
    {
        return h($v);
    }

    /**
     * @inheritDoc
     */
    public function getHalLinks(EntityInterface $entity): array
    {
        return [
            'self' => [
                'href' => (new HyperMedia())->getHref('/%s/actors/%s', $entity)
            ],
        ];
    }

    /**
     * @inheritDoc
     */
    public function getJsonLdContext(): string
    {
        return '/public/contexts/actor';
    }

    /**
     * @inheritDoc
     */
    public function getJsonLdType(): string
{
        return 'https://schema.org/actor';
    }

    /**
     * @inheritDoc
     */
    public function getJsonLdIdentifier(EntityInterface $entity): string
    {
        return (new HyperMedia())->getHref('/%s/actors/%s', $entity);
    }

    /**
     * @inheritDoc
     */
    public function getJsonLdSchemas(): array
    {
        return [
            new JsonLdSchema('first_name', 'https://schema.org/giveName'),
            new JsonLdSchema('last_name', 'https://schema.org/familyName'),
        ];
    }
}
