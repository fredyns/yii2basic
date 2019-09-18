<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\ArrayHelper;
use app\models\geo_address\City;
use app\models\geo_address\Country;
use app\models\geo_address\District;
use app\models\geo_address\Region;
use app\models\geo_address\Subdistrict;
use app\models\geo_address\Type;

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

    /**
     * import indonesian provinces
     * @return int Exit code
     */
    public function actionIdProvinces()
    {
        // country model
        $country = Country::findOne(['code' => 'ID']);
        if (empty($country)) {
            $country = new Country([
                'code' => "ID",
                'name' => "Indonesia",
            ]);
            $country->save(FALSE);
            echo "New Country Inserted: Indonesia\n";
        }

        // type model
        $type = Type::findOne(['name' => 'Provinsi']);
        if (empty($type)) {
            $type = new Type([
                'name' => "Provinsi",
            ]);
            $type->save(FALSE);
            echo "New Type Inserted: Provinsi\n";
        }

        // datasource
        $filepath = Yii::getAlias('@app').'/dataseed/indonesia_provinces.json';
        $content = file_get_contents($filepath);
        $datasource = (array) json_decode($content, true);
        if (empty($datasource)) {
            echo "Datasource is empty.\n";
            return ExitCode::DATAERR;
        }
        if (isset($datasource['rows']) === FALSE) {
            echo "Rows is empty.\n";
            return ExitCode::DATAERR;
        }

        // compose batch
        $batch_list = [];
        foreach ($datasource['rows'] as $row) {
            if (isset($row['name']) == FALSE OR isset($row['number']) == FALSE) {
                continue;
            }
            $batch_list[] = [
                'name' => $row['name'],
                'type_id' => $type->id,
                'country_id' => $country->id,
                'reg_number' => $row['number'],
            ];
        }

        // insert batch
        $success = Yii::$app->db->createCommand()
            ->batchInsert(Region::tableName(), ['name', 'type_id', 'country_id', 'reg_number'], $batch_list)
            ->execute();

        echo "Operation Done: {$success} affected.\n";
        return ExitCode::OK;
    }

    /**
     * import indonesian cities
     * @return int Exit code
     */
    public function actionIdCities()
    {
        // country model
        $country = Country::findOne(['code' => 'ID']);
        if (empty($country)) {
            $country = new Country([
                'code' => "ID",
                'name' => "Indonesia",
            ]);
            $country->save(FALSE);
            echo "New Country Inserted: Indonesia\n";
        }

        // region model list
        $region_list = Region::find()
            ->where(['country_id' => $country->id])
            ->indexBy('reg_number')
            ->all();

        // type model for "City"
        $type_city = Type::findOne(['name' => 'Kota']);
        if (empty($type_city)) {
            $type_city = new Type([
                'name' => "Provinsi",
            ]);
            $type_city->save(FALSE);
            echo "New Type Inserted: Kota\n";
        }

        // type model for "Regency"
        $type_regency = Type::findOne(['name' => 'Kabupaten']);
        if (empty($type_regency)) {
            $type_regency = new Type([
                'name' => "Kabupaten",
            ]);
            $type_regency->save(FALSE);
            echo "New Type Inserted: Kabupaten\n";
        }

        // datasource
        $filepath = Yii::getAlias('@app').'/dataseed/indonesia_cities.json';
        $content = file_get_contents($filepath);
        $datasource = (array) json_decode($content, true);
        if (empty($datasource)) {
            echo "Datasource is empty.\n";
            return ExitCode::DATAERR;
        }
        if (isset($datasource['rows']) === FALSE) {
            echo "Rows is empty.\n";
            return ExitCode::DATAERR;
        }

        // compose batch
        $batch_list = [];
        foreach ($datasource['rows'] as $index => $row) {
            // skip broken data
            if (isset($row['name']) == FALSE OR isset($row['number']) == FALSE) {
                echo "Skipping: #{$index} (".print_r($row, TRUE).").\n";
                continue;
            }
            // decide region
            $region_code = substr($row['number'], 0, 2);
            if (empty($region_code)) {
                echo "Skipping: #{$index} ({$row['number']}).\n";
                continue;
            }
            // decide city/regency
            $isRegency = (strpos($row['name'], 'Kab') !== FALSE);
            $type_id = $isRegency ? $type_regency->id : $type_city->id;
            // batch item
            $batch_list[] = [
                'name' => $row['name'],
                'type_id' => $type_id,
                'country_id' => $country->id,
                'region_id' => ArrayHelper::getValue($region_list, $region_code.'.id'),
                'reg_number' => $row['number'],
            ];
        }

        // release memory
        unset($datasource);
        unset($region_list);

        // insert batch
        $success = Yii::$app->db->createCommand()
            ->batchInsert(City::tableName(), ['name', 'type_id', 'country_id', 'region_id', 'reg_number'], $batch_list)
            ->execute();

        echo "Operation Done: {$success} affected.\n";
        return ExitCode::OK;
    }

    /**
     * import indonesian district
     * @return int Exit code
     */
    public function actionIdDistricts()
    {
        // country model
        $country = Country::findOne(['code' => 'ID']);
        if (empty($country)) {
            $country = new Country([
                'code' => "ID",
                'name' => "Indonesia",
            ]);
            $country->save(FALSE);
            echo "New Country Inserted: Indonesia\n";
        }

        // region model list
        $city_list = City::find()
            ->where(['country_id' => $country->id])
            ->indexBy('reg_number')
            ->all();

        // cleanup
        unset($country);

        // type model for "District"
        $type = Type::findOne(['name' => 'Kecamatan']);
        if (empty($type)) {
            $type = new Type(['name' => "Kecamatan"]);
            $type->save(FALSE);
            echo "New Type Inserted: Kecamatan\n";
        }

        // datasource
        $filepath = Yii::getAlias('@app').'/dataseed/indonesia_districts.json';
        $content = file_get_contents($filepath);
        $source_list = (array) json_decode($content, true);
        if (empty($source_list)) {
            echo "Datasource is empty.\n";
            return ExitCode::DATAERR;
        }

        // compose batch
        foreach ($source_list as $index => $row) {
            // skip broken data
            if (isset($row['name']) == FALSE OR isset($row['reg_number']) == FALSE) {
                echo "Skipping: #{$index} (".print_r($row, TRUE).").\n";
                continue;
            }
            // decide region
            $city_code = substr($row['reg_number'], 0, 4);
            if (empty($city_code)) {
                echo "Skipping: #{$index} ({$row['reg_number']}).\n";
                continue;
            }
            // add information
            $source_list[$index]['type_id'] = $type->id;
            $source_list[$index]['city_id'] = ArrayHelper::getValue($city_list, $city_code.'.id');
        }

        // release memory
        unset($city_list);

        // insert batch
        $success = Yii::$app->db->createCommand()
            ->batchInsert(District::tableName(), ['reg_number', 'name', 'type_id', 'city_id'], $source_list)
            ->execute();

        echo "Operation Done: {$success} affected.\n";
        return ExitCode::OK;
    }

    /**
     * import indonesian subdistrict
     * @return int Exit code
     */
    public function actionIdSubdistricts()
    {
        // type model for "Kelurahan"
        $type = Type::findOne(['name' => 'Kelurahan']);
        if (empty($type)) {
            $type = new Type(['name' => "Kelurahan"]);
            $type->save(FALSE);
            echo "New Type Inserted: Kelurahan\n";
        }

        // seed file list
        $file_list = \yii\helpers\FileHelper::findFiles(
                Yii::getAlias('@app').DIRECTORY_SEPARATOR.'dataseed'
                , [
                'only' => ['indonesia_subdistricts_*.json'],
                'recursive' => false,
                ]
        );
        if (empty($file_list)) {
            echo "File not found.\n";
            return ExitCode::DATAERR;
        }

        // parse each seed
        foreach ($file_list as $filepath) {
            // datasource
            $content = file_get_contents($filepath);
            $source_list = (array) json_decode($content, true);
            if (empty($source_list)) {
                echo "File {$filepath} is empty.\n";
                continue;
            }

            // release memory
            unset($content);

            // populate district code
            $distric_code_list = [];
            foreach ($source_list as $row) {
                // invalid feed
                if (isset($row['reg_number']) === FALSE) {
                    continue;
                }
                // chop district code
                $distric_code_list[] = substr(trim($row['reg_number']), 0, 6);
            }

            // get district as refference
            $district_map = District::find()
                ->select(['id', 'reg_number'])
                ->where(['reg_number' => $distric_code_list])
                ->indexBy('reg_number')
                ->column();

            // release memory
            unset($distric_code_list);

            // inject attribute
            foreach ($source_list as &$row) {
                $distric_code = substr(trim($row['reg_number']), 0, 6);
                $row['type_id'] = $type->id;
                $row['district_id'] = ArrayHelper::getValue($district_map, $distric_code);
            }

            // release memory
            unset($district_map);

            // insert batch
            $success = Yii::$app->db->createCommand()
                ->batchInsert(Subdistrict::tableName(), ['reg_number', 'name', 'type_id', 'district_id'], $source_list)
                ->execute();

            // release memory
            unset($source_list);

            // message
            echo "Operation Done: {$success} affected.\n";
        }

        return ExitCode::OK;
    }

}