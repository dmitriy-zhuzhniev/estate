@extends('layouts.master')

@section('title', 'Apartments')

@section('content')
    <div class="container">
        <table class="table">
            <caption>Ваши проекты</caption>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>title</th>
                    <th>type</th>
                    <th>realty_id</th>
                    <th>customer</th>
                    <th>owner</th>
                    <th>agreement_id</th>
                    <th>realty_goal</th>
                    <th>region</th>
                    <th>city</th>
                    <th>house_number</th>
                    <th>apartment_number</th>
                    <th>square</th>
                    <th>floor</th>
                    <th>total_floor</th>
                    <th>rooms</th>
                    <th>user_id</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($apartments as $apartment)
                    <tr>
                        <th scope="row">{{ $apartment->id }}</th>
                        <td>{{ $apartment->title }}</td>
                        <td>{{ $apartment->type }}</td>
                        <td>{{ $apartment->realty_id }}</td>
                        <td>{{ $apartment->customer }}</td>
                        <td>{{ $apartment->owner }}</td>
                        <td>{{ $apartment->agreement_id }}</td>
                        <td>{{ $apartment->realty_goal }}</td>
                        <td>{{ $apartment->region }}</td>
                        <td>{{ $apartment->city }}</td>
                        <td>{{ $apartment->house_number }}</td>
                        <td>{{ $apartment->apartment_number }}</td>
                        <td>{{ $apartment->square }}</td>
                        <td>{{ $apartment->floor }}</td>
                        <td>{{ $apartment->total_floor }}</td>
                        <td>{{ $apartment->rooms }}</td>
                        <td>{{ $apartment->user_id }}</td>
                        <td><a href="/{{ $apartment->id }}/parse">parse</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection