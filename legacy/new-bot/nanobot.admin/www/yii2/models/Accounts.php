<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "accounts".
 *
 * @property int $id
 * @property string $engine
 * @property string $status
 * @property string $date_message_first
 * @property string $date_message_last
 * @property string $date_status
 */
class Accounts extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'b_accounts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'engine', 'status'], 'required'],
            [['id'], 'integer'],
            [['engine', 'status'], 'string'],
            [['date_message_first', 'date_message_last', 'date_status'], 'safe'],
            [['id', 'engine'], 'unique', 'targetAttribute' => ['id', 'engine']],
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
            'status' => 'Status',
            'date_message_first' => 'Date Message First',
            'date_message_last' => 'Date Message Last',
            'date_status' => 'Date Status',
        ];
    }


    //count
    public function get_count_messages_in_group_today()
    {
        return $this->hasMany(MessagesInGroup::className(), ['account_id' => 'id','engine' => 'engine'])->where('DATE(`date_get`) = CURDATE()')->count();
    }

    public function get_count_messages_in_group_yesterday()
    {
        return $this->hasMany(MessagesInGroup::className(), ['account_id' => 'id','engine' => 'engine'])->where('DATE(`date_get`) = CURDATE()-1')->count();
    }

    public function get_count_messages_in_group_total()
    {
        return $this->hasMany(MessagesInGroup::className(), ['account_id' => 'id','engine' => 'engine'])->count();
    }

    public function get_count_messages_out_today()
    {
        return $this->hasMany(MessagesOut::className(), ['account_id' => 'id','engine' => 'engine'])->where('DATE(`date_send`) = CURDATE()')->count();
    }

    public function get_count_messages_out_yesterday()
    {
        return $this->hasMany(MessagesOut::className(), ['account_id' => 'id','engine' => 'engine'])->where('DATE(`date_send`) = CURDATE()-1')->count();
    }

    public function get_count_messages_out_total()
    {
        return $this->hasMany(MessagesOut::className(), ['account_id' => 'id','engine' => 'engine'])->count();
    }


    public function get_count_dialogs_today()
    {

        return 0;


        $count = (new yii\db\Query())
            ->select('COUNT(*) AS cnt')
            ->from('b_messages_in_group')
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


    public function get_count_ca_ready()
    {

        //return 0;


        $count = (new yii\db\Query())
            ->select('count(*) as cnt FROM b_ca AS ca
LEFT JOIN states ON ca.account_id=states.account_id AND ca.engine=states.engine AND ca.id=states.ca_id
LEFT JOIN scripts ON states.script_id=scripts.id
WHERE
ca.account_id="'.$this->id.'" AND
ca.engine="'.$this->engine.'" AND
(
(DATE_SUB(NOW(),INTERVAL 55 MINUTE)<last_seen_time 
) AND
messages_new_count>0
AND
((scripts.enabled=1 AND scripts.del=0) OR ISNULL(scripts.enabled)))')->createCommand()->queryAll()[0]["cnt"];


        return $count;
        //return $this->hasMany(MessagesInGroup::className(), ['script_id' => 'id'])->where('DATE(`date_send`) = CURDATE()')->groupBy('ca_id','engine')->count();
    }

    public function get_message_last()
    {

        //return 0;


        $count = (new yii\db\Query())
            ->select('MAX(states.step_date) as message_last FROM b_ca as ca
LEFT JOIN states ON ca.account_id=states.account_id AND ca.engine=states.engine AND ca.id=states.ca_id
LEFT JOIN scripts ON states.script_id=scripts.id
WHERE
ca.account_id="'.$this->id.'" AND
ca.engine="'.$this->engine.'"')->createCommand()->queryAll()[0]["message_last"];


        return $count;
        //return $this->hasMany(MessagesInGroup::className(), ['script_id' => 'id'])->where('DATE(`date_send`) = CURDATE()')->groupBy('ca_id','engine')->count();
    }

}
