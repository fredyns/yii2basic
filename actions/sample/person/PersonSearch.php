<?php

namespace app\actions\sample\person;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\lib\DateSearch;
use app\lib\TimestampSearch;
use app\models\sample\Person;

/**
 * PersonSearch represents the model behind the search form about `app\models\sample\Person`.
 */
class PersonSearch extends Person
{
    public $createdSearch;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // range search initiations
        $this->createdSearch = new TimestampSearch([
            'attribute' => 'created_at',
            'field' => static::tableName().'.created_at',
        ]);
    }

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
            [['id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [
                ['created_at', 'name'],
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
        $query = Person::find();

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
            static::tableName().'.created_by' => $this->created_by,
            static::tableName().'.updated_by' => $this->updated_by,
            static::tableName().'.is_deleted' => $this->is_deleted,
            static::tableName().'.deleted_by' => $this->deleted_by,
        ]);

        $query
            ->andFilterWhere(['like', static::tableName().'.name', $this->name])
        ;

        $this->createdSearch->applyFilter($query, $this->created_at);

        return $dataProvider;
    }

}