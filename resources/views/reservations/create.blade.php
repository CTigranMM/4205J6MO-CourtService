@extends('layouts.app')

@section('title', 'Créer une réservation')

@section('content')
    <h2>Créer une réservation</h2>

    <div class="content-box">
        <form method="post" action="{{ route('reservations.store') }}" enctype="multipart/form-data">
            @include('reservations._form', ['reservation' => null])
        </form>
    </div>
@endsection
