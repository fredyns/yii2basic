<?php
/**
 * This is the template for generating CRUD search class of the specified model.
 */
use yii\db\Schema;
use yii\helpers\StringHelper;

/* @var $this \yii\web\View  */
/* @var $generator \app\generators\crud\Generator  */
/* @var $tableSchema \yii\db\TableSchema  */
/* @var $softdelete bool  */
/* @var $modelClassName string  */
/* @var $modelSlug string  */
/* @var $modelName string  */
/* @var $model \yii\db\ActiveRecord  */
/* @var $controllerClassName string  */
/* @var $controllerNameSpace string  */
/* @var $moduleNameSpace string  */
/* @var $moduleId string  */
/* @var $subPath string  */
/* @var $apiNameSpace string  */
/* @var $dateRange string[]  */
/* @var $timestampRange string[]  */

$searchModelClass = StringHelper::basename($generator->searchModelClass);
$rules = $generator->generateSearchRules();
$labels = $generator->generateSearchLabels();
$searchAttributes = $generator->getSearchAttributes();
$searchConditions = $generator->generateSearchConditions();

echo "<?php\n";
?>

namespace <?= StringHelper::dirname($generator->searchModelClass) ?>;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\components\DateSearch;
use app\components\TimestampSearch;
use <?= $generator->modelClass ?>;

/**
 * <?= $searchModelClass ?> represents the model behind the search form about `<?= $generator->modelClass ?>`.
 */
class <?= $searchModelClass ?> extends <?= $modelClassName ?>

{
<?php if($dateRange OR $timestampRange): ?>
<?php foreach ($dateRange as $rangeKey => $columnName): ?>
    public $<?= $rangeKey ?>Search;
<?php endforeach;?>
<?php foreach ($timestampRange as $rangeKey => $columnName): ?>
    public $<?= $rangeKey ?>Search;
<?php endforeach;?>

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // range search initiations
<?php foreach ($dateRange as $rangeKey => $columnName): ?>
        $this-><?= $rangeKey ?>Search = new DateSearch([
            'attribute' => '<?= $columnName ?>',
            'field' => static::tableName().'.<?= $columnName ?>',
        ]);
<?php endforeach;?>
<?php foreach ($timestampRange as $rangeKey => $columnName): ?>
        $this-><?= $rangeKey ?>Search = new TimestampSearch([
            'attribute' => '<?= $columnName ?>',
            'field' => static::tableName().'.<?= $columnName ?>',
        ]);
<?php endforeach;?>
    }
<?php endif;?>

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
            <?= implode(",\n            ", $rules) ?>,
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
        $query = <?= $modelClassName ?>::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
<?php if($tableSchema->getColumn('id') !== null): ?>
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
            ],
<?php endif;?>
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        <?= implode("\n        ", $searchConditions) ?>

<?php if($dateRange OR $timestampRange): ?>
<?php foreach ($dateRange as $rangeKey => $columnName): ?>
        $this-><?= $rangeKey ?>Search->applyFilter($query, $this-><?= $columnName ?>);
<?php endforeach;?>
<?php foreach ($timestampRange as $rangeKey => $columnName): ?>
        $this-><?= $rangeKey ?>Search->applyFilter($query, $this-><?= $columnName ?>);
<?php endforeach;?>
<?php endif;?>

        return $dataProvider;
    }

}