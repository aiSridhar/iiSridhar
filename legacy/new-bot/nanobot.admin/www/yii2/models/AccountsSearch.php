<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Accounts;

/**
 * AccountsSearch represents the model behind the search form of `app\models\Accounts`.
 */
class AccountsSearch extends Accounts
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['engine', 'status', 'date_message_first', 'date_message_last', 'date_status'], 'safe'],
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
        $query = Accounts::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            'date_message_first' => $this->date_message_first,
            'date_message_last' => $this->date_message_last,
            'date_status' => $this->date_status,
        ]);

        $query->andFilterWhere(['like', 'engine', $this->engine])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
