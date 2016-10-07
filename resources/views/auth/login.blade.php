@extends('layouts.master')
@section('content')
    <div class="container">
        {!! Form::open(['class' => 'form-horizontal']) !!}
        @include('widgets.form._formitem_text', ['name' => 'email', 'title' => 'Email', 'placeholder' => 'Ваш Email' ])
        @include('widgets.form._formitem_password', ['name' => 'password', 'title' => 'Пароль', 'placeholder' => 'Пароль' ])
        @include('widgets.form._formitem_checkbox', ['name' => 'remember', 'title' => 'Запомнить меня'] )
        @include('widgets.form._formitem_btn_submit', ['title' => 'Вход'])
        {!! Form::close() !!}
        <p class="col-sm-offset-2"><a href="{{URL::to('/reset')}}">Забыли пароль?</a></p>
    </div>
@stop