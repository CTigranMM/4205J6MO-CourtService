<?php

namespace App\Policies;

use App\Models\Reservation;
use App\Models\User;

class ReservationPolicy
{
    public function update(User $user, Reservation $reservation): bool
    {
        return $reservation->user_id === $user->id;
    }

    public function delete(User $user, Reservation $reservation): bool
    {
        return $this->update($user, $reservation);
    }
}

