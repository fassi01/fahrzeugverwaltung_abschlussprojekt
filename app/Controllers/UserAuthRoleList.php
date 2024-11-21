<?php
namespace App\Controllers;
use App\Libraries\SiteAuth;
use CodeIgniter\Controller;

class UserAuthRoleList extends Controller
{
    
    public function __construct()
    {
        helper(['url', 'form']);
        
        $session = session();
        $session->set('htmlTitle', 'FPMS - Berechtigungen');
        
        if (isset($_GET['deleteOk'])){
            $deleteOk = $_GET['deleteOk'];
        }else{
            $deleteOk = 'empty';
        }
        
        session()->remove('formErrors');
        
        if ($deleteOk=='false' || $deleteOk=='empty'){
            session()->setFlashdata('msgOK', '');
        }
        
        session()->setFlashdata('msgWarning', '');
        
        if ($deleteOk=='true' || $deleteOk=='empty'){
            session()->setFlashdata('msgError', '');
        }
        
    }
    
    public function index()
    {
        // Check permissions for site
        
        if(SiteAuth::getPermissionLvl('MANAGE_USER') == '0'){
            session()->setFlashdata('msgError', 'Zugriff verweigert. Zu niedriges Berechtigungslevel.');
            return redirect()->to('/?permissionError=true');
        }
        
        
        if (isset($_GET['modus'])){
            $modus = $_GET['modus'];
        }else{
            $modus = '';
        }
        
        if ($modus == 'search') {
            
            $inpAuthRoleIdValue = old('inpAuthRoleIdValue');
            $inpAuthRoleDescValue = old('inpAuthRoleDescValue');
            
            $inpAuthRoleIdOp = old('inpAuthRoleIdOp');
            $inpAuthRoleDescOp = old('inpAuthRoleDescOp');           
            
            $db      = \Config\Database::connect();
            $builder = $db->table('t_group');
            
            if ($inpAuthRoleIdOp != 'empty' &&  !empty($inpAuthRoleIdOp)){
                $this->setWhereClausel('g_id', $inpAuthRoleIdOp, $inpAuthRoleIdValue, $builder);
            }
            
            if ($inpAuthRoleDescOp != 'empty' && !empty($inpAuthRoleDescOp) ){
                $this->setWhereClausel('g_description', $inpAuthRoleDescOp, $inpAuthRoleDescValue, $builder);
            }
            
            $query   = $builder->get();
            
        }else{
            
            $db      = \Config\Database::connect();
            $builder = $db->table('t_group');            
            $query   = $builder->get();
            
        }
        
        $i = 0;
        $viewData = array ();
        $data = array();
        foreach ($query->getResult() as $resultRow) {
            
            $row = json_decode(json_encode($resultRow), true);
            
            $dataStructure = array('g_id' => $row['g_id'],
                'g_description' => $row['g_description']);
            
            $data[$i] = $dataStructure;
            $i++;
            
        }
        
        $viewData['tableView'] = $data;
        
        echo view('templates/header');
        echo view('templates/navigation');
        echo view('user_auth_role_list_view', $viewData);
        echo view('templates/footer');
        
        
        
    }
    
    public function search()
    {
        return redirect()->to('/UserAuthRoleList?modus=search')->withInput();
        
    }
    
    
    protected function setWhereClausel($dbField, $operator, $value, $builder)
    {
        
        if ($operator != 'CP' AND $operator != 'SW'){
            
            $operatorConv = $this->getOperatorConv($operator);
            $idWhere = $dbField . ' ' . $operatorConv;
            $builder->where($idWhere, $value);
            
        }else{
            
            if ($operator == 'CP'){
                $builder->like($dbField, $value);
            }
            
            if ($operator == 'SW'){
                $builder->like($dbField, $value, 'after');
            }
            
        }
        
    }
    
    protected function getOperatorConv($operator)
    {
        
        switch ($operator) {
            case '=':           $operatorConv = '='; break;
            case '&lt;&gt;':    $operatorConv = '<>'; break;
            case '&gt;':        $operatorConv = '>'; break;
            case '&lt;':        $operatorConv = '<'; break;
        }
        
        return $operatorConv;
    }
    
    
}