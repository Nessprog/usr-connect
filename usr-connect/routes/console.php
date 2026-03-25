<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\Slot;
use App\Notifications\MissionReminder;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Cette fonction va tourner en arrière-plan
Schedule::call(function () {
    // On récupère les créneaux qui commencent dans pile 2 heures (marge de 10 min)
    $slots = Slot::whereBetween('start_time', [
        now()->addHours(2),
        now()->addHours(2)->addMinutes(10)
    ])->get();

    foreach ($slots as $slot) {
        // On envoie le mail à tous les utilisateurs inscrits à ce créneau
        foreach ($slot->users as $user) {
            $user->notify(new MissionReminder($slot));
        }
    }
})->everyMinute();
