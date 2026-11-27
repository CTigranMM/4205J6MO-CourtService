<?php

namespace App\Policies;

use App\Models\Reservation;
use App\Models\User;

class ReservationPolicy
{
    public function update(User $user, Reservation $reservation): bool
    {
        if (in_array($user->role, [User::ROLE_MANAGER, 'admin'], true)) {
            return true;
        }

        return $reservation->user_id === $user->id
            && $reservation->estModifiableParMembre();
    }

    public function delete(User $user, Reservation $reservation): bool
    {
        return $this->update($user, $reservation);
    }
}

