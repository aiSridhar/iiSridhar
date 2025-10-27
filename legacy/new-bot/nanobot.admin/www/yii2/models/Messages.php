<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "messages".
 *
 * @property string $in
 * @property string $in_id
 * @property string $out_id
 * @property string $group_id
 * @property string $engine
 * @property int $account_id
 * @property int $ca_id
 * @property string $date
 * @property int $script_id
 * @property int $step
 * @property int $class_id
 * @property string $text
 */
class Messages extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'b_messages';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['account_id', 'ca_id', 'script_id', 'step', 'class_id'], 'integer'],
            [['date'], 'safe'],
            [['text'], 'required'],
            [['text'], 'string'],
            [['in'], 'string', 'max' => 3],
            [['in_id', 'out_id', 'group_id'], 'string', 'max' => 11],
            [['engine'], 'string', 'max' => 8],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'in' => 'In',
            'in_id' => 'In ID',
            'out_id' => 'Out ID',
            'group_id' => 'Group ID',
            'engine' => 'Engine',
            'account_id' => 'Account ID',
            'ca_id' => 'Ca ID',
            'date' => 'Date',
            'script_id' => 'Script ID',
            'step' => 'Step',
            'class_id' => 'Class ID',
            'text' => 'Text',
        ];
    }
}
