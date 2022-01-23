<?php

namespace App\Helpers;

class ControllerResponse
{
    /**
     * @param $update
     * @return \Illuminate\Http\JsonResponse
     */
    static function update($update): \Illuminate\Http\JsonResponse
    {
        if ($update) {
            return response()->json(true);
        } else {
            return response()->json(['error' => 'Erreur lors de la mise à jour des information'], 400);
        }
    }
}
