@extends('layouts.master')

@section('title', 'Add apartment')

@section('content')
    <div class="container">
        {!! Form::open(['url' => url('/')]) !!}
            @include('widgets.form._formitem_text', ['name' => 'title', 'title' => 'Title', 'placeholder' => 'Title' ])
            @include('widgets.form._formitem_select', ['name' => 'type', 'title' => 'Type', 'options' => ['apartment', 'house', 'parcel', 'garage'] ])
            @include('widgets.form._formitem_text', ['name' => 'realty_id', 'title' => 'Realty ID', 'placeholder' => 'Realty ID' ])
            @include('widgets.form._formitem_text', ['name' => 'customer', 'title' => 'Customer', 'placeholder' => 'Customer' ])
            @include('widgets.form._formitem_text', ['name' => 'owner', 'title' => 'Owner', 'placeholder' => 'Owner' ])
            @include('widgets.form._formitem_text', ['name' => 'agreement_id', 'title' => 'Agreement ID', 'placeholder' => 'Agreement ID' ])
            @include('widgets.form._formitem_text', ['name' => 'realty_goal', 'title' => 'Realty goal', 'placeholder' => 'Realty goal' ])
            @include('widgets.form._formitem_select', ['name' => 'region', 'title' => 'Region', 'options' => [0 => 'Kyivska', 1 => 'Kharkivska'] ])
            @include('widgets.form._formitem_select', ['name' => 'city', 'title' => 'City', 'options' => [0 => 'Kyiv', 1 => 'Kharkiv'] ])
            @include('widgets.form._formitem_text', ['name' => 'house_number', 'title' => 'House number', 'placeholder' => 'House number' ])
            @include('widgets.form._formitem_text', ['name' => 'apartment_number', 'title' => 'Apartment number', 'placeholder' => 'Apartment number' ])
            @include('widgets.form._formitem_text', ['name' => 'square', 'title' => 'Square', 'placeholder' => 'Square' ])
            @include('widgets.form._formitem_text', ['name' => 'floor', 'title' => 'Floor', 'placeholder' => 'Floor' ])
            @include('widgets.form._formitem_text', ['name' => 'total_floor', 'title' => 'Total floor', 'placeholder' => 'Max floor' ])
            @include('widgets.form._formitem_text', ['name' => 'rooms', 'title' => 'Rooms', 'placeholder' => 'Rooms' ])
            @include('widgets.form._formitem_btn_submit', ['title' => 'Search'])
        {!! Form::close() !!}
    </div>
@endsection