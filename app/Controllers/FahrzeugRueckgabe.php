<?php

namespace App\Controllers;

class FahrzeugRueckgabe extends BaseController
{
    public function index()
    {
		echo view('templates/header');
		echo view('templates/navigation');
        echo view('fahrzeug_rueckgabe_view');
		echo view('templates/footer');
    }
}
