<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Scripts;

/**
 * ScriptsSearch represents the model behind the search form of `app\models\Scripts`.
 */
class ScriptsSearch extends Scripts
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name'], 'safe'],
            [['enabled', 'use_stem', 'use_orfo', 'use_translit', 'ai_keyword', 'ai_bayes', 'ai_neuro', 'ai_expert', 'ai_operator'], 'boolean'],
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
        $query = Scripts::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
	    'sort'=> ['defaultOrder' => ['id' => SORT_ASC]]
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
            'enabled' => $this->enabled,
            'use_stem' => $this->use_stem,
            'use_orfo' => $this->use_orfo,
            'use_translit' => $this->use_translit,
            'ai_keyword' => $this->ai_keyword,
            'ai_bayes' => $this->ai_bayes,
            'ai_neuro' => $this->ai_neuro,
            'ai_expert' => $this->ai_expert,
            'ai_operator' => $this->ai_operator,]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
