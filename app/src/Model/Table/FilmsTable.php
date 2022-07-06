<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

/**
 * Films Model
 *
 * @property \App\Model\Table\LanguagesTable&\Cake\ORM\Association\BelongsTo $Languages
 * @property \App\Model\Table\FilmActorsTable&\Cake\ORM\Association\HasMany $FilmActors
 * @property \App\Model\Table\FilmCategoriesTable&\Cake\ORM\Association\HasMany $FilmCategories
 * @property \App\Model\Table\FilmTextsTable&\Cake\ORM\Association\HasMany $FilmTexts
 * @property \App\Model\Table\RentalsTable&\Cake\ORM\Association\HasMany $Rentals
 *
 * @method \App\Model\Entity\Film newEmptyEntity()
 * @method \App\Model\Entity\Film newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Film[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Film get($primaryKey, $options = [])
 * @method \App\Model\Entity\Film findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Film patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Film[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Film|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Film saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Film[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Film[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Film[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Film[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class FilmsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void {
        parent::initialize($config);

        $this->setTable('films');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Search.Search');
        $this->addBehavior('Timestamp');

        $this->belongsTo('Languages', [
            'foreignKey' => 'language_id',
            'joinType'   => 'INNER',
        ]);
        $this->hasMany('FilmActors', [
            'foreignKey' => 'film_id',
        ]);
        $this->hasMany('FilmCategories', [
            'foreignKey' => 'film_id',
        ]);
        $this->hasMany('FilmTexts', [
            'foreignKey' => 'film_id',
        ]);
        $this->hasMany('Rentals', [
            'foreignKey' => 'film_id',
        ]);
        $this->belongsToMany('Actors', [
            'through' => 'FilmActors',
        ]);
        $this->belongsToMany('Categories', [
            'through' => 'FilmCategories',
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
            ->dateTime('deleted_at')
            ->allowEmptyDateTime('deleted_at');

        $validator
            ->nonNegativeInteger('version')
            ->notEmptyString('version');

        $validator
            ->uuid('uuid')
            ->allowEmptyString('uuid');

        $validator
            ->nonNegativeInteger('language_id')
            ->inList(
                'language_id',
                array_keys(TableRegistry::getTableLocator()->get('Languages')->find('list')->toArray()),
                'Must be a valid Language ID'
            )
            ->requirePresence('language_id', 'create')
            ->notEmptyString('language_id');

        $validator
            ->scalar('title')
            ->maxLength('title', 255)
            ->allowEmptyString('title');

        $validator
            ->scalar('description')
            ->allowEmptyString('description');

        $validator
            ->scalar('release_year')
            ->requirePresence('release_year', 'create')
            ->minLength('release_year', 4, 'Must be a 4 digit year')
            ->maxLength('release_year', 4, 'Must be a 4 digit year');

        $validator
            ->nonNegativeInteger('rental_duration')
            ->greaterThanOrEqual('rental_duration', 1, 'Value must be >= 1')
            ->allowEmptyString('rental_duration');

        $validator
            ->decimal('rental_rate')
            ->greaterThanOrEqual('rental_rate', 1, 'Value must be >= 1')
            ->allowEmptyString('rental_rate');

        $validator
            ->integer('length')
            ->greaterThanOrEqual('length', 1, 'Value must be >= 1')
            ->allowEmptyString('length');

        $validator
            ->decimal('replacement_cost')
            ->greaterThanOrEqual('replacement_cost', 1, 'Value must be >= 1')
            ->notEmptyString('replacement_cost');

        $ratings = ['PG', 'PG-13', 'R', 'NC-17', 'NR'];
        $validator
            ->scalar('lov_film_rating')
            ->requirePresence('lov_film_rating', 'create')
            ->inList('lov_film_rating', $ratings, 'Value must be one of: ' . implode(', ', $ratings))
            ->maxLength('lov_film_rating', 5);

        $validator
            ->scalar('special_features')
            ->maxLength('special_features', 255)
            ->allowEmptyString('special_features');

        $validator
            ->scalar('lov_film_status')
            ->maxLength('lov_film_status', 50)
            ->allowEmptyString('lov_film_status');

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
        $rules->add($rules->existsIn('language_id', 'Languages'), ['errorField' => 'language_id']);

        return $rules;
    }

    /**
     * Returns list of films grouped by year
     *
     * @param Query $query
     * @param array $options
     * @return Query
     */
    public function findGroupByRating(Query $query, array $options): Query {
        return $query
            ->select([
                'rating',
                'total' => $query->func()->count('Films.id')
            ])
            ->group(['rating']);
    }
}
