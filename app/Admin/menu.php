<?php

Admin::menu()->url('/')->label('Start page')->icon('fa-dashboard');

Admin::menu(App\Apartment::class)->label('Объекты')->icon('fa-plus');

Admin::menu()->label('Пользователи')->icon('fa-users')->items(function ()
{
    Admin::menu(App\Permit::class)->label('Права')->icon('fa-key');
    Admin::menu(App\Role::class)->label('Роли')->icon('fa-graduation-cap');
    Admin::menu(App\User::class)->label('Юзеры')->icon('fa-user');
});