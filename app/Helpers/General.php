<?php

namespace App\Helpers;


class General {


    public function getMonthNameById($id)
    {
        $arr = [
            'январь',
            'февраль',
            'март',
            'апрель',
            'май',
            'июнь',
            'июль',
            'август',
            'сентябрь',
            'октябрь',
            'ноябрь',
            'декабрь'
        ];

        return $arr[$id];
    }
}
