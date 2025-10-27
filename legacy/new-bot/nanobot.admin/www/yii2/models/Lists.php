<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "lists".
 *
 * @property int $id
 * @property string $name
 *
 * @property ListMembers[] $listMembers
 */
class Lists extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'b_lists';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 32],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getListMembers()
    {
        return $this->hasMany(ListMembers::className(), ['list_id' => 'id']);
    }
}
