@if($item->status == 'invite_bonus')
    Реферальный бонус
@elseif($item->status == 'turnover_bonus')
    Структурный бонус
@elseif($item->status == 'matching_bonus')
    Кумулятивный бонус



@elseif($item->status == 'cashback')
    Кэшбек
@elseif($item->status == 'status_bonus')
    Бонус признания
@elseif($item->status == 'quickstart_bonus')
    Быстрый старт
@elseif($item->status == 'request')
    Запрос на списание
@elseif($item->status == 'register')
    <span style="color: red">Регистрация</span>
@elseif($item->status == 'out')
    Выведено
@elseif($item->status == 'cancel')
    Отменено
@elseif($item->status == 'revitalization')
    Автоматическая покупка
@elseif($item->status == 'revitalization-shop')
    Покупка с баланса(повторная)
@elseif($item->status == 'shop')
    Покупка с карты(повторная)
@elseif($item->status == 'upgrade')
    Апгрейд
@elseif($item->status == 'admin_add')
    {{ $item->message }}
@else
    Не определено
@endif
