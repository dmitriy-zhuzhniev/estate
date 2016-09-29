<?php
/**
 * Created by PhpStorm.
 * User: itdev13
 * Date: 29.09.16
 * Time: 15:53
 */
@extends('layouts.master')
@section('body')
    {!! Form::open() !!}
    @include('widgets.form._formitem_text', ['name' => 'email', 'title' => 'Email', 'placeholder' => 'Ваш Email' ])
    @include('widgets.form._formitem_btn_submit', ['title' => 'Сброс пароля'])
    {!! Form::close() !!}
@stop