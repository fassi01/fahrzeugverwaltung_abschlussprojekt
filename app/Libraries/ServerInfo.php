<?php
namespace App\Libraries;

class ServerInfo
{

    // Returns the environment-name
    public function getEnvNameDe()
    {
        helper([
            'url'
        ]);
        $currentUrl = current_url();

        IF (ENVIRONMENT === 'development' && str_contains($currentUrl, 'localhost:8080') || str_contains($currentUrl, 'fpms-pde-test')) {
            return 'Entwicklungsumgebung';
        }

        IF (ENVIRONMENT === 'deveproductionlopment' && str_contains($currentUrl, 'fpms-prod')) {
            return 'Produktivumgebung';
        }

        return 'Kein Systemname gefunden';
    }

    public static function getEnvNameEn()
    {
        helper([
            'url'
        ]);
        $currentUrl = current_url();

        IF (ENVIRONMENT === 'development' && str_contains($currentUrl, 'localhost:8080') || str_contains($currentUrl, 'fpms-pde-test')) {
            return 'development';
        }

        IF (ENVIRONMENT === 'deveproductionlopment' && str_contains($currentUrl, 'fpms-prod')) {
            return 'production';
        }

        return 'Kein Systemname gefunden';
    }

    public function printSession()
    {
        $session_htmlTitle = session()->get('htmlTitle');
        $session_enviornmentEn = session()->get('enviornmentEn');
        $session_enviornmentDe = session()->get('enviornmentDe');
        $session_u_id = session()->get('u_id');
        $session_u_firstName = session()->get('u_firstName');
        $session_u_lastName = session()->get('u_lastName');
        $session_u_mail = session()->get('u_mail');
        $session_u_isLoggedIn = session()->get('u_isLoggedIn');
        $session_u_permissions = session()->get('u_permissions');
        $session_relevantFieldsForProvregeln = session()->get('relevantFieldsForProvregeln');

        echo "htmlTitle: " . $session_htmlTitle . "</br>" . "enviornmentEn: " . $session_enviornmentEn . "</br>" . "enviornmentDe: " . $session_enviornmentDe . "</br>" . "u_id: " . $session_u_id . "</br>" . "u_firstName: " . $session_u_firstName . "</br>" . "u_lastName: " . $session_u_lastName . "</br>" . "u_mail: " . $session_u_mail . "</br>" . "u_isLoggedIn: " . $session_u_isLoggedIn . "</br></br>";

        echo "UserPermissions: ";
        print_r($session_u_permissions);
        echo "</br></br>Relevante Felder Provisionsregeln: ";
        print_r($session_relevantFieldsForProvregeln);
    }



    public static function getDbGroup()
    {
        $env = ServerInfo::getEnvNameEn();

        if ($env == 'development') {
            return 'test';
        }

        if ($env == 'production') {
            return 'prod';
        }
    }

    public static function getMysqlSessionUserId(){
        $mySqlSessionUserId = 'SET @WEBUSERID=' . session()->get('u_id');
        return $mySqlSessionUserId;
    }

}
   