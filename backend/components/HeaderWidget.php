<?php
namespace backend\components;


class HeaderWidget extends CommonWidget
{
    public function run()
    {
        return $this->render('header');
    }
}