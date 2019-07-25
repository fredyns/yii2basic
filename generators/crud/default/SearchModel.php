<?php
/**
 * This is the template for generating CRUD search class of the specified model.
 */
use yii\db\Schema;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator app\generators\crud\Generator */

$modelClass = StringHelper::basename($generator->modelClass);
$searchModelClass = StringHelper::basename($generator->searchModelClass);
if ($modelClass === $searchModelClass) {
    $modelAlias = $modelClass.'Model';
}
$rules = $generator->generateSearchRules();
$labels = $generator->generateSearchLabels();
$searchAttributes = $generator->getSearchAttributes();
$searchConditions = $generator->generateSearchConditions();
$tableSchema = $generator->getTableSchema();

/**
 * range search
 */
$dateRange = [];
$timestampRange = [];
$skipCols = ['updated_at', 'deleted_at'];
foreach ($tableSchema->columns as $column) {
    if (in_array($column->name, $skipCols)) {
        continue;
    }
    if ($column->type == Schema::TYPE_INTEGER && substr_compare($column->name, '_at', -3, 3, true) === 0) {
        $filterName = substr($column->name, 0, (strlen($column->name) - 3));
        if (!$filterName) {
            continue;
        }
        $timestampRange[$filterName] = $column->name;
        continue;
    }
    if ($column->type == Schema::TYPE_DATE) {
        if (substr_compare($column->name, '_date', -5, 5, true) === 0) {
            $filterName = substr($column->name, 0, (strlen($column->name) - 5));
        } else {
            $filterName = $column->name;
        }
        if (!$filterName) {
            continue;
        }
        $dateRange[$filterName] = $column->name;
        continue;
    }
}


echo "<?php\n";
?>

namespace <?= StringHelper::dirname(ltrim($generator->searchModelClass, '\\')) ?>;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\lib\DateSearch;
use app\lib\TimestampSearch;
use <?= ltrim($generator->modelClass, '\\').(isset($modelAlias) ? " as $modelAlias" : '') ?>;

/**
 * <?= $searchModelClass ?> represents the model behind the search form about `<?= $generator->modelClass ?>`.
 */
class <?= $searchModelClass ?> extends <?= isset($modelAlias) ? $modelAlias : $modelClass ?>

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
        $query = <?= isset($modelAlias) ? $modelAlias : $modelClass ?>::find();

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