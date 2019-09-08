<?php

namespace app\actions\geographical_hierarchy\city;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\components\DateSearch;
use app\components\TimestampSearch;
use app\models\geographical_hierarchy\City;

/**
 * CitySearch represents the model behind the search form about `app\models\geographical_hierarchy\City`.
 */
class CitySearch extends City
{
    public $countryName;
    public $regionName;

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
            [['id', 'type_id', 'country_id', 'region_id', 'reg_number'], 'integer'],
            [
                ['name', 'countryName', 'regionName'],
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
        $query = City::find()
            ->joinWith('country')
            ->joinWith('region');

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
            static::tableName().'.type_id' => $this->type_id,
            static::tableName().'.country_id' => $this->country_id,
            static::tableName().'.region_id' => $this->region_id,
            static::tableName().'.reg_number' => $this->reg_number,
        ]);

        $query
            ->andFilterWhere(['like', static::tableName().'.name', $this->name])
            ->andFilterWhere(['like', static::COUNTRY.'.name', $this->countryName])
            ->andFilterWhere(['like', static::REGION.'.name', $this->regionName])
        ;


        return $dataProvider;
    }

}