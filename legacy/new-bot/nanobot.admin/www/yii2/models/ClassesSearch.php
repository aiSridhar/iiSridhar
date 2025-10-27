<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Classes;

/**
 * ClassesSearch represents the model behind the search form of `app\models\Classes`.
 */
class ClassesSearch extends Classes
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'script_id', 'step', 'priority', 'action_resend', 'action_vktalker_bl', 'action_set_list_id', 'action_set_script_id', 'action_set_step', 'action_finish', 'action_pause_sec_block', 'action_pause_sec_nonblock', 'if_age_min', 'if_age_max', 'if_list_id', 'if_city_id'], 'integer'],
            [['name', 'keywords', 'value', 'example', 'keywords_norm', 'action_work_mode', 'if_platform', 'if_engine', 'date_last_count'], 'safe'],
            [['action_exec_next', 'if_male', 'action_response_clear'], 'boolean'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Classes::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
	    'sort'=> ['defaultOrder' => ['script_id' => SORT_ASC,'step' => SORT_ASC,'priority' => SORT_ASC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'script_id' => $this->script_id,
            'step' => $this->step,
            'priority' => $this->priority,
            'action_exec_next' => $this->action_exec_next,
            'action_resend' => $this->action_resend,
            'action_vktalker_bl' => $this->action_vktalker_bl,
            'action_set_list_id' => $this->action_set_list_id,
            'action_set_script_id' => $this->action_set_script_id,
            'action_set_step' => $this->action_set_step,
            'action_finish' => $this->action_finish,
            'action_pause_sec_block' => $this->action_pause_sec_block,
            'action_pause_sec_nonblock' => $this->action_pause_sec_nonblock,
            'if_male' => $this->if_male,
            'if_age_min' => $this->if_age_min,
            'if_age_max' => $this->if_age_max,
            'if_list_id' => $this->if_list_id,
            'if_city_id' => $this->if_city_id,
            'action_response_clear' => $this->action_response_clear,
            'date_last_count' => $this->date_last_count,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'keywords', $this->keywords])
            ->andFilterWhere(['like', 'value', $this->value])
            ->andFilterWhere(['like', 'example', $this->example])
            ->andFilterWhere(['like', 'keywords_norm', $this->keywords_norm])
            ->andFilterWhere(['like', 'action_work_mode', $this->action_work_mode])
            ->andFilterWhere(['like', 'if_platform', $this->if_platform])
            ->andFilterWhere(['like', 'if_engine', $this->if_engine]);

        return $dataProvider;
    }
}
