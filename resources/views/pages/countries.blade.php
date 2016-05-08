@extends('layouts.app')

@section('content')

    <div class="page-header">
        <h1>Please select your country <small>choose one</small></h1>
    </div>

    <div class="row">
        @foreach ($countries as $countriesChunk)
            <div class="col-xs-6 col-sm-3">
                <div class="list-group">
                    @foreach ($countriesChunk as $country)
                        <div class="list-group-item">
                            <form method="post">
                                {{ csrf_field() }}
                                <input type="hidden" name="country" value="{{ $country->id }}">
                                <button type="submit" class="btn btn-link">
                                    <span class="flag-icon flag-icon-{{ strtolower($country->iso_code) }}"></span>
                                    {{ $country->name }}
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

@endsection
