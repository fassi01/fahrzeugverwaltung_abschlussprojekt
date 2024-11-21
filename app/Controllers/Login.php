<?php
namespace App\Controllers;

use App\Libraries\ServerInfo;
use CodeIgniter\Controller;
use MongoDB\Driver\Exception\AuthenticationException;

class Login extends Controller
{

    public function __construct()
    {
        helper([
            'url',
            'form'
        ]);

        $session = session();

        $session->set('htmlTitle', 'FPMS - Login');
    }

    public function index()
    {
        $this->getServerMessage();

        echo view('templates/header');
        echo view('login_view');
        echo view('templates/footer');
    }

    protected function getServerMessage()
    {
        $dbGroup = ServerInfo::getDbGroup();
        $db1 = \Config\Database::connect($dbGroup);
        $builder1 = $db1->table('t_server_messages');
        $builder1->where('sm_id', '1');
        $builder1->where('sm_status', 'A'); // Fehlermeldungen sind inaktiv
        $query1 = $builder1->get();
        $serverMessageArray = $query1->getFirstRow('array');

        if (! empty($serverMessageArray['sm_message_text'])) {
            session()->setFlashdata('msgWarning', $serverMessageArray['sm_message_text']);
        }
    }

    public function loginAuth()
    {
        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');

        $session = session();

        if (! $email) {
            $session->setFlashdata('msgErrorLoginEmail', 'Bitte füllen Sie die E-Mail aus.');
        }

        if (! $password) {
            $session->set('u_mail', $email);
            $session->setFlashdata('msgErrorLoginPassword', 'Bitte füllen Sie das Passwort aus.');
        }

        if (! $email || ! $password) {
            return redirect()->to('/login');
        }

        $dbGroup = ServerInfo::getDbGroup();
        $db1 = \Config\Database::connect($dbGroup);
        $builder1 = $db1->table('t_user');
        $builder1->like('u_mail', $email);
        $builder1->where('u_status', 'A');
        $query1 = $builder1->get();

        $dbArray = $query1->getFirstRow('array');

        if (! empty($dbArray)) {
            
            if ($dbArray['u_status'] == 'A' && $dbArray['u_super_user'] == 'X') {

                $passPassword = trim($dbArray['u_password']);

                $authenticatePassword = password_verify($password, $passPassword);

                if ($authenticatePassword) {
                    
                    session()->set('u_id', $dbArray['u_id']);
                    session()->set('u_firstName', $dbArray['u_first_name']);
                    session()->set('u_lastName', $dbArray['u_last_name']);
                    session()->set('u_mail', $dbArray['u_mail']);
                    session()->set('u_isLoggedIn', TRUE);                    
                    

                    // Set last login to database
                    $ServerInfo = new ServerInfo();
                    $dbGroup = $ServerInfo->getDbGroup();

                    $timestamp = date('Y-m-d H:i:s');

                    $builder1 = $db1->table('t_user');
                    $builder1->set('u_last_login', $timestamp);
                    $builder1->where('u_id', session()->get('u_id'));
                    $builder1->where('u_status', 'A');
                    $builder1->update();

                    
                    
                    return redirect()->to('/home');
                } else {
                    $session->setFlashdata('msgErrorLoginPassword', 'Das Passwort ist falsch.');
                    $session->set('u_mail', $email);
                    return redirect()->to('/login');
                }
            } else {
                return redirect()->to('/login');
            }
        } else {
            $session->setFlashdata('msgErrorLoginEmail', 'Die E-Mail existiert nicht.');
            return redirect()->to('/login');
        }

        return redirect()->to('/login');
    }

    public function logout()
    {
        session_destroy();
        return redirect()->to('/login');
    }

    protected function isLoginlocked()
    {
        $isLoginLocked = false;

        $dbGroup = ServerInfo::getDbGroup();
        $db1 = \Config\Database::connect($dbGroup);
        $builder1 = $db1->table('t_server_messages');
        $builder1->where('sm_id', '1');
        $builder1->where('sm_status', 'A');
        $builder1->where('sm_login_locked', 'X');
        $query1 = $builder1->get();

        $serverMessageArray = $query1->getFirstRow('array');

        if (! empty($serverMessageArray)) {
            $isLoginLocked = true;
        }

        return $isLoginLocked;
    }

}
