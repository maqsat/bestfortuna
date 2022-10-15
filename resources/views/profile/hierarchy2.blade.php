<!DOCTYPE html>
<html lang="en" >
<head>
    <meta charset="UTF-8">
    <title>CodePen - User Tree View</title>
    <link rel="stylesheet" href="/user-tree-view/dist/style.css">

</head>
<body>
<!-- partial:index.partial.html -->
<div class="body user-body user-scroll">
    <div class="user-tree">
        <ul>
            <li>
                <a href="javascript:void(0);">
                    <div class="member-view-box">
                        <div class="member-image">
                            <img src="{{$user->photo}}" alt="" class="bg-red">
                        </div>
                        <div class="member-details">
                            <h4>{{ $user->name }}</h4>
                            <p><i>Мастер третьего класса</i></p>
                            <p>id: {{ $user->id_number }} | Личники: {{ \App\Facades\Hierarchy::inviterCount($user->id) }} | Все партнеры: {{ \App\Facades\Hierarchy::teamCount($user->id) }}</p>
                        </div>
                    </div>
                </a>
                <ul class="active">
                    @foreach($invitations as $item)
                    <li>
                        <a href="javascript:void(0);">
                            <div class="member-view-box">
                                <div class="member-image">
                                    <img src="{{$item->photo}}" alt=" " class="bg-orange">
                                </div>
                                <div class="member-details">
                                    <h4>{{ $item->name }}</h4>
                                    <p><i>id: {{ $item->id_number }} | Мастер третьего класса</i></p>
                                    <p>Личники: {{ \App\Facades\Hierarchy::inviterCount($item->id) }} | Все партнеры: {{ \App\Facades\Hierarchy::teamCount($item->id) }}</p>
                                </div>
                            </div>
                        </a>
                        <ul class="">
                            <li>
                                <a href="javascript:void(0);">
                                    <div class="member-view-box">
                                        <div class="member-image">
                                            <img src="" alt=" " class="bg-blue">
                                            <div class="member-details">
                                                <h3>Kishan Sunar</h3>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <ul>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="member-view-box">
                                                <div class="member-image">
                                                    <img src="" alt=" ">
                                                    <div class="member-details">
                                                        <h3>Kishan Sunar</h3>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="member-view-box">
                                                <div class="member-image">
                                                    <img src="" alt=" ">
                                                    <div class="member-details">
                                                        <h3>Kishan Sunar</h3>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="member-view-box">
                                                <div class="member-image">
                                                    <img src="" alt=" ">
                                                    <div class="member-details">
                                                        <h3>Kishan Sunar</h3>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="javascript:void(0);">
                                    <div class="member-view-box">
                                        <div class="member-image">
                                            <img src="" alt=" " class="bg-blue">
                                            <div class="member-details">
                                                <h3>Kishan Sunar</h3>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <ul>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="member-view-box">
                                                <div class="member-image">
                                                    <img src="" alt=" ">
                                                    <div class="member-details">
                                                        <h3>Kishan Sunar</h3>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="member-view-box">
                                                <div class="member-image">
                                                    <img src="" alt=" ">
                                                    <div class="member-details">
                                                        <h3>Kishan Sunar</h3>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="member-view-box">
                                                <div class="member-image">
                                                    <img src="" alt=" ">
                                                    <div class="member-details">
                                                        <h3>Kishan Sunar</h3>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="javascript:void(0);">
                                    <div class="member-view-box">
                                        <div class="member-image">
                                            <img src="" alt=" " class="bg-blue">
                                            <div class="member-details">
                                                <h3>Kishan Sunar</h3>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <ul>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="member-view-box">
                                                <div class="member-image">
                                                    <img src="" alt=" ">
                                                    <div class="member-details">
                                                        <h3>Kishan Sunar</h3>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="member-view-box">
                                                <div class="member-image">
                                                    <img src="" alt=" ">
                                                    <div class="member-details">
                                                        <h3>Kishan Sunar</h3>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="member-view-box">
                                                <div class="member-image">
                                                    <img src="" alt=" ">
                                                    <div class="member-details">
                                                        <h3>Kishan Sunar</h3>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    @endforeach
                </ul>
            </li>
        </ul>
    </div>
</div>
<!-- partial -->
<script src='https://code.jquery.com/jquery-3.4.1.min.js'></script>
<script  src="/user-tree-view/dist/script.js"></script>

</body>
</html>
