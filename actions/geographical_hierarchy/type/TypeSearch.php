<?php

namespace app\actions\geographical_hierarchy\type;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\lib\DateSearch;
use app\lib\TimestampSearch;
use app\models\geographical_hierarchy\Type;

/**
 * TypeSearch represents the model behind the search form about `app\models\geographical_hierarchy\Type`.
 */
class TypeSearch extends Type
{

    /**
     * @inheritdoc
     */
    public function formName()
    {
        return '';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [
                ['name', 'description'],
                \fredyns\stringcleaner\yii2\PlaintextValidator::class,
            ],
        ];
    }

    /**
     * @inheritdoc
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
        $query = Type::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            static::tableName().'.id' => $this->id,
        ]);

        $query
            ->andFilterWhere(['like', static::tableName().'.name', $this->name])
            ->andFilterWhere(['like', static::tableName().'.description', $this->description])
        ;


        return $dataProvider;
    }

}