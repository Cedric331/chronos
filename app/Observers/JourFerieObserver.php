<?php

namespace App\Observers;

use App\Models\CollaborateurDate;
use App\Models\Date;
use App\Models\JoursFerie;
use Illuminate\Support\Facades\Auth;

class JourFerieObserver
{
    /**
     * Handle the JoursFerie "created" event.
     *
     * @param  \App\Models\JoursFerie  $joursFerie
     * @return void
     */
    public function created(JoursFerie $joursFerie)
    {
        $dates = Date::with(['collaborateurs' => function($query) {
                  return $query->where('collaborateurs.hub_id', Auth::user()->hub_id);
                }
            ])
            ->where('date', $joursFerie->date)
            ->get();

        $horaires = [
            'debut_journee' => null,
            'debut_pause' => null,
            'fin_pause' => null,
            'fin_journee' => null,
            'teletravail' => false,
            'type' => 'F',
        ];

        if ($dates->isNotEmpty()) {
           foreach ($dates as $date) {
               if (!empty($date)) {
                   foreach ($date['collaborateurs'] as $collaborateur) {
                       CollaborateurDate::where('date_id', $collaborateur['pivot']['date_id'])
                           ->where('hub_id', Auth::user()->hub_id)
                           ->where('collaborateur_id', $collaborateur['pivot']['collaborateur_id'])
                           ->update(['horaire' => $horaires]);
                   }
               }
           }
        }
    }

    /**
     * Handle the JoursFerie "updated" event.
     *
     * @param  \App\Models\JoursFerie  $joursFerie
     * @return void
     */
    public function updated(JoursFerie $joursFerie)
    {
        //
    }

    /**
     * Handle the JoursFerie "deleted" event.
     *
     * @param  \App\Models\JoursFerie  $joursFerie
     * @return void
     */
    public function deleted(JoursFerie $joursFerie)
    {
        //
    }

    /**
     * Handle the JoursFerie "restored" event.
     *
     * @param  \App\Models\JoursFerie  $joursFerie
     * @return void
     */
    public function restored(JoursFerie $joursFerie)
    {
        //
    }

    /**
     * Handle the JoursFerie "force deleted" event.
     *
     * @param  \App\Models\JoursFerie  $joursFerie
     * @return void
     */
    public function forceDeleted(JoursFerie $joursFerie)
    {
        //
    }
}
