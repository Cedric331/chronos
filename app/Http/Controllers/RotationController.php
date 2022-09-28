<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class RotationController extends Controller
{

    public function __construct()
    {
        $this->middleware('role:Coordinateur|Administrateur|Responsable');
    }

    /**
     * @return \Inertia\Response
     */
    public function index (): \Inertia\Response
    {
        return Inertia::render('Rotation', [
            'rotations' => ''
        ]);
    }
}
