<?php
namespace App\Controllers;

class Logout extends BaseController
{

    // Eine Funktion aus der Klasse Login wird aufgerufen und löscht die Session
    public static function index()
    {
        $logout = new Login();
        $logout->logout();
    }
}
