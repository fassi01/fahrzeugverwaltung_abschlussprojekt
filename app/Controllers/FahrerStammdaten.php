<?php

namespace App\Controllers;
use CodeIgniter\Controller;

class FahrerStammdaten extends Controller
{
	public function __construct()
    {
		
	}
	
    public function index()
    {
		echo view('templates/header');
		echo view('templates/navigation');
        echo view('fahrer_stammdaten_view');
		echo view('templates/footer');
    }

}
?>