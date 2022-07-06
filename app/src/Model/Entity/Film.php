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
 * Film Entity
 *
 * @property int $id
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property int|null $created_by
 * @property int|null $modified_by
 * @property \Cake\I18n\FrozenTime|null $deleted_at
 * @property int $version
 * @property string|null $uuid
 * @property int $language_id
 * @property string|null $title
 * @property string|null $description
 * @property int|null $release_year
 * @property int $rental_duration
 * @property string $rental_rate
 * @property int|null $length
 * @property string $replacement_cost
 * @property string|null $lov_film_rating
 * @property string|null $special_features
 * @property string|null $lov_film_status
 *
 * @property \App\Model\Entity\Language $language
 * @property \App\Model\Entity\FilmActor[] $film_actors
 * @property \App\Model\Entity\FilmCategory[] $film_categories
 * @property \App\Model\Entity\FilmText[] $film_texts
 * @property \App\Model\Entity\Actor[] $actors
 * @property \App\Model\Entity\Category[] $categories
 */
class Film extends Entity implements HalResourceInterface, JsonLdDataInterface
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

    public function _getTitle(?string $v)
    {
        return h($v);
    }

    public function _getDescription(?string $v)
    {
        return h($v);
    }

    public function _getSpecialFeatures(?string $v)
    {
        return h($v);
    }

    protected $_hidden = [
        '_joinData',
        '_matchingData'
    ];

    /**
     * @inheritDoc
     */
    public function getHalLinks(EntityInterface $entity): array
    {
        return [
            'self' => [
                'href' => (new HyperMedia())->getHref('/%s/films/%s', $entity),
            ],
        ];
    }

    /**
     * @inheritDoc
     */
    public function getJsonLdContext(): string
    {
        return '/public/contexts/Film';
    }

    /**
     * @inheritDoc
     */
    public function getJsonLdType(): string
    {
        return 'https://schema.org/Movie';
    }

    /**
     * @inheritDoc
     */
    public function getJsonLdIdentifier(EntityInterface $entity): string
    {
        return (new HyperMedia())->getHref('/%s/films/%s', $entity);
    }

    /**
     * @inheritDoc
     */
    public function getJsonLdSchemas(): array
    {
        return [
            new JsonLdSchema('title', 'https://schema.org/name', 'The title of the movie'),
            new JsonLdSchema('description', 'https://schema.org/about'),
            new JsonLdSchema('length', 'https://schema.org/duration'),
            new JsonLdSchema('rating', 'https://schema.org/contentRating'),
            new JsonLdSchema('release_year', 'https://schema.org/copyrightYear'),
        ];
    }
}
