<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Questionnaire Entity
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $name
 * @property string|null $questions
 * @property string|null $evidence_file
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\User $user
 */
class Questionnaire extends Entity
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
        'user_id' => true,
        'name' => true,
        'questions' => true,
        'evidence_file' => true,
        'created' => true,
        'modified' => true,
        'user' => true
    ];
}
