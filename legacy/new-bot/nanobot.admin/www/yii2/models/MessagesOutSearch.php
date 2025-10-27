<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\MessagesOut;

/**
 * MessagesOutSearch represents the model behind the search form of `app\models\MessagesOut`.
 */
class MessagesOutSearch extends MessagesOut
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'script_id', 'step', 'class_id', 'account_id', 'ca_id', 'group_id', 'sended'], 'integer'],
            [['engine', 'external_id', 'text_send', 'date_ready', 'date_send'], 'safe'],
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
        $query = MessagesOut::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
	        'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
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
            'class_id' => $this->class_id,
            'account_id' => $this->account_id,
            'ca_id' => $this->ca_id,
            'group_id' => $this->group_id,
            //'sended' => $this->sended,
            //'date_ready' => $this->date_ready,
            //'date_send' => $this->date_send,
        ]);


        $query->andFilterWhere(['like', 'engine', $this->engine]);
            //->andFilterWhere(['like', 'external_id', $this->external_id])
            //->andFilterWhere(['like', 'text_send', $this->text_send]);

        return $dataProvider;
    }
}
