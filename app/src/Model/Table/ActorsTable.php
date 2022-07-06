<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\Http\ServerRequest;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Actors Model
 *
 * @property \App\Model\Table\FilmActorsTable&\Cake\ORM\Association\HasMany $FilmActors
 *
 * @method \App\Model\Entity\Actor newEmptyEntity()
 * @method \App\Model\Entity\Actor newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Actor[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Actor get($primaryKey, $options = [])
 * @method \App\Model\Entity\Actor findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Actor patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Actor[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Actor|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Actor saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Actor[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Actor[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Actor[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Actor[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ActorsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('actors');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('Search.Search');

        $this->hasMany('FilmActors', [
            'foreignKey' => 'actor_id',
        ]);

        $this->belongsToMany('Films', [
            'through' => 'FilmActors',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->nonNegativeInteger('created_by')
            ->allowEmptyString('created_by');

        $validator
            ->nonNegativeInteger('modified_by')
            ->allowEmptyString('modified_by');

        $validator
            ->dateTime('deleted_at')
            ->allowEmptyDateTime('deleted_at');

        $validator
            ->nonNegativeInteger('version')
            ->notEmptyString('version');

        $validator
            ->uuid('uuid')
            ->allowEmptyString('uuid');

        $validator
            ->scalar('firstname')
            ->maxLength('firstname', 255)
            ->allowEmptyString('firstname');

        $validator
            ->scalar('lastname')
            ->maxLength('lastname', 255)
            ->allowEmptyString('lastname');

        return $validator;
    }
}
