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


    public function getMonthName()
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


        $month = date('n')-1;
        return $arr[$month];
    }
}
