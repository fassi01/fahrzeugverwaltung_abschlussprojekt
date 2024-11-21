<?php

namespace App\Controllers;

class BerichtPersonalabteilung extends BaseController
{
    public function index()
    {
		echo view('templates/header');
		echo view('templates/navigation');
        echo view('bericht_personalabteilung_view');
		echo view('templates/footer');
    }
}
