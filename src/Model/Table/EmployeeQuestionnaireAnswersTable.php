<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ClientQuestionnaireAnswers Model
 *
 * @property \App\Model\Table\ClientQuestionnairesTable|\Cake\ORM\Association\BelongsTo $ClientQuestionnaires
 *
 * @method \App\Model\Entity\ClientQuestionnaireAnswer get($primaryKey, $options = [])
 * @method \App\Model\Entity\ClientQuestionnaireAnswer newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ClientQuestionnaireAnswer[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ClientQuestionnaireAnswer|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ClientQuestionnaireAnswer saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ClientQuestionnaireAnswer patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ClientQuestionnaireAnswer[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ClientQuestionnaireAnswer findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class EmployeeQuestionnaireAnswersTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('employee_questionnaire_answers');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('EmployeeQuestionnaires', [
            'foreignKey' => 'employee_questionnaire_id'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', 'create');

        $validator
            ->scalar('question')
            ->maxLength('question', 400)
            ->allowEmptyString('question');

        $validator
            ->scalar('answer')
            ->allowEmptyString('answer');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['employee_questionnaire_id'], 'EmployeeQuestionnaires'));

        return $rules;
    }
}
