<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * FilmActors Model
 *
 * @property \App\Model\Table\FilmsTable&\Cake\ORM\Association\BelongsTo $Films
 * @property \App\Model\Table\ActorsTable&\Cake\ORM\Association\BelongsTo $Actors
 *
 * @method \App\Model\Entity\FilmActor newEmptyEntity()
 * @method \App\Model\Entity\FilmActor newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\FilmActor[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\FilmActor get($primaryKey, $options = [])
 * @method \App\Model\Entity\FilmActor findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\FilmActor patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\FilmActor[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\FilmActor|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FilmActor saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FilmActor[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\FilmActor[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\FilmActor[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\FilmActor[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class FilmActorsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void {
        parent::initialize($config);

        $this->setTable('film_actors');
        $this->setDisplayField('uuid');
        $this->setPrimaryKey(['film_id', 'actor_id']);

        $this->addBehavior('Search.Search');
        $this->addBehavior('Timestamp');

        $this->belongsTo('Films', [
            'foreignKey' => 'film_id',
            'joinType'   => 'INNER',
        ]);
        $this->belongsTo('Actors', [
            'foreignKey' => 'actor_id',
            'joinType'   => 'INNER',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator {
        $validator
            ->nonNegativeInteger('created_by')
            ->allowEmptyString('created_by');

        $validator
            ->nonNegativeInteger('modified_by')
            ->allowEmptyString('modified_by');

        $validator
            ->uuid('uuid')
            ->allowEmptyString('uuid');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker {
        $rules->add($rules->existsIn('film_id', 'Films'), ['errorField' => 'film_id']);
        $rules->add($rules->existsIn('actor_id', 'Actors'), ['errorField' => 'actor_id']);

        return $rules;
    }

    /**
     * @param Query $query
     * @param string $id
     * @return Query
     */
    public function findFilmsByActor(Query $query, array $options): Query {
        return $query
            ->contain(['Films'])
            ->where(['actor_id' => $options['actor_id']]);
    }

    /**
     * @param Query $query
     * @param string $id
     * @return Query
     */
    public function findActorsByFilm(Query $query, array $options): Query {
        return $query
            ->contain(['Actors'])
            ->where(['film_id' => $options['film_id']]);
    }
}
