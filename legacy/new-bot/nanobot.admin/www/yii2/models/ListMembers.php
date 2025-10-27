<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "list_members".
 *
 * @property int $list_id
 * @property string $engine
 * @property int $ca_id
 * @property string $date_add
 *
 * @property Lists $list
 */
class ListMembers extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'b_list_members';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['list_id', 'engine', 'ca_id'], 'required'],
            [['list_id', 'ca_id'], 'integer'],
            [['engine'], 'string'],
            [['date_add'], 'safe'],
            [['list_id', 'engine', 'ca_id'], 'unique', 'targetAttribute' => ['list_id', 'engine', 'ca_id']],
            [['list_id'], 'exist', 'skipOnError' => true, 'targetClass' => Lists::className(), 'targetAttribute' => ['list_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'list_id' => 'List ID',
            'engine' => 'Engine',
            'ca_id' => 'Ca ID',
            'date_add' => 'Date Add',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getList()
    {
        return $this->hasOne(Lists::className(), ['id' => 'list_id']);
    }
}
