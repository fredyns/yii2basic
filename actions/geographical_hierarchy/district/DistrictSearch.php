<?php

namespace app\actions\geographical_hierarchy\district;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\lib\DateSearch;
use app\lib\TimestampSearch;
use app\models\geographical_hierarchy\District;

/**
 * DistrictSearch represents the model behind the search form about `app\models\geographical_hierarchy\District`.
 */
class DistrictSearch extends District
{
    public $cityName;

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
            [['id', 'type_id', 'city_id', 'reg_number'], 'integer'],
            [
                ['name', 'cityName'],
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
        $query = District::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
                'attributes' => [
                    // native attributes
                    'id',
                    'name',
                    'type_id',
                    'city_id',
                    'reg_number',
                    // extended attributes
                    'cityName' => [
                        'asc' => [static::CITY.'.name' => SORT_ASC],
                        'desc' => [static::CITY.'.name' => SORT_DESC],
                    ],
                ],
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
            static::tableName().'.type_id' => $this->type_id,
            static::tableName().'.city_id' => $this->city_id,
            static::tableName().'.reg_number' => $this->reg_number,
        ]);

        $query
            ->andFilterWhere(['like', static::tableName().'.name', $this->name])
            ->andFilterWhere(['like', static::CITY.'.name', $this->cityName])
        ;


        return $dataProvider;
    }

}