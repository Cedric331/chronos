<?php

namespace App\Helpers;

class ControllerResponse
{
    /**
     * @param $store
     * @return \Illuminate\Http\JsonResponse
     */
    static function store($store): \Illuminate\Http\JsonResponse
    {
        if ($store) {
            return response()->json($store);
        } else {
            return response()->json(['error' => 'Erreur lors de la création'], 400);
        }
    }

    /**
     * @param $update
     * @return \Illuminate\Http\JsonResponse
     */
    static function update($update, $data = null, $return = false): \Illuminate\Http\JsonResponse
    {
        if ($update) {
            if ($return) {
                return response()->json($data);
            } else {
                return response()->json(true);
            }
        } else {
            return response()->json(['error' => 'Erreur lors de la mise à jour'], 400);
        }
    }

    /**
     * @param $delete
     * @return \Illuminate\Http\JsonResponse
     */
    static function delete($delete): \Illuminate\Http\JsonResponse
    {
        if ($delete) {
            return response()->json(true);
        } else {
            return response()->json(['error' => 'Erreur lors de la suppression'], 400);
        }
    }
}
