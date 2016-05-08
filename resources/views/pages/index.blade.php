@extends('layouts.app')

@section('content')

    <div class="page-header">
        <h1>Phone number for a country "{{ $country->name }}"</h1>
    </div>

    @if ($phonenumbers->isEmpty())
        <div class="alert alert-info" role="alert">
            Unfortunatelly we don't have any numbers in {{ $country->name }} yet.
            But we can buy some.
        </div>

        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="twilio/buy" method="post">
            {{ csrf_field() }}

            <input type="hidden" name="country_code" value="{{ $country->iso_code }}">
            <input type="hidden" name="country_id" value="{{ $country->id }}">
            <button type="submit" class="btn btn-primary">Buy number</button>
        </form>
    @else
        <div class="list-group">
            @foreach ($phonenumbers as $phonenumber)
                <li class="list-group-item">{{ $phonenumber->number }}</li>
            @endforeach
        </div>
    @endif

@endsection
