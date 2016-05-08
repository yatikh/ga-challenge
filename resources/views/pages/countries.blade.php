@extends('layouts.app')

@section('content')

    <div class="page-header">
        <h1>Please select your country <small>choose one</small></h1>
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

    <div class="row">
        @foreach ($countries as $countriesChunk)
            <div class="col-xs-6 col-sm-3">
                <div class="list-group">
                    @foreach ($countriesChunk as $country)
                        <div class="list-group-item">
                            <form action="/countries" method="post">
                                {{ csrf_field() }}
                                <input type="hidden" name="country_iso" value="{{ $country['iso'] }}">
                                <input type="hidden" name="country_name" value="{{ $country['name'] }}">
                                <button type="submit" class="btn btn-link">
                                    <span class="flag-icon flag-icon-{{ strtolower($country['iso']) }}"></span>
                                    {{ $country['name'] }}
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

@endsection
