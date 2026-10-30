@extends('layouts.app')

@section('title', 'Modifier la réservation #' . $reservation->id)

@section('content')
    <h2>{{ __('Modifier la réservation #:id', ['id' => $reservation->id]) }}</h2>

    <div class="content-box">
        @if ($reservation->document_path)
            <form id="destroy-document-form" method="post" action="{{ route('reservations.document.destroy', $reservation) }}" style="display: none;">
                @csrf
                @method('DELETE')
            </form>
        @endif

        <form method="post" action="{{ route('reservations.update', $reservation) }}" enctype="multipart/form-data">
            @method('PATCH')
            @include('reservations._form')
        </form>
    </div>
@endsection
