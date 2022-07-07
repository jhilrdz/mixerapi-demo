<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;
use MixerApi\JwtAuth\Jwt\Jwt;
use MixerApi\JwtAuth\Jwt\JwtEntityInterface;
use MixerApi\JwtAuth\Jwt\JwtInterface;


/**
 * User Entity
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
 * @property string $email
 * @property string $password
 * @property bool $active_flg
 * @property bool $blocked_flg
 * @property string|null $avatar
 * @property string|null $refresh_token
 * @property \Cake\I18n\FrozenTime|null $refresh_token_expire
 */
class User extends Entity implements JwtEntityInterface
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

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array<string>
     */
    protected $_hidden = [
        'password',
    ];

    /**
     * @inheritDoc
     */
    public function getJwt(): JwtInterface {
        return new Jwt(
            exp: time() + 60 * 60 * 24,
            sub: strval($this->get('id')),
            iss: 'demo.mixerapi.com',
            aud: null,
            nbf: null,
            iat: null,
            jti: \Cake\Utility\Text::uuid(),
            claims: [
                'user' => [
                    'email' => $this->get('email')
                ]
            ]
        );
    }
}
