<?php

namespace App\Http\Requests\Rotation;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\RequiredIf;

class StoreRotationRequest extends FormRequest
{
    /**
     * Indicates if the validator should stop on the first rule failure.
     *
     * @var bool
     */
    protected $stopOnFirstFailure = true;


    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->isAdmin() || $this->user()->isCoordinateur() || $this->user()->isResponsable();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'type' => 'required|string|max:3',
            'jours' => 'required',

            'jours.lundi' => 'required',
            'jours.lundi.debut_journee' => new RequiredIf($this->jours['lundi']['fin_journee'] !== null || $this->jours['lundi']['is_off'] === false),
            'jours.lundi.fin_journee' => new RequiredIf($this->jours['lundi']['debut_journee'] !== null),
            'jours.lundi.debut_pause' =>  new RequiredIf($this->jours['lundi']['fin_pause'] !== null),
            'jours.lundi.fin_pause' => new RequiredIf($this->jours['lundi']['debut_pause'] !== null),

            'jours.mardi' => 'required',
            'jours.mardi.debut_journee' => new RequiredIf($this->jours['mardi']['fin_journee'] !== null || $this->jours['mardi']['is_off'] === false),
            'jours.mardi.debut_journee' => new RequiredIf($this->jours['mardi']['fin_journee'] !== null),
            'jours.mardi.fin_journee' => new RequiredIf($this->jours['mardi']['debut_journee'] !== null),
            'jours.mardi.debut_pause' =>  new RequiredIf($this->jours['mardi']['fin_pause'] !== null),
            'jours.mardi.fin_pause' => new RequiredIf($this->jours['mardi']['debut_pause'] !== null),

            'jours.mercredi' => 'required',
            'jours.mercredi.debut_journee' => new RequiredIf($this->jours['mercredi']['fin_journee'] !== null || $this->jours['mercredi']['is_off'] === false),
            'jours.mercredi.debut_journee' => new RequiredIf($this->jours['mercredi']['fin_journee'] !== null),
            'jours.mercredi.fin_journee' => new RequiredIf($this->jours['mercredi']['debut_journee'] !== null),
            'jours.mercredi.debut_pause' =>  new RequiredIf($this->jours['mercredi']['fin_pause'] !== null),
            'jours.mercredi.fin_pause' => new RequiredIf($this->jours['mercredi']['debut_pause'] !== null),

            'jours.jeudi' => 'required',
            'jours.jeudi.debut_journee' => new RequiredIf($this->jours['jeudi']['fin_journee'] !== null || $this->jours['jeudi']['is_off'] === false),
            'jours.jeudi.fin_journee' => new RequiredIf($this->jours['jeudi']['debut_journee'] !== null),
            'jours.jeudi.debut_pause' =>  new RequiredIf($this->jours['jeudi']['fin_pause'] !== null),
            'jours.jeudi.fin_pause' => new RequiredIf($this->jours['jeudi']['debut_pause'] !== null),

            'jours.vendredi' => 'required',
            'jours.vendredi.debut_journee' => new RequiredIf($this->jours['vendredi']['fin_journee'] !== null || $this->jours['vendredi']['is_off'] === false),
            'jours.vendredi.fin_journee' => new RequiredIf($this->jours['vendredi']['debut_journee'] !== null),
            'jours.vendredi.debut_pause' =>  new RequiredIf($this->jours['vendredi']['fin_pause'] !== null),
            'jours.vendredi.fin_pause' => new RequiredIf($this->jours['vendredi']['debut_pause'] !== null),

            'jours.samedi' => 'required',
            'jours.samedi.debut_journee' => new RequiredIf($this->jours['samedi']['fin_journee'] !== null || $this->jours['samedi']['is_off'] === false),
            'jours.samedi.fin_journee' => new RequiredIf($this->jours['samedi']['debut_journee'] !== null),
            'jours.samedi.debut_pause' =>  new RequiredIf($this->jours['samedi']['fin_pause'] !== null),
            'jours.samedi.fin_pause' => new RequiredIf($this->jours['samedi']['debut_pause'] !== null),

            'jours.dimanche' => 'required',
            'jours.dimanche.debut_journee' => new RequiredIf($this->jours['dimanche']['fin_journee'] !== null || $this->jours['dimanche']['is_off'] === false),
            'jours.dimanche.fin_journee' => new RequiredIf($this->jours['dimanche']['debut_journee'] !== null),
            'jours.dimanche.debut_pause' =>  new RequiredIf($this->jours['dimanche']['fin_pause'] !== null),
            'jours.dimanche.fin_pause' => new RequiredIf($this->jours['dimanche']['debut_pause'] !== null),
        ];
    }
}
