<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "messages_out".
 *
 * @property int $id
 * @property string $engine
 * @property int $script_id
 * @property int $step
 * @property int $class_id
 * @property int $account_id
 * @property int $ca_id
 * @property string $external_id
 * @property string $text_send
 * @property int $group_id
 * @property int $sended
 * @property string $date_ready
 * @property string $date_send
 */
class MessagesOut extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'b_messages_out';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['engine', 'script_id', 'step', 'account_id', 'ca_id', 'text_send', 'group_id', 'date_ready'], 'required'],
            [['engine', 'text_send'], 'string'],
            [['script_id', 'step', 'class_id', 'account_id', 'ca_id', 'group_id', 'sended'], 'integer'],
            [['date_ready', 'date_send'], 'safe'],
            [['external_id'], 'string', 'max' => 100],
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
            'class_id' => 'Class ID',
            'account_id' => 'Account ID',
            'ca_id' => 'Ca ID',
            'external_id' => 'External ID',
            'text_send' => 'Text Send',
            'group_id' => 'Group ID',
            'sended' => 'Sended',
            'date_ready' => 'Date Ready',
            'date_send' => 'Date Send',
        ];
    }

    public function get_messages_in_group()
    {
        return $this->hasOne(MessagesInGroup::className(), ['id' => 'group_id'])->one();
    }
}
