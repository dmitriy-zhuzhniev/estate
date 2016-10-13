@extends('layouts.master')

@section('title', 'Add apartment')

@section('content')
    <div class="container">
        {!! Form::open(['url' => url('/')]) !!}
            @include('widgets.form._formitem_text', ['name' => 'title', 'title' => 'Title*', 'placeholder' => 'Title' ])
            @include('widgets.form._formitem_select', ['name' => 'type', 'title' => 'Type*', 'options' => \App\Apartment::getTypes() ])
            @include('widgets.form._formitem_text', ['name' => 'realty_id', 'title' => 'Realty ID*', 'placeholder' => 'Realty ID' ])
            @include('widgets.form._formitem_text', ['name' => 'customer', 'title' => 'Customer*', 'placeholder' => 'Customer' ])
            @include('widgets.form._formitem_text', ['name' => 'owner', 'title' => 'Owner*', 'placeholder' => 'Owner' ])
            @include('widgets.form._formitem_text', ['name' => 'agreement_id', 'title' => 'Agreement ID*', 'placeholder' => 'Agreement ID' ])
            @include('widgets.form._formitem_text', ['name' => 'realty_goal', 'title' => 'Realty goal*', 'placeholder' => 'Realty goal' ])
            @include('widgets.form._formitem_select', ['name' => 'region', 'title' => 'Region*', 'options' => $regions ])
            @include('widgets.form._formitem_select', ['name' => 'city', 'title' => 'City*', 'options' => [] ])
            @include('widgets.form._formitem_select', ['name' => 'district', 'title' => 'District', 'options' => [], 'hidden' => true ])
            @include('widgets.form._formitem_text', ['name' => 'street', 'title' => 'Street', 'placeholder' => 'Street' ])
            @include('widgets.form._formitem_text', ['name' => 'house_number', 'title' => 'House number*', 'placeholder' => 'House number' ])
            @include('widgets.form._formitem_text', ['name' => 'apartment_number', 'title' => 'Apartment number*', 'placeholder' => 'Apartment number' ])
            @include('widgets.form._formitem_text', ['name' => 'square', 'title' => 'Square*', 'placeholder' => 'Square' ])
            @include('widgets.form._formitem_text', ['name' => 'floor', 'title' => 'Floor*', 'placeholder' => 'Floor' ])
            @include('widgets.form._formitem_text', ['name' => 'total_floor', 'title' => 'Total floor*', 'placeholder' => 'Max floor' ])
            @include('widgets.form._formitem_text', ['name' => 'rooms', 'title' => 'Rooms*', 'placeholder' => 'Rooms' ])
            @include('widgets.form._formitem_btn_submit', ['title' => 'Search'])
        {!! Form::close() !!}
    </div>
@endsection

@section('script')
    <script>
        function setStreetAutocomplete(_city_id) {
            $('[name=street]').typeahead({
                ajax: {
                    url: 'streets?city_id=' + _city_id,
                    displayField: 'name',
                    triggerLength: 1,
                    timeout: 500,
                    method: "get"
                }
            });
        }

        $(document).ready(function() {
            $(document).on('change', '[name=region]', function() {
                var _obj = $(this);
                $.ajax({
                    url: '{{ route('json.cities') }}',
                    data: {region_id: _obj.val()},
                    dataType: 'JSON',
                    success: function(_data) {
                        var _cities = $('[name=city]');
                        _cities.html('');
                        $.each(_data, function(_id, _city) {
                            _cities.append('<option value="'+_city.id+'">'+_city.name+'</option>');
                        });
                    }
                });
            });
            $('[name=region]').trigger('change');

            $(document).on('change', '[name=city]', function() {
                var _obj = $(this);
                $.ajax({
                    url: '{{ route('json.districts') }}',
                    data: {city_id: _obj.val()},
                    dataType: 'JSON',
                    success: function(_data) {
                        setStreetAutocomplete(_obj.val());
                        var _districts = $('[name=district]');
                        if (_data.length > 0) {
                            _districts.html('<option></option>');
                            $.each(_data, function (_id, _district) {
                                _districts.append('<option value="' + _district.id + '">' + _district.name + '</option>');
                            });
                            _districts.parents('.form-group').fadeIn().removeClass('hidden');
                        } else {
                            _districts.parents('.form-group').fadeOut().addClass('hidden');
                        }
                    }
                });
            });
            $('[name=form-group]').trigger('change');


        });
    </script>
@endsection