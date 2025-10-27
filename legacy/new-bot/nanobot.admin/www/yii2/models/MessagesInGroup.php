<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "messages_in_group".
 *
 * @property int $id
 * @property string $engine
 * @property int $script_id
 * @property int $step
 * @property int $account_id
 * @property int $ca_id
 * @property string $text
 * @property string $text_norm
 * @property string $date_send
 * @property string $date_get
 * @property string $date_norm
 * @property string $date_class
 * @property string $class_engine
 * @property int $class_id
 */
class MessagesInGroup extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'b_messages_in_group';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['engine', 'script_id', 'step', 'account_id', 'text'], 'required'],
            [['engine', 'text', 'text_norm', 'class_engine'], 'string'],
            [['script_id', 'step', 'account_id', 'ca_id', 'class_id'], 'integer'],
            [['date_send', 'date_get', 'date_norm', 'date_class'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'engine' => 'Engine',
            'script_id' => 'Script ID',
            'step' => 'Step',
            'account_id' => 'Account ID',
            'ca_id' => 'Ca ID',
            'text' => 'Text',
            'text_norm' => 'Text Norm',
            'date_send' => 'Date Send',
            'date_get' => 'Date Get',
            'date_norm' => 'Date Norm',
            'date_class' => 'Date Class',
            'class_engine' => 'Class Engine',
            'class_id' => 'Class ID',
        ];
    }

    public function get_class()
    {
        return $this->hasOne(Classes::className(), ['id' => 'class_id'])->one();
    }
}
