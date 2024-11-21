<?php
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Libraries\SiteAuth;
use App\Libraries\ServerInfo;

class UserAuthRole extends Controller
{
    
    public function __construct()
    {
        helper(['url', 'form']);
        
        $session = session();
        $session->set('htmlTitle', 'FPMS - Berechtigungsrolle');
        
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
        
        // set back navigation Url
        if (empty($session->get('backNavUserAuthRole'))){
            $session->set('backNavUserAuthRole', '/UserAuthRoleList');
        }
        
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
        
        if (isset($_GET['authRoleId'])){
            $authRoleId = $_GET['authRoleId'];
        }else{
            $authRoleId = '';
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
            
            // Insert new group & group_permissions
            
            $authRoleDesc = old('authRoleDesc');
            
            //Validation
            $valData = array(
                'authRoleDesc' => $authRoleDesc
            );
            
            $validation =  \Config\Services::validation();
            $validation->run($valData, 'authRole');
            $valErrors = $validation->getErrors();
            
            $viewData = $this->getChangedViewDataArray($authRoleId, $authRoleDesc);
            
            if ($valErrors) {
                // Show valditation errors on view
                session()->set('formErrors', $valErrors);
                session()->setFlashdata('msgError', 'Bei der Validierung sind Fehler aufgetreten!');
                
                $this->showView($viewData);
                
            }else{
                
                $authRoleId = $this->insertAuthRole($viewData);
                $url = '/UserAuthRole?modus=show&authRoleId=' . $authRoleId . '&insertOk=true';
                return redirect()->to($url);
                
            }
            
        }elseif ($modus == 'show') {
            
            $viewData = $this->getViewDataByAuthRoleIdFromDb($authRoleId);
            $this->showView($viewData);
            
        }elseif ($modus == 'change' && $action == 'changeButton') {            

            $viewData = $this->getViewDataByAuthRoleIdFromDb($authRoleId);
            $this->showView($viewData);        
            
        }elseif ($modus == 'change' && $action == 'changeSaveButton') {
            
            $authRoleDesc = old('authRoleDesc');
            
            //Validation
            $valData = array(
                'authRoleDesc' => $authRoleDesc
            );
            
            $validation =  \Config\Services::validation();
            $validation->run($valData, 'authRole');
            $valErrors = $validation->getErrors();
            
            $viewData = $this->getChangedViewDataArray($authRoleId, $authRoleDesc);
            
            if ($valErrors) {
                // Show valditation errors on view
                session()->set('formErrors', $valErrors);
                session()->setFlashdata('msgError', 'Bei der Validierung sind Fehler aufgetreten!');                
                
                $this->showView($viewData);
                
            }else{
                
                $this->updateAuthRole($viewData);
                $this->showView($viewData);
                
            }
            
        }elseif ($modus == 'delete') {
            
            $foundDbError = $this->deleteAuthRoleById($authRoleId);
            
            IF ($foundDbError == true){
                session()->setFlashdata('msgError', 'Die Benutzerrolle konnte nicht gelöscht werden. Diese Benutzerrolle ist möglicherweise kaputt.');
                return redirect()->to('/UserAuthRoleList?deleteOk=false');
            }else{
                session()->setFlashdata('msgOK', 'Die Benutzerrolle wurde erfolgreich gelöscht.');
                return redirect()->to('/UserAuthRoleList?deleteOk=true');
            }
            
        }
    }
    
    public function insertButton()
    {
        return redirect()->to('/UserAuthRole?modus=insert');
        
    }
    
    public function saveButton()
    {        
        return redirect()->to('/UserAuthRole?modus=insert&action=save')->withInput();
        
    }
    
    public function changeSaveButton()
    {
        $authRoleId = set_value('authRoleId');
        $url = '/UserAuthRole?modus=change&action=changeSaveButton&authRoleId=' . $authRoleId;
        return redirect()->to($url)->withInput();
        
    }
    
    public function changeButton()
    {
        $authRoleId = set_value('authRoleId');
        $url = '/UserAuthRole?modus=change&action=changeButton&authRoleId=' . $authRoleId;
        return redirect()->to($url)->withInput();
        
    }
    
    protected function getViewDataByAuthRoleIdFromDb ($authRoleId)
    {
        
        // get tableView-data 'group-permission-level'
        
        $db1      = \Config\Database::connect();
        $builder1 = $db1->table('t_permission');
        $builder1->orderBy('p_description', 'ASC');
        $query1   = $builder1->get();
        
        $db2      = \Config\Database::connect();
        $builder2 = $db2->table('t_group_permission');
        
        $i = 0;
        $viewData = array ();
        $data = array();
        foreach ($query1->getResult() as $resultRow) {
            
            $row = json_decode(json_encode($resultRow), true);
            
            
            $builder2->where('g_id', $authRoleId);
            $builder2->where('p_id', $row['p_id']);
            $query2   = $builder2->get();
            $groupPermissionLevelArray = $query2->getFirstRow('array');
            
            
            $dataStructure = array('p_id' => $row['p_id'],
                'p_description' => $row['p_description'],
                'gp_level'      => $groupPermissionLevelArray['gp_level']
            );
            
            $data[$i] = $dataStructure;
            $i++;
        }
        
        // get group description
        $db3      = \Config\Database::connect();
        $builder3 = $db3->table('t_group');
        $builder3->where('g_id', $authRoleId);
        $query3   = $builder3->get();
        $groupArray = $query3->getFirstRow('array');
        
        // set data-array for view
        $viewData['tableView'] = $data;
        $viewData['authRoleId'] = $authRoleId;
        $viewData['authRoleDesc'] = $groupArray['g_description'];
        
        return $viewData;
        
        
    }
    
    protected function showView($viewData)
    {
        
        echo view('templates/header');
        echo view('templates/navigation');
        echo view('user_auth_role_view', $viewData);
        echo view('templates/footer');
        
    }
    
    protected function getChangedViewDataArray($authRoleId, $authRoleDesc)
    {
        
        $db1      = \Config\Database::connect();
        $builder1 = $db1->table('t_permission');
        $builder1->orderBy('p_description', 'ASC');
        $query1   = $builder1->get();
        
        $i = 0;
        $data = array();
        foreach ($query1->getResult() as $resultRow) {
            
            $row = json_decode(json_encode($resultRow), true);
            
            $dataStructure = array('p_id' => $row['p_id'],
                'p_description' => $row['p_description'],
                'gp_level'      => '0'
            );
            
            $permissionId = $row['p_id'];
            $gpLevelOld = old($permissionId);
            
            $dataStructure['gp_level'] = $gpLevelOld;
            
            $data[$i] = $dataStructure;
            $i++;
        }
        
        // set data-array for view
        $viewData['tableView'] = $data;
        $viewData['authRoleId'] = $authRoleId;
        $viewData['authRoleDesc'] = $authRoleDesc;
        
        return $viewData;
        
    }
    
    protected function updateAuthRole($viewData)
    {
        $foundDbError = false;
        
        // update group description
        $db1      = \Config\Database::connect();
//         $builder1 = $db1->query(ServerInfo::getMysqlSessionUserId());
        $builder1 = $db1->table('t_group');
        $builder1->set('g_description', $viewData['authRoleDesc']);
        $builder1->where('g_id', $viewData['authRoleId']);        
        
        try {
            $builder1->update();
        } catch (\Exception $dbError){
            $foundDbError = true;
            $dbErrorArray = $db1->error();
            session()->setFlashdata('msgError', $dbErrorArray['message']);
        }
        
        // update groupPermissionLevel
        
        if ($foundDbError==false) {
            
            $db2      = \Config\Database::connect();
//             $builder2 = $db2->query(ServerInfo::getMysqlSessionUserId());
            $builder2 = $db2->table('t_group_permission');
            
            foreach ($viewData['tableView'] as $resultRow) {
                
                if ($foundDbError==false) {
                    
                    $builder2->set('gp_level', $resultRow['gp_level']);
                    $builder2->where('p_id', $resultRow['p_id']);
                    $builder2->where('g_id', $viewData['authRoleId']); 
                    
                    try {
                        $builder2->update();
                    } catch (\Exception $dbError){
                        $foundDbError = true;
                        $dbErrorArray = $db2->error();
                        session()->setFlashdata('msgError', $dbErrorArray['message']);
                    }
                }else{
                    break;
                }
                
            }
        }
        
        if($foundDbError==false){
            session()->setFlashdata('msgOK', 'Die Benutzerrolle wurde erfolgreich aktualisiert.');
        }
        
    }
    
    
    protected function getRawViewData()
    {
        
        $db1      = \Config\Database::connect();
        $builder1 = $db1->table('t_permission');
        $builder1->orderBy('p_description', 'ASC');
        $query1   = $builder1->get();
        
        $i = 0;
        $data = array();
        foreach ($query1->getResult() as $resultRow) {
            
            $row = json_decode(json_encode($resultRow), true);
            
            $dataStructure = array('p_id' => $row['p_id'],
                'p_description' => $row['p_description'],
                'gp_level'      => '0'
            );
            
            $data[$i] = $dataStructure;
            $i++;
        }
        
        $viewData['tableView'] = $data;
        $viewData['authRoleId'] = '';
        $viewData['authRoleDesc'] = '';
        
        return $viewData;
        
    }
    
    protected function insertAuthRole($viewData)
    {
        $foundDbError = false;
        
        // insert group 
        $db1      = \Config\Database::connect();
//         $builder1 = $db1->query(ServerInfo::getMysqlSessionUserId());
        $builder1 = $db1->table('t_group');
        
        $insertData1 = [
            'g_description' => $viewData['authRoleDesc'],
        ];
        
        
        try {
            $builder1->insert($insertData1);
        } catch (\Exception $dbError){
            $foundDbError = true;
            $dbErrorArray = $db1->error();
            session()->setFlashdata('msgError', $dbErrorArray['message']);
        }
        
        // update groupPermissionLevel
        
        if ($foundDbError==false) {
            
            $groupId = $db1->insertID();
            
            $db2      = \Config\Database::connect();
//             $builder2 = $db1->query(ServerInfo::getMysqlSessionUserId());
            $builder2 = $db2->table('t_group_permission');
                        
            foreach ($viewData['tableView'] as $resultRow) {
                
                if ($foundDbError==false) {
                
                    $builder2->set('gp_level', $resultRow['gp_level']);
                    $builder2->where('p_id', $resultRow['p_id']);
                    
                    $insertData2 = [
                        'g_id' => $groupId,
                        'p_id' => $resultRow['p_id'],
                        'gp_level' => $resultRow['gp_level'],
                    ];
                    
                    try {
                        $builder2->insert($insertData2);
                    } catch (\Exception $dbError){
                        $foundDbError = true;
                        $dbErrorArray = $db2->error();
                        session()->setFlashdata('msgError', $dbErrorArray['message']);
                    }
                }else{
                    break;
                }
                
            }
        }
        
        if($foundDbError==false){
            session()->setFlashdata('msgOK', 'Die Benutzerrolle wurde erfolgreich angelegt.');
        }
        
        return $groupId;
        
    }
    
    public function deleteButton(){
        
        if (isset($_GET['authRoleId'])){
            $authRoleId = $_GET['authRoleId'];
        }else{
            $authRoleId = '';
        }
        
        $url = '/UserAuthRole?modus=delete&authRoleId=' . $authRoleId;
        return redirect()->to($url);
        
    }
    
    protected function deleteAuthRoleById($authRoleId){
        
        $foundDbError = false;
        
        //delete user to group relation table
        $db1      = \Config\Database::connect();
//         $builder1 = $db1->query(ServerInfo::getMysqlSessionUserId());
        $builder1 = $db1->table('t_user_group');
        
        try {
            $builder1->delete(['g_id' => $authRoleId]);
        } catch (\Exception $dbError){
            $foundDbError = true;
            $dbErrorArray = $db1->error();
            session()->setFlashdata('msgError', $dbErrorArray['message']);
        }
        
        if ($foundDbError==false) {
            
            //delete group permission relation table
            $db2      = \Config\Database::connect();
//             $builder2 = $db2->query(ServerInfo::getMysqlSessionUserId());
            $builder2 = $db2->table('t_group_permission');
            
            try {
                $builder2->delete(['g_id' => $authRoleId]);
            } catch (\Exception $dbError){
                $foundDbError = true;
                $dbErrorArray = $db2->error();
                session()->setFlashdata('msgError', $dbErrorArray['message']);
            }
            
            if ($foundDbError==false) {
                
                //delete group table
                $db3      = \Config\Database::connect();
//                 $builder3 = $db3->query(ServerInfo::getMysqlSessionUserId());
                $builder3 = $db3->table('t_group');
                
                try {
                    $builder3->delete(['g_id' => $authRoleId]);
                } catch (\Exception $dbError){
                    $foundDbError = true;
                    $dbErrorArray = $db3->error();
                    session()->setFlashdata('msgError', $dbErrorArray['message']);
                }
                
            }
            
        }    
        
        return $foundDbError;
        
    }
    
    
}
    
