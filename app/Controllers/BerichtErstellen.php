<?php

namespace App\Controllers;

class BerichtErstellen extends BaseController
{
    public function index()
    {
		echo view('templates/header');
		echo view('templates/navigation');
        echo view('bericht_erstellen_view');
		echo view('templates/footer');
    }
}
