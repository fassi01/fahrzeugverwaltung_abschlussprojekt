<?php
namespace App\Controllers;

use App\Libraries\ServerInfo;
use CodeIgniter\Controller;

class Konto extends Controller
{

    // Die Meldungen aus der Session werden gelöscht
    public function __construct()
    {
        helper([
            'url',
            'form'
        ]);
        $session = session();
        $session->set('htmlTitle', 'FPMS - Konto');

        if (! $session->getFlashdata('msgError')) {
            $session->remove('formErrors');
        }
        // if ($insertOk == 'false' && empty($saveBBlock) && empty($saveZBlock)) {
        // session()->setFlashdata('msgOK', '');
        // }
        // session()->setFlashdata('msgWarning', '');
    }

    public function index()
    {
        echo view('templates/header');
        echo view('templates/navigation');
        echo view('konto_view');
        echo view('templates/footer');
    }

    public function changePassword()
    {
        // Die Werte aus den Input-Feldern werden in einem Array gespeichert, damit es der validation übergeben werden kann.
        $data = array(
            'newPassword' => $this->request->getVar('newPassword'),
            'confirmPassword' => $this->request->getVar('confirmPassword')
        );

        // Die Validation überprüft die Eingaben in den Feldern und gibt wenn nötig Fehlermeldungen
        $validation = \Config\Services::validation();
        $validation->run($data, 'accountChangePassword');
        $errors = $validation->getErrors();

        if ($errors) {
            // Show valditation errors on view
            session()->set('formErrors', $errors);
            session()->setFlashdata('msgError', 'Bei der Validierung sind Fehler aufgetreten!');
            return redirect()->to('/Konto?validationError=true');
        } else {
            // Save input to DB

            // Passwort wird gehasht
            $hashPassword = password_hash($this->request->getVar('confirmPassword'), PASSWORD_DEFAULT);

            // Die Daten des angemeldeten Accounts werden abgerufen
            $dbGroup = ServerInfo::getDbGroup();
            $db1 = \Config\Database::connect($dbGroup);
            $builder1 = $db1->table('t_user');
            // $AccountModel = new AccountModel();
            $whereSql = array(
                'u_id' => session()->get('u_id')
            );

            // Das neue Passwort wird in der Datenbank gespeichert
            $builder1->where($whereSql)
                ->set([
                'u_password' => $hashPassword
            ])
                ->update();

            session()->setFlashdata('msgOK', 'Das Passwort wurde erfolgreich aktualisiert.');
            return redirect()->to('/Konto?changeOk=true');
        }
    }
}
