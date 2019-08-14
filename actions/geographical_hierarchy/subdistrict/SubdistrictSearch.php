<?php

namespace app\actions\geographical_hierarchy\subdistrict;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\lib\DateSearch;
use app\lib\TimestampSearch;
use app\models\geographical_hierarchy\Subdistrict;

/**
 * SubdistrictSearch represents the model behind the search form about `app\models\geographical_hierarchy\Subdistrict`.
 */
class SubdistrictSearch extends Subdistrict
{
    public $districtName;

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
                ['name','districtName'],
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
                'attributes' => [
                    // native attributes
                    'id',
                    'name',
                    'type_id',
                    'district_id',
                    'reg_number',
                    // extended attributes
                    'districtName' => [
                        'asc' => [static::DISTRICT.'.name' => SORT_ASC],
                        'desc' => [static::DISTRICT.'.name' => SORT_DESC],
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
            static::tableName().'.district_id' => $this->district_id,
            static::tableName().'.reg_number' => $this->reg_number,
        ]);

        $query
            ->andFilterWhere(['like', static::tableName().'.name', $this->name])
            ->andFilterWhere(['like', static::DISTRICT.'.name', $this->districtName])
        ;


        return $dataProvider;
    }

}