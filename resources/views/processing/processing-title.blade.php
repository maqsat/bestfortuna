@if($item->status == 'invite_bonus')
    Реферальный бонус
@elseif($item->status == 'turnover_bonus')
    Структурный бонус
@elseif($item->status == 'matching_bonus')
    Кумулятивный бонус
@elseif($item->status == 'cashback')
    Бонус от Личного закупа
@elseif($item->status == 'quickstart_bonus')
    Пассивный бонус
@elseif($item->status == 'status_bonus')
    Мировой бонус



@elseif($item->status == 'request')
    Запрос на списание
@elseif($item->status == 'register')
    Регистрация
@elseif($item->status == 'out')
    Выведено
@elseif($item->status == 'cancel')
    Отменено
@elseif($item->status == 'revitalization-shop')
    Покупка с баланса(повторная)
@elseif($item->status == 'shop')
    Личный закуп
@elseif($item->status == 'upgrade')
    Апгрейд
@elseif($item->status == 'admin_add')
    {{ $item->message }}
@else
    Не определено
@endif
