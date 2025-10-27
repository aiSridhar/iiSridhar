<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "classes".
 *
 * @property int $id
 * @property string $name
 * @property int $script_id
 * @property int $step
 * @property string $keywords
 * @property string $value
 * @property string $example
 * @property string $keywords_norm
 * @property int $priority
 * @property bool $action_exec_next
 * @property int $action_resend
 * @property int $action_vktalker_bl
 * @property int $action_set_list_id
 * @property int $action_set_script_id
 * @property int $action_set_step
 * @property int $action_finish
 * @property string $action_work_mode
 * @property int $action_pause_sec_block
 * @property int $action_pause_sec_nonblock
 * @property string $if_platform
 * @property bool $if_male
 * @property int $if_age_min
 * @property int $if_age_max
 * @property int $if_list_id
 * @property string $if_engine
 * @property int $if_city_id
 * @property bool $action_response_clear
 * @property int $count_today
 * @property int $count_total
 * @property int $count_yesterday
 * @property string $date_last_count
 *
 * @property Scripts $script
 */
class Classes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'b_classes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [

            [['name', 'script_id', 'step'], 'required'],

            [['script_id', 'step',  'priority', 'action_resend', 'action_vktalker_bl', 'action_set_list_id', 'action_set_script_id', 'action_set_step', 'action_finish', 'action_pause_sec_block', 'action_pause_sec_nonblock', 'if_age_min', 'if_age_max', 'if_list_id', 'if_not_list_id', 'if_city_id'], 'integer'],
            [['keywords', 'value', 'example', 'keywords_norm', 'action_work_mode', 'if_platform', 'if_engine'], 'string'],
            [['action_exec_next', 'if_male', 'action_response_clear','enabled'], 'boolean'],
            [['date_last_count'], 'safe'],
            [['name'], 'string', 'max' => 32],
            [['script_id', 'name'], 'unique', 'targetAttribute' => ['script_id', 'name']],
            //[['action_work_mode'],'default',null],

            [['script_id'], 'exist', 'skipOnError' => true, 'targetClass' => Scripts::className(), 'targetAttribute' => ['script_id' => 'id']],

            [['action_set_script_id','action_set_step','action_set_list_id','action_work_mode','action_finish','action_resend','action_response_clear','if_platform','if_engine','if_male','if_list_id','if_not_list_id'], 'filter', 'filter' => function ($value) {
                if($value == "")
                {

                    return null;
                }
                return $value;
            }] // FOR NULL

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'class_id',
            'name' => 'код',
            'script_id' => 'script_id',
            'step' => 'step',
            'post' => 'Post',
            'keywords' => 'keywords',
            'value' => 'response',
            'example' => 'example',
            'keywords_norm' => 'keywords_norm',
            'priority' => 'priority',

            'action_exec_next' => 'action_exec_next',
            'action_resend' => 'action_resend',
            'action_vktalker_bl' => 'action_vktalker_bl',
            'action_set_list_id' => 'action_set_list_id',
            'action_set_script_id' => 'action_set_script_id',
            'action_set_step' => 'action_set_step',
            'action_finish' => 'action_finish',
            'action_work_mode' => 'action_work_mode',
            'action_pause_sec_block' => 'action_pause_sec_block',
            'action_pause_sec_nonblock' => 'action_pause_sec_nonblock',

            'if_platform' => 'if_platform',
            'if_male' => 'if_male',
            'if_age_min' => 'if_age_min',
            'if_age_max' => 'if_age_max',
            'if_list_id' => 'if_list_id',
            'if_not_list_id' => 'if_not_list_id',
            'if_engine' => 'if_engine',
            'if_city_id' => 'if_city_id',
            'action_response_clear' => 'action_response_clear',
            'date_last_count' => 'date_last_count',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScript()
    {
        return $this->hasOne(Scripts::className(), ['id' => 'script_id']);
    }



    /*
     *
     *User::find()->where(['id' => $id])->one();
     *
     *         ->orderBy(['id'=>SORT_DESC])
    ->groupBy('lineID')
    ->via('ordersLines');
}
If $model is an OrdersModel instance, you obtain the last stat row using:

$model->lastOrderLineStat

     */

    public function get_count_messages_in_group_today()
    {
        return $this->hasMany(MessagesInGroup::className(), ['class_id' => 'id'])->where('DATE(`date_get`) = CURDATE()')->count();
    }

    public function get_count_messages_in_group_yesterday()
    {
        return $this->hasMany(MessagesInGroup::className(), ['class_id' => 'id'])->where('DATE(`date_get`) = CURDATE()-1')->count();
    }

    public function get_count_messages_in_group_total()
    {
        return $this->hasMany(MessagesInGroup::className(), ['class_id' => 'id'])->count();
    }

    public function get_count_messages_out_today()
    {
        return $this->hasMany(MessagesOut::className(), ['class_id' => 'id'])->where('DATE(`date_send`) = CURDATE()')->count();
    }

    public function get_count_messages_out_yesterday()
    {
        return $this->hasMany(MessagesOut::className(), ['class_id' => 'id'])->where('DATE(`date_send`) = CURDATE()-1')->count();
    }

    public function get_count_messages_out_total()
    {
        return $this->hasMany(MessagesOut::className(), ['class_id' => 'id'])->count();
    }

    public function copy()
    {
        /*
                try {
                    if ($this->validate()) {
                        // assuming Rmtemplate is the model used in RmtemplateSearch
                        $selectedRmtemplate = Rmtemplate::find()->where(['id' => $this->rmtemplate_ids]);
                        foreach ($selectedRmtemplate->each() as $rm) {
                            $rawMaterial = new Rawmaterial();
                            $rawMaterial->rmname = $rm->rmname;
                            $rawMaterial->usedate = $this->usedate;
                            $rawMaterial->useqty = $rm->qty;
                            $rawMaterial->unitcost = $rm->unitcost;
                            $rawMaterial->productname = $rm->productname;
                            $rawMaterial->chargenumber = $this->chargenumber;
                            if (!$rawMaterial->save()) {
                                throw new \Exception('Error while saving rawMaterial!');
                            }
                        }
                        return true;
                    }
                } catch (\Exception $exc) {
                    \Yii::error($exc->getMessage());
                }
                return false;

            }
        */
        return;
    }

}