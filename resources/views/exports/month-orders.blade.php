@php

    $date = new \DateTime();
    $date->modify('-1 month');

    $users = \App\User::join('activations', 'users.id', '=', 'activations.user_id')
    ->where('activations.month',\Carbon\Carbon::parse($date)->month)
    ->where('activations.status',1)
    ->get(['users.*']);

    $users = \App\User::all();


    $general_invite_bonus = 0;
    $general_turnover_bonus = 0;
    $general_matching_bonus = 0;
    $general_quickstart_bonus = 0;
    $general_cashback = 0;
    $general_status_bonus = 0;
    $general_orderSumOfMonth = 0;


@endphp

<table>
    <thead>
    <tr>
        <th>Контракт</th>
        <th>ФИО</th>
        <th>Дата регистрации</th>
        <th>Спонсор</th>
        <th>Склад</th>
        <th>Статус</th>
        <th>Активация</th>
        <th>Общий ТО</th>
        <th>Бонус за рекомендации</th>
        <th>Кешбек(20% от ЛЗ)</th>
        <th>Структурный бонус</th>
        <th>Пассивный бонус</th>
        <th>Кумулятивный бонус</th>
        <th>Мировой бонус</th>
    </tr>
    </thead>
    <tbody>
    @foreach($users as $key => $item)
        @php
            $invite_bonus = \App\Facades\Report::getIncomePreviousMonthBalanceByStatus($item->id,'invite_bonus');
            $general_invite_bonus += $invite_bonus;

            $turnover_bonus = \App\Facades\Report::getIncomePreviousMonthBalanceByStatus($item->id,'turnover_bonus');
            $general_turnover_bonus += $turnover_bonus;

            $matching_bonus = \App\Facades\Report::getIncomePreviousMonthBalanceByStatus($item->id,'matching_bonus');
            $general_matching_bonus += $matching_bonus;

            $quickstart_bonus= \App\Facades\Report::getIncomePreviousMonthBalanceByStatus($item->id,'quickstart_bonus');
            $general_quickstart_bonus += $quickstart_bonus;

            $cashback = \App\Facades\Report::getIncomePreviousMonthBalanceByStatus($item->id,'cashback');
            $general_cashback += $cashback;

            $status_bonus = \App\Facades\Report::getIncomePreviousMonthBalanceByStatus($item->id,'status_bonus');
            $general_status_bonus += $status_bonus;

            $orderSumOfMonth = \App\Facades\Report::getOrderSumOfMonth($item->id);
            $general_orderSumOfMonth += $orderSumOfMonth;

            $total = $invite_bonus + $turnover_bonus +  $matching_bonus + $quickstart_bonus + $cashback + $status_bonus+$orderSumOfMonth;
        @endphp

        @if($total > 0)
        <tr>
            <td>{{ $item->id_number }}</td>
            <td>{{ $item->name }}</td>
            <td>{{ $item->created_at }}</td>
            <td>
                @php
                    $inviter = \App\User::find($item->inviter_id);
                @endphp
                @if(!is_null($inviter)){{ $inviter->id_number }} @else нет спонсора @endif
            </td>
            <td>
                @php
                    $office =  \App\Models\Office::find($item->office_id);
                @endphp
                @if(!is_null($office)){{ $office->title }} @else нет склада @endif
            </td>
            <td>
                @php $user_program = \App\Models\UserProgram::whereUserId($item->id)->first(); @endphp
                {{ \App\Models\Status::find($user_program->status_id)->title }}
            </td>
            <td>
                @if(\App\Facades\Hierarchy::checkIsActive($item->id)) Активация  @endif
            </td>
            <td>
                {{ $orderSumOfMonth }}
            </td>
            <td>{{ $invite_bonus }}</td>
            <td>{{ $cashback }}</td>
            <td>{{ $turnover_bonus }}</td>
            <td>{{ $quickstart_bonus }}</td>
            <td>{{ $matching_bonus }}</td>
            <td>{{ $status_bonus }}</td>
        </tr>
        @endif
    @endforeach
    </tbody>
    <tr>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th>{{ $general_orderSumOfMonth }}</th>
        <th>{{ $general_invite_bonus }}</th>
        <th>{{ $general_cashback }}</th>
        <th>{{ $general_turnover_bonus }}</th>
        <th>{{ $general_quickstart_bonus }}</th>
        <th>{{ $general_matching_bonus }}</th>
        <th>{{ $general_status_bonus }}</th>
    </tr>
</table>
