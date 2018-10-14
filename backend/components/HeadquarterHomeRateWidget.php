<?php
namespace backend\components;


class HeadquarterHomeRateWidget extends CommonWidget
{
    public function run()
    {
        return $this->render('headquarter_home_rate');
    }
}