<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use app\models\geographical_hierarchy\Country;

/**
 * 
 */
class ImportController extends Controller
{

    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @return int Exit code
     */
    public function actionIndex()
    {
        echo "Wanna import something?\n";

        return ExitCode::OK;
    }

    /**
     * import country names from Country.io
     * @return int Exit code
     */
    public function actionCountryioNames()
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "http://country.io/names.json");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);

        $feed = json_decode($output, TRUE);

        if (empty($feed) OR is_array($feed) == FALSE) {
            echo "Feed unreadable.\n";
            echo $output."\n";
            return ExitCode::UNSPECIFIED_ERROR;
        }

        $existing_list = Country::find()->select(['code'])->column();

        foreach ($existing_list as $code) {
            if (isset($feed[$code])) {
                echo "Existing country: {$code}-{$feed[$code]}\n";
                unset($feed[$code]);
            }
        }

        echo "\n";

        $rows = [];
        foreach ($feed as $code => $name) {
            $rows[] = [
                'code' => $code,
                'name' => $name,
            ];
        }

        Yii::$app->db->createCommand()
            ->batchInsert(Country::tableName(), ['code', 'name'], $rows)
            ->execute()
        ;

        echo "Imported:\n";
        echo "- ".implode("\n- ", $feed)."\n";

        return ExitCode::OK;
    }

}