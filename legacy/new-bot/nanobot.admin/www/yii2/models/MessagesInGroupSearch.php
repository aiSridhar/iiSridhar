<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\MessagesInGroup;

/**
 * MessagesInGroupSearch represents the model behind the search form of `app\models\MessagesInGroup`.
 */
class MessagesInGroupSearch extends MessagesInGroup
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'script_id', 'step', 'account_id', 'ca_id', 'class_id'], 'integer'],
            [['engine', 'text', 'text_norm', 'date_send', 'date_get', 'date_norm', 'date_class', 'class_engine'], 'safe'],
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
        $query = MessagesInGroup::find();

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
            'account_id' => $this->account_id,
            'ca_id' => $this->ca_id,
            'date_send' => $this->date_send,
            'date_get' => $this->date_get,
            'date_norm' => $this->date_norm,
            'date_class' => $this->date_class,
            'class_id' => $this->class_id,
        ]);

        $query->andFilterWhere(['like', 'engine', $this->engine])
            ->andFilterWhere(['like', 'text', $this->text])
            ->andFilterWhere(['like', 'text_norm', $this->text_norm])
            ->andFilterWhere(['like', 'class_engine', $this->class_engine]);

        return $dataProvider;
    }
}
