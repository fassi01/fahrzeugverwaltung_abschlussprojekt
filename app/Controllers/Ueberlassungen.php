<?php

namespace App\Controllers;

class Ueberlassungen extends BaseController
{
    public function index()
    {
		echo view('templates/header');
		echo view('templates/navigation');
        echo view('ueberlassungen_view');
		echo view('templates/footer');
    }
}
