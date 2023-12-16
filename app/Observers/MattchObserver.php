<?php

namespace App\Observers;

use App\Models\Mattch;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class MattchObserver
{
    public function creating(Mattch $mattch): void
    {
        /**
         * @var \App\Models\Skhedule|null $skhedule
         */
        $skhedule = $mattch->tournament->skhedules()->whereDate('date', $mattch->date)->first();

        if (!$skhedule) {
            abort(
                HttpResponse::HTTP_UNPROCESSABLE_ENTITY,
                'Tournament schedule for: '.$mattch->date->format('Y-m-d').' could not be found.'
            );
        }
    }

    public function created(Mattch $mattch): void
    {
        /**
         * @var \App\Models\Skhedule|null $skhedule
         */
        $skhedule = $mattch->tournament->skhedules()->whereDate('date', $mattch->date)->first();

        // Unecessary check since it's already handled in "creating" hook, but just include for sanity check
        if (!$skhedule) {
            $mattch->delete();
            abort(
                HttpResponse::HTTP_UNPROCESSABLE_ENTITY,
                'Dirty match schedule found. Removed match: ('.$mattch->id.').'
            );
        }

        \DB::table('mattch_skhedule')->insert([
            'skhedule_id' => $skhedule->id,
            'mattch_id'   => $mattch->id,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
    }
}
