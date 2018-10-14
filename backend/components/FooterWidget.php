<?php
namespace backend\components;


class FooterWidget extends CommonWidget
{
    public function run()
    {
        return $this->render('footer');
    }
}