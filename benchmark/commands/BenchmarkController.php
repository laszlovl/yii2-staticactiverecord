<?php

namespace app\commands;

use app\models\Animal;
use app\models\StaticAnimal;
use yii\db\ActiveRecord;
use Yii;

class BenchmarkController extends \yii\console\Controller
{
    const REPEAT = 25;

    public function actionIndex()
    {
        $this->createDatabase();

        $this->warm();

        return $this->benchmarks();
    }
    
    private function benchmarks()
    {
        $this->benchmark('benchmarkGetProperty');

        $this->benchmark('benchmarkSetProperty');

        $this->benchmark('benchmarkValidate');
    }

    private function benchmark($function)
    {
        $regular = Animal::class;
        $static = StaticAnimal::class;

        $callable = [$this, $function];

        $regularDuration = $this->runBenchmark($callable, [$regular]);
        $staticDuration = $this->runBenchmark($callable, [$static]);

        $improvement = self::improvement($regularDuration, $staticDuration);

        $this->stdout("Benchmarking $function...\n"
        . "Regular ActiveRecord: $regularDuration\n"
        . "Static ActiveRecord:  $staticDuration\n"
        . "Improvement:          $improvement\n\n");
    }

    private function runBenchmark(callable $callable, array $args)
    {
        $durations = [];

        for ($i = 0; $i < self::REPEAT; $i++) {
            $start = microtime(true);

            call_user_func_array($callable, $args);

            $durations[] = microtime(true) - $start;
        }

        $duration = array_sum($durations) / count($durations);
        
        return $duration;
    }
    
    private function benchmarkGetProperty($class)
    {
        foreach ($class::find()->all() as $model) {
            $temp = $model->name;
        }
    }

    private function benchmarkSetProperty($class)
    {
        foreach ($class::find()->all() as $model) {
            $model->name = 'testing';
        }
    }

    private function benchmarkValidate($class)
    {
        foreach ($class::find()->all() as $model) {
            $model->validate();
        }
    }

    private static function improvement($old, $new)
    {
        return -(floor(($new - $old) / $old * 100)) . '%';
    }

    private function warm()
    {
        Animal::find()->one()->name;
        StaticAnimal::find()->one()->name;
    }

    private function createDatabase()
    {
        $db = Yii::$app->db;

        if ($db->getTableSchema('animal') === null) {
            $db->createCommand()->createTable('animal', [
                'id'    => 'pk',
                'name'  => 'string'
            ])->execute();

            // sqlite: "too many terms in compound SELECT"
            for ($i = 0; $i < 2; $i++) {
                $rows = [];
                for ($j = 0; $j < 500; $j++) {
                    $rows[] = ['test'];
                }

                $db->createCommand()->batchInsert('animal', ['name'], $rows)->execute();
            }

            // yii's internal schema caching; this has nothing to do with our changes
            $db->getTableSchema('animal', true);
        }
    }
}
