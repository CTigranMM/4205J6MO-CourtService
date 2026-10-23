@extends('layouts.app')

@section('title', 'Modifier la réservation #' . $reservation->id)

@section('content')
    <h2>Modifier la réservation #{{ $reservation->id }}</h2>

    <div class="content-box">
        <form method="post" action="{{ route('reservations.update', $reservation) }}">
            @method('PATCH')
            @include('reservations._form')
        </form>
    </div>
@endsection
