<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "scripts".
 *
 * @property int $id
 * @property string $name
 * @property bool $enabled
 * @property bool $use_stem
 * @property bool $use_orfo
 * @property bool $use_translit
 * @property bool $ai_keyword
 * @property bool $ai_bayes
 * @property bool $ai_neuro
 * @property bool $ai_expert
 * @property bool $ai_operator
 * @property int count_scripts
 * @property int count_messages_in_group_today
 * @property int count_messages_in_group_yesterday
 * @property int count_messages_in_group_total
 * @property int count_messages_in_group_noclass_today
 * @property int count_messages_in_group_noclass_yesterday
 * @property int count_messages_in_group_noclass_total
 * @property int count_messages_out_today
 * @property int count_messages_out_yesterday
 * @property int count_messages_out_total
 * @property int count_dialogs_today
 * @property int count_dialogs_yesterday
 * @property int count_dialogs_total
 *
 * @property Classes[] $classes
 */
class Scripts extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'b_scripts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['del','enabled', 'use_stem', 'use_orfo', 'use_translit', 'ai_keyword', 'ai_bayes', 'ai_neuro', 'ai_expert', 'ai_operator'], 'boolean'],
            //[['count_today', 'count_total'], 'integer'],
            [['name'], 'string', 'max' => 32],
            [['name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'script_id',
            'name' => 'Name',
            'enabled' => 'Enabled',
            'use_stem' => 'Use Stem',
            'use_orfo' => 'Use Orfo',
            'use_translit' => 'Use Translit',
            'ai_keyword' => 'Ai Keyword',
            'ai_bayes' => 'Ai Bayes',
            'ai_neuro' => 'Ai Neuro',
            'ai_expert' => 'Ai Expert',
            'ai_operator' => 'Ai Operator',
            //'count_classes',
	        'count_messages_in_group_today',
            'count_messages_in_group_yesterday',
            'count_messages_in_group_total',
            'count_messages_in_group_noclass_today',
            'count_messages_in_group_noclass_yesterday',
            'count_messages_in_group_noclass_total',
	        'count_messages_out_today',
	        'count_messages_out_yesterday',
	        'count_messages_out_total',
            'count_dialogs_today',
	        'count_dialogs_yesterday',
            'count_dialogs_total',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClasses()
    {
        return $this->hasMany(Classes::className(), ['script_id' => 'id']);
    }

    public function get_count_classes()
    {
        return $this->hasMany(Classes::className(), ['script_id' => 'id'])->count();
    }

    //count
    public function get_count_messages_in_group_today()
    {
        return $this->hasMany(MessagesInGroup::className(), ['script_id' => 'id'])->where('DATE(`date_get`) = CURDATE()')->count();
    }

    public function get_count_messages_in_group_yesterday()
    {
        return $this->hasMany(MessagesInGroup::className(), ['script_id' => 'id'])->where('DATE(`date_get`) = CURDATE()-1')->count();
    }

    public function get_count_messages_in_group_total()
    {
        return $this->hasMany(MessagesInGroup::className(), ['script_id' => 'id'])->count();
    }

    public function get_count_messages_out_today()
    {
        return $this->hasMany(MessagesOut::className(), ['script_id' => 'id'])->where('DATE(`date_send`) = CURDATE()')->count();
    }

    public function get_count_messages_out_yesterday()
    {
        return $this->hasMany(MessagesOut::className(), ['script_id' => 'id'])->where('DATE(`date_send`) = CURDATE()-1')->count();
    }

    public function get_count_messages_out_total()
    {
        return $this->hasMany(MessagesOut::className(), ['script_id' => 'id'])->count();
    }


    public function get_count_dialogs_today()
    {

        return 0;


        $count = (new yii\db\Query())
            ->select('COUNT(*) AS cnt')
            ->from('messages_in_group')
            ->where('DATE(`date_send`) = CURDATE()')
            ->groupBy('engine','account_id','ca_id')
            ->createCommand()->queryAll()[0]["cnt"];


        return $count;
        //return $this->hasMany(MessagesInGroup::className(), ['script_id' => 'id'])->where('DATE(`date_send`) = CURDATE()')->groupBy('ca_id','engine')->count();
    }

    public function get_count_dialogs_yesterday()
    {
        return 0;
        return $this->hasMany(MessagesInGroup::className(), ['script_id' => 'id'])->where('DATE(`date_send`) = CURDATE()-1')->groupBy('ca_id','engine')->count();
    }

    public function get_count_dialogs_total()
    {
        return 0;
        return $this->hasMany(MessagesInGroup::className(), ['script_id' => 'id'])->groupBy('ca_id','engine')->count();
    }


    public function get_count_messages_in_noclass_today()
    {
        return 0;
        return $this->hasMany(MessagesInGroup::className(), ['script_id' => 'id'])->where('DATE(`date_get`) = CURDATE() AND class_id<=0')->count();
    }

    public function get_count_messages_in_noclass_yesterday()
    {
        return 0;
        return $this->hasMany(MessagesInGroup::className(), ['script_id' => 'id'])->where('DATE(`date_get`) = CURDATE()-1 AND class_id<=0')->count();
    }

    public function get_count_messages_in_noclass_total()
    {
        return 0;
        return $this->hasMany(MessagesInGroup::className(), ['script_id' => 'id'])->where('class_id<=0')->count();
    }

}
