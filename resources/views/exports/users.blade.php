<?php
    $date = new \DateTime();
    //$date->modify('-1 month');
?>
<table>
    <thead>
    <tr>
        <th>Контракт</th>
        <th>ФИО</th>
        <th>Спонсор</th>
        <th>Статус</th>
        <th>Активация</th>
        <th>Накопительный PV</th>
        <th>Месяц({{ \App\Facades\General::getMonthNameById($date->format('n')) }})</th>
    </tr>
    </thead>
    <tbody>
    @foreach($users as $user)
        <tr>
            <td>{{ $user->id_number }}</td>
            <td>{{ $user->name }}</td>
            <td>
                @php $sponsor = \App\User::whereId($user->inviter_id)->first(); @endphp
                @if(!is_null($sponsor)) {{ $sponsor->id_number }}@endif
            </td>
            <td>
                @php $user_program = \App\Models\UserProgram::whereUserId($user->id)->first(); @endphp
                {{ \App\Models\Status::find($user_program->status_id)->title }}
            </td>
            <td>
                @if(\App\Facades\Hierarchy::checkIsActive($user->id)) Активация  @endif
            </td>
            <td>{{ \App\Facades\Balance::getIncomeBalance($user->id) }}</td>
            <td>{{ \App\Facades\Balance::getBalance($user->id) }}</td>
        </tr>
@endforeach
</tbody>
</table>
