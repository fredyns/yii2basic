<?php

namespace app\lib\geo_address\subdistrict;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\components\DateSearch;
use app\components\TimestampSearch;
use app\models\geo_address\Subdistrict;

/**
 * SubdistrictSearch represents the model behind the search form about `app\models\geo_address\Subdistrict`.
 */
class SubdistrictSearch extends Subdistrict
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
            [['id', 'type_id', 'district_id', 'reg_number'], 'integer'],
            [
                ['name'],
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
        $query = Subdistrict::find();

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
            static::tableName().'.district_id' => $this->district_id,
            static::tableName().'.reg_number' => $this->reg_number,
        ]);

        $query
            ->andFilterWhere(['like', static::tableName().'.name', $this->name])
        ;


        return $dataProvider;
    }

}