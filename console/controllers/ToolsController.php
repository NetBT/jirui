<?php
namespace console\controllers;

use console\models\CalenderPlan;
use yii\console\Controller;

class ToolsController extends Controller {

   public function actionCheckPlan() {
        $model = new CalenderPlan();

       return $model->checkPlan();
   }
}