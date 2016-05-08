@extends('layouts.app')

@section('content')

    <h1>Please select your country</h1>

    <form method="POST">
        {{ csrf_field() }}

        <div class="row">
            @foreach ($countries as $countriesChunk)
                <div class="col-xs-6 col-sm-3">
                    @foreach ($countriesChunk as $country)
                            {{-- <div class="list-group">

                            </div> --}}


                        <div class="radio">
                            <label>
                                <input type="radio" name="countries" id="country-{{ $country->id }}" value="{{ $country->id }}">
                                <span class="flag-icon flag-icon-{{ strtolower($country->iso_code) }}"></span>
                                {{ $country->name }}
                            </label>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </form>


@endsection
