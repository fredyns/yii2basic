<?php

namespace app\actions\geographical_hierarchy\region;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\lib\DateSearch;
use app\lib\TimestampSearch;
use app\models\geographical_hierarchy\Region;

/**
 * RegionSearch represents the model behind the search form about `app\models\geographical_hierarchy\Region`.
 */
class RegionSearch extends Region
{
    public $countryName;

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
            [['id', 'type_id', 'country_id', 'reg_number'], 'integer'],
            [
                ['name', 'countryName'],
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
        $query = Region::find()
            ->joinWith('country');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
                'attributes' => [
                    // native attributes
                    'id',
                    'name',
                    'type_id',
                    'country_id',
                    'reg_number',
                    // extended attributes
                    'countryName' => [
                        'asc' => [static::COUNTRY.'.name' => SORT_ASC],
                        'desc' => [static::COUNTRY.'.name' => SORT_DESC],
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
            static::tableName().'.country_id' => $this->country_id,
            static::tableName().'.reg_number' => $this->reg_number,
        ]);

        $query
            ->andFilterWhere(['like', static::tableName().'.name', $this->name])
            ->andFilterWhere(['like', static::COUNTRY.'.name', $this->countryName])
        ;


        return $dataProvider;
    }

}