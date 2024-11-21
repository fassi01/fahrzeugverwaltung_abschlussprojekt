<?php

namespace App\Controllers;

class BerichtOrdnungswidrigkeit extends BaseController
{
    public function index()
    {
		echo view('templates/header');
		echo view('templates/navigation');
        echo view('bericht_ordnungswidrigkeit_view');
		echo view('templates/footer');
    }
}
