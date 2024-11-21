<?php

namespace App\Controllers;

class FahrzeugUebergabe extends BaseController
{
    public function index()
    {
		echo view('templates/header');
		echo view('templates/navigation');
        echo view('fahrzeug_uebergabe_view');
		echo view('templates/footer');
    }
}
