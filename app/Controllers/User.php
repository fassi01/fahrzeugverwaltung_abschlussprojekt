<?php
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Libraries\ServerInfo;
use App\Libraries\SiteAuth;

class User extends Controller
{
    
    public function __construct()
    {
        helper(['url', 'form']);
        
        $session = session();
        $session->set('htmlTitle', 'FPMS - Benutzer');
        
        if (isset($_GET['insertOk'])){
            $insertOk = $_GET['insertOk'];
        }else{
            $insertOk = 'false';
        }
        
        session()->remove('formErrors');
        
        if ($insertOk=='false'){
            session()->setFlashdata('msgOK', '');
        }
        session()->setFlashdata('msgWarning', '');
        session()->setFlashdata('msgError', '');
        
        $session->set('backNavUserAuthRole', '');
        
    }
    
    public function index()
    {
        
        if (isset($_GET['modus'])){
            $modus = $_GET['modus'];
        }else{
            $modus = '';
        }
        
        if (isset($_GET['action'])){
            $action = $_GET['action'];
        }else{
            $action = '';
        }
        
        if (isset($_GET['userId'])){
            $userId = $_GET['userId'];
        }else{
            $userId = '';
        }
        
        // Check permissions for site
        if(SiteAuth::getPermissionLvl('MANAGE_USER') == '0'){
            session()->setFlashdata('msgError', 'Zugriff verweigert. Zu niedriges Berechtigungslevel.');
            return redirect()->to('/?permissionError=true');
        }elseif ((SiteAuth::getPermissionLvl('MANAGE_USER') == '1' && $modus=='change')
            || (SiteAuth::getPermissionLvl('MANAGE_USER') == '1' && $modus=='insert')){
                session()->setFlashdata('msgError', 'Zugriff verweigert. Zu niedriges Berechtigungslevel.');
                return redirect()->to('/?permissionError=true');
        }
        
        
        if ($modus == 'insert' && $action == '') {
            // fill viewData with raw fields
            $viewData = $this->getRawViewData();
            $this->showView($viewData);
            
        }elseif ($modus == 'insert' && $action == 'save') {
            
            // Insert new user & user2authGroup
            
            $email = old('email');
            $firstName = old('firstName');
            $lastName = old('lastName');
            $status = old('status');
            $newPassword = old('newPassword');
            $authRoleId = old('authRoleId');
            $empty = 'empty';
            
            //Validation
            $valData = array(
                'u_mail' => $email,
                'u_first_name' => $firstName,
                'u_last_name' => $lastName,
                'u_status' => $status,
                'u_authRoleId' => $authRoleId,
                'u_password' => $newPassword,
                'empty' => $empty                
            );
            
            $validation =  \Config\Services::validation();
            $validation->run($valData, 'userInsert');
            $valErrors = $validation->getErrors();
            
            $viewData = $this->getChangedViewDataArray($userId, $email, $firstName, $lastName, $status, $newPassword, $authRoleId);
            
            if ($valErrors) {
                // Show valditation errors on view
                session()->set('formErrors', $valErrors);
                session()->setFlashdata('msgError', 'Bei der Validierung sind Fehler aufgetreten!');
                
                $this->showView($viewData);
                
            }else{
                
                $userId = $this->insertUser($viewData);
                $url = '/User?modus=show&userId=' . $userId . '&insertOk=true';
                return redirect()->to($url);
                
            }
            
        }elseif ($modus == 'show') {
            
            $viewData = $this->getViewDataByUserIdFromDb($userId);
            $this->showView($viewData);
            
        }elseif ($modus == 'change' && $action == 'changeButton') {
            
            $viewData = $this->getViewDataByUserIdFromDb($userId);
            $this->showView($viewData);
            
        }elseif ($modus == 'change' && $action == 'changeSaveButton') {
            
            $email = old('email');
            $firstName = old('firstName');
            $lastName = old('lastName');            
            $status = old('status');
            $newPassword = old('newPassword');
            $authRoleId = old('authRoleId');
            $empty = 'empty';
            
            //Validation
            $valData = array(
                'u_mail' => $email,
                'u_first_name' => $firstName,
                'u_last_name' => $lastName,
                'u_status' => $status,
                'u_authRoleId' => $authRoleId,
                'empty' => $empty
            );            
            
            $validation =  \Config\Services::validation();
            $validation->run($valData, 'user');
            $valErrors = $validation->getErrors();
            
            $viewData = $this->getChangedViewDataArray($userId, $email, $firstName, $lastName, $status, $newPassword, $authRoleId);
            
            if ($valErrors) {
                // Show valditation errors on view
                session()->set('formErrors', $valErrors);
                session()->setFlashdata('msgError', 'Bei der Validierung sind Fehler aufgetreten!');
                
                $this->showView($viewData);
                
            }else{
                
                $this->updateUser($viewData);
                $this->showView($viewData);
                
            }            
        }
    }
    
    public function insertButton()
    {
        return redirect()->to('/User?modus=insert');
        
    }
    
    public function saveButton()
    {
        return redirect()->to('/User?modus=insert&action=save')->withInput();
        
    }
    
    public function changeSaveButton()
    {
        $userId = set_value('userId');
        $url = '/User?modus=change&action=changeSaveButton&userId=' . $userId;
        return redirect()->to($url)->withInput();
        
    }
    
    public function changeButton()
    {
        $userId = set_value('userId');
        $url = '/User?modus=change&action=changeButton&userId=' . $userId;
        return redirect()->to($url)->withInput();
        
    }
    
    public function setBackLocationInsertAuthRoleFromUserInsert(){
        
        session()->set('backNavUserAuthRole', '/User?modus=insert');
        return redirect()->to('/UserAuthRole?modus=insert');
        
    }
    
    public function setBackLocationInsertAuthRoleFromUserChange(){
        
        if (isset($_GET['userId'])){
            $userId = $_GET['userId'];
        }else{
            $userId = '';
        }
        
        $url = '/User?modus=change&action=changeButton&userId=' . $userId;
        session()->set('backNavUserAuthRole', $url);
        return redirect()->to('/UserAuthRole?modus=insert');
        
    }
    
    protected function getViewDataByUserIdFromDb ($userId)
    {
        
        $viewData = array ();
        
        // get data from t_user
        
        $db1      = \Config\Database::connect();
        $builder1 = $db1->table('t_user');
        $builder1->where('u_id', $userId);
        $query1   = $builder1->get();
        $userArray = $query1->getFirstRow('array');
        

        
        //get data from t_user_group
        
        $db2      = \Config\Database::connect();
        $builder2 = $db2->table('t_user_group');
        $builder2->where('u_id', $userId);
        $query2   = $builder2->get();
        $userGroupArray = $query2->getFirstRow('array');
        
        
        
        //get dropdown values from t_group
        $db3      = \Config\Database::connect();
        $builder3 = $db3->table('t_group');        
        $query3   = $builder3->get();
        
        $i = 0;
        $data = array();
        foreach ($query3->getResult() as $resultRow) {
            
            $row = json_decode(json_encode($resultRow), true);
            
            $dataStructure = array('g_id' => $row['g_id'],
                'g_description' => $row['g_description']                
            );
            
            $data[$i] = $dataStructure;
            $i++;
            
            
        }
        
        // set viewData-Array for View
        
        $viewData['u_id'] = $userArray['u_id'];
        $viewData['u_mail'] = $userArray['u_mail'];
        $viewData['u_password'] = '';
        $viewData['u_first_name'] = $userArray['u_first_name'];
        $viewData['u_last_name'] = $userArray['u_last_name'];
        $viewData['u_status'] = $userArray['u_status'];
        
        if (!$userGroupArray) {
            $viewData['g_id'] = 'empty';
        }else{
            $viewData['g_id'] = $userGroupArray['g_id'];
        }
        
        $viewData['groupDropDown'] = $data;
        
        return $viewData;
        
        
    }
    
    protected function showView($viewData)
    {
        
        echo view('templates/header');
        echo view('templates/navigation');
        echo view('user_view', $viewData);
        echo view('templates/footer');
        
    }
    
    protected function getChangedViewDataArray($userId, $email, $firstName, $lastName, $status, $newPassword, $authRoleId)
    {
        
        //get dropdown values from t_group
        $db1      = \Config\Database::connect();
        $builder1 = $db1->table('t_group');
        $query1   = $builder1->get();
        
        $i = 0;
        $data = array();
        foreach ($query1->getResult() as $resultRow) {
            
            $row = json_decode(json_encode($resultRow), true);
            
            $dataStructure = array('g_id' => $row['g_id'],
                'g_description' => $row['g_description']
            );
            
            $data[$i] = $dataStructure;
            $i++;
            
        }

        
        // set viewData-Array for View
        
        $viewData['u_id'] = $userId;
        $viewData['u_mail'] = $email;
        $viewData['u_password'] = $newPassword;
        $viewData['u_first_name'] = $firstName;
        $viewData['u_last_name'] = $lastName;
        $viewData['u_status'] = $status;
        $viewData['g_id'] = $authRoleId;        
        $viewData['groupDropDown'] = $data;
        
        return $viewData;
        
    }
    
    protected function updateUser($viewData)
    {
        $foundDbError = false;        
        
        // update t_user
        $db1      = \Config\Database::connect();
//         $builder1 = $db1->query(ServerInfo::getMysqlSessionUserId());
        $builder1 = $db1->table('t_user');
        $builder1->set('u_mail', $viewData['u_mail']);
        $builder1->set('u_first_name', $viewData['u_first_name']);
        $builder1->set('u_last_name', $viewData['u_last_name']);
        $builder1->set('u_status', $viewData['u_status']);
        
        if (!empty($viewData['u_password']) || $viewData['u_password'] != '') {
            $newPassword = password_hash($viewData['u_password'], PASSWORD_DEFAULT);
            $builder1->set('u_password', $newPassword);
        }
        
        $builder1->where('u_id', $viewData['u_id']);
        
        try {
            $builder1->update();
        } catch (\Exception $dbError){
            $foundDbError = true;
            $dbErrorArray = $db1->error();
            session()->setFlashdata('msgError', $dbErrorArray['message']);
        }
        
        // update t_user_group
        
        if ($foundDbError==false) {
            
            $db2      = \Config\Database::connect();
//             $builder2 = $db2->query(ServerInfo::getMysqlSessionUserId());
            $builder2 = $db2->table('t_user_group');            
            $builder2->set('g_id', $viewData['g_id']);
            $builder2->where('u_id', $viewData['u_id']);
            
            try {
                $builder2->update();
            } catch (\Exception $dbError){
                $foundDbError = true;
                $dbErrorArray = $db2->error();
                session()->setFlashdata('msgError', $dbErrorArray['message']);
            }                        

        }
        
        if($foundDbError==false){
            session()->setFlashdata('msgOK', 'Der Benutzer wurde erfolgreich aktualisiert.');
        }
        
    }
    
    
    protected function getRawViewData()
    {
        
        //get dropdown values from t_group
        $db1      = \Config\Database::connect();
        $builder1 = $db1->table('t_group');
        $query1   = $builder1->get();
        
        $i = 0;
        $data = array();
        foreach ($query1->getResult() as $resultRow) {
            
            $row = json_decode(json_encode($resultRow), true);
            
            $dataStructure = array('g_id' => $row['g_id'],
                'g_description' => $row['g_description']
            );
            
            $data[$i] = $dataStructure;
            $i++;            
        }        
        
        // set viewData-Array for View
        
        $viewData['u_id'] = '';
        $viewData['u_mail'] = '';
        $viewData['u_password'] = '';
        $viewData['u_first_name'] = '';
        $viewData['u_last_name'] = '';
        $viewData['u_status'] = '';
        $viewData['g_id'] = '';
        $viewData['groupDropDown'] = $data;
        
        return $viewData;
        
    }
    
    protected function insertUser($viewData)
    {
        
        $foundDbError = false;
        
        // insert t_user
        $db1      = \Config\Database::connect();
//         $builder1 = $db1->query(ServerInfo::getMysqlSessionUserId());
        $builder1 = $db1->table('t_user');
        
        $newPassword = password_hash($viewData['u_password'], PASSWORD_DEFAULT);
        
        $insertData1 = [
            'u_mail' => $viewData['u_mail'],
            'u_first_name' => $viewData['u_first_name'],
            'u_last_name' => $viewData['u_last_name'],
            'u_status' => $viewData['u_status'],
            'u_password' => $newPassword,
        ];        
        
        try {
            $builder1->insert($insertData1);
        } catch (\Exception $dbError){
            $foundDbError = true;
            $dbErrorArray = $db1->error();
            session()->setFlashdata('msgError', $dbErrorArray['message']);
        }
        
        $userId = $db1->insertID();
        
        // insert t_user_group
        
        if ($foundDbError==false) {
            
            $db2      = \Config\Database::connect();
//             $builder2 = $db2->query(ServerInfo::getMysqlSessionUserId());
            $builder2 = $db2->table('t_user_group');           
            
            $insertData2 = [
                'u_id' => $userId,
                'g_id' => $viewData['g_id'],
            ];            
            
            try {
                $builder2->insert($insertData2);
            } catch (\Exception $dbError){
                $foundDbError = true;
                $dbErrorArray = $db2->error();
                session()->setFlashdata('msgError', $dbErrorArray['message']);
            }
            
        }
        
        if($foundDbError==false){
            session()->setFlashdata('msgOK', 'Der Benutzer wurde erfolgreich angelegt.');
        }

        return $userId;
        
    }
    
}

