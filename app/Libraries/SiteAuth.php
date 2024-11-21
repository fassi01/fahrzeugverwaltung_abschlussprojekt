<?php

namespace App\Libraries;

class SiteAuth
{   
    public static function getPermissionLvl($permissionId)
    {   
        $permissionLvl = '0';
        $permissionArray = session()->get('u_permissions');
        
        foreach ($permissionArray as $permissionRow) {

            if (str_contains($permissionRow['p_id'], $permissionId)) {      
            
                $permissionLvl = $permissionRow['gp_level'];
                break;
                
            }
        }
        
        return $permissionLvl;
        
    }

    public static function setUserPermissionsToSession()
    {   
        
        // read user_group by userId
        $db1      = \Config\Database::connect();
        $builder1 = $db1->table('t_user_group');
        $builder1->where('u_id', session()->get('u_id'));
        $query1   = $builder1->get();
        $userGroupArray = $query1->getFirstRow('array');
        
        //read group_permission by groupId
        $db2      = \Config\Database::connect();
        $builder2 = $db2->table('t_group_permission');
        $builder2->where('g_id', $userGroupArray['g_id']);
        $query2   = $builder2->get();
        
        $i = 0;
        $data = array();
        foreach ($query2->getResult() as $resultRow) {
            
            $row = json_decode(json_encode($resultRow), true);
            
            $dataStructure = array('p_id' => $row['p_id'],
                'gp_level' => $row['gp_level'],
            );
            
            $data[$i] = $dataStructure;
            $i++;
        }
        
        session()->set('u_permissions', $data);
    }
    
}