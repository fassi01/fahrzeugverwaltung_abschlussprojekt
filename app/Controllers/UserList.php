<?php
namespace App\Controllers;
use App\Libraries\SiteAuth;
use CodeIgniter\Controller;

class UserList extends Controller
{
    
    public function __construct()
    {
        helper(['url', 'form']);
        
        $session = session();
        $session->set('htmlTitle', 'FPMS - Benutzerübersicht');
        
        session()->remove('formErrors');
        session()->setFlashdata('msgOK', '');
        session()->setFlashdata('msgWarning', '');
        session()->setFlashdata('msgError', '');
        
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
            
            $inpUserIdValue = old('inpUserIdValue');            
            $inpFirstnameValue = old('inpFirstnameValue');
            $inpLastnameValue = old('inpLastnameValue');
            $inpEmailValue = old('inpEmailValue');
            $inpStatus = old('inpStatusValue');
            
            $inpUserIdOp = old('inpUserIdOp');
            $inpFirstnameOp = old('inpFirstnameOp');
            $inpLastnameOp = old('inpLastnameOp');
            $inpEmailOp = old('inpEmailOp');
            $inpStatusOp = old('inpStatusOp');
            
            /*
            echo $inpUserIdOp . " " . $inpUserIdValue . "</br>";
            echo $inpFirstnameOp . " " . $inpFirstnameValue . "</br>";
            echo $inpLastnameOp . " " . $inpLastnameValue . "</br>";
            echo $inpEmailOp . " " . $inpEmailValue . "</br>";
            echo $inpStatusOp . " " . $inpStatus . "</br>";
            */
            
            
            $db      = \Config\Database::connect();            
            $builder = $db->table('t_user');
            
            if ($inpUserIdOp != 'empty' &&  !empty($inpUserIdOp)){
                $this->setWhereClausel('u_id', $inpUserIdOp, $inpUserIdValue, $builder);
            }
            
            if ($inpFirstnameOp != 'empty' &&  !empty($inpFirstnameOp)){
                $this->setWhereClausel('u_first_name', $inpFirstnameOp, $inpFirstnameValue, $builder);
            } 
            
            if ($inpLastnameOp != 'empty' && !empty($inpLastnameOp)){
                $this->setWhereClausel('u_last_name', $inpLastnameOp, $inpLastnameValue, $builder);
            } 
            
            if ($inpEmailOp != 'empty' && !empty($inpEmailOp)){
                $this->setWhereClausel('u_mail', $inpEmailOp, $inpEmailValue, $builder);
            } 
            
            if ($inpStatusOp != 'empty' && !empty($inpStatusOp)){
                $this->setWhereClausel('u_status', $inpStatusOp, $inpStatus, $builder);
            } 

            $query   = $builder->get();
           
        }else{
            
            $db      = \Config\Database::connect();
            $builder = $db->table('t_user');            
            $query   = $builder->get(); 
            
        }
        
        $i = 0;
        $viewData = array ();
        $data = array();
        foreach ($query->getResult() as $resultRow) {
            
            $row = json_decode(json_encode($resultRow), true);
            
            $dataStructure = array('u_id' => $row['u_id'],
                'u_first_name' => $row['u_first_name'],
                'u_last_name' => $row['u_last_name'],
                'u_mail' => $row['u_mail'],
                'u_status' => $row['u_status']);
            
            $data[$i] = $dataStructure;
            $i++;
            
        }
        
        $viewData['tableView'] = $data;
        
        echo view('templates/header');
        echo view('templates/navigation');
        echo view('user_list_view', $viewData);
        echo view('templates/footer');
        

        
    }
    
    public function search()
    {   
        return redirect()->to('/UserList?modus=search')->withInput();        
        
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