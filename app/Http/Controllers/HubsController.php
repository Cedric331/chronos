<?php

namespace App\Http\Controllers;

use App\Helpers\ControllerResponse;
use App\Http\Requests\Hub\PatchHubRequest;
use App\Models\Hub;
use Illuminate\Http\Request;

class HubsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:Coordinateur'], ['only' => ['update']]);
    }

    /**
     * @param PatchHubRequest $request
     * @param integer $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(PatchHubRequest $request, int $id): \Illuminate\Http\JsonResponse
    {
        $update = Hub::find($id)->update([
                    'departement' => $request->departement,
                    'code_postal' => $request->code_postal,
                    'adresse' => $request->adresse,
                    'complement_adresse' => $request->complement_adresse,
                    'abonne_freebox' => $request->abonne_freebox,
                    'abonne_mobile' => $request->abonne_mobile
                ]);

        return ControllerResponse::update($update);
    }
}
