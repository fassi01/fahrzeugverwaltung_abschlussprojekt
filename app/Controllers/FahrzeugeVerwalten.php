<?php

namespace App\Controllers;
use App\Libraries\ServerInfo;
use App\Libraries\SiteAuth;
use CodeIgniter\Controller;
use CodeIgniter\CLI\Console;
use CodeIgniter\HTTP\RedirectResponse;
use FFI\Exception;

/**
 * @author fassbinderl
 *
 */
class FahrzeugeVerwalten extends Controller
{
    
	/**
	 * Konstruktor
	 */
	public function __construct()
    {
        helper(['url', 'form']);
        
        // Vorhandene Session wird geladen.
        $session = session();
        
        // Titel der Webanwendung wird definiert.
        $session->set('htmlTitle', 'Fahrzeuge verwalten');
	}
	
    /**
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function index()
    {
        
        // Bei keiner Berechtigung auf die Fahrzeugverwaltung wird der Zugriff verweigert.
        if(SiteAuth::getPermissionLvl('MANAGE_FAHRZEUGE_VERWALTEN') == '0'){
            session()->setFlashdata('msgError', 'Zugriff verweigert. Zu niedriges Berechtigungslevel.');
            return redirect()->to('/?permissionError=true');
        }
        
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
        
        if (isset($_GET['kfz_id'])){
            $kfzID = $_GET['kfz_id'];
        }else{
            $kfzID = '';
        }        
        
        // Fahrzeugdaten werden anhand der KFZ-ID angezeigt.
        if ($modus == 'show') {
            
            $viewData['kennzeichen'] = $this->getKennzeichen();
            $viewData['fahrzeug'] = $this->getFahrzeugByID($kfzID);
            $viewData['allData'] = $this->getAllData();
            
            $this->showView($viewData);
            
        // Das nächste Fahrzeug aus der Datenbank wird angezeigt.
        } elseif ($modus == 'next'){
                        
            $kfzID = $this->getNextID($kfzID);
            
            $viewData['kennzeichen'] = $this->getKennzeichen();
            $viewData['fahrzeug'] = $this->getFahrzeugByID($kfzID);
            $viewData['allData'] = $this->getAllData();
            
            $this->showView($viewData);
            
            $url = '/FahrzeugeVerwalten?modus=show&kfz_id=' . $kfzID;
            return redirect()->to($url)->withInput();
            
        // Das vorherige Fahrzeug aus der Datenbank wird angezeigt.
        } elseif ($modus == 'former'){
            
            $kfzID = $this->getFormerID($kfzID);
            
            $viewData['kennzeichen'] = $this->getKennzeichen();
            $viewData['fahrzeug'] = $this->getFahrzeugByID($kfzID);
            $viewData['allData'] = $this->getAllData();
            
            $this->showView($viewData);
            
            $url = '/FahrzeugeVerwalten?modus=show&kfz_id=' . $kfzID;
            return redirect()->to($url)->withInput();
            
        // Das Fahrzeug wird geupdated.
        } elseif ($modus == 'change' && $action == 'changeSaveButton'){
            
            if($this->updateFahrzeug($kfzID)){
                $viewData['kennzeichen'] = $this->getKennzeichen();
                $viewData['fahrzeug'] = $this->getFahrzeugByID($kfzID);
                $viewData['allData'] = $this->getAllData();
                
                $this->showView($viewData);                
                $url = '/FahrzeugeVerwalten?modus=show&kfz_id=' . $kfzID;
                return redirect()->to($url)->withInput();
            }
            
        // Ein neues Fahrzeug wird erstellt.
        } elseif ($modus == 'insert' && $action == 'insertSaveButton'){
            
            $newkfzID = $this->insertFahrzeug();
            
            if($newkfzID > 0) {
                
                $viewData['kennzeichen'] = $this->getKennzeichen();
                $viewData['fahrzeug'] = $this->getFahrzeugByID($newkfzID);
                $viewData['allData'] = $this->getAllData();
                
                $this->showView($viewData);  
                $url = '/FahrzeugeVerwalten?modus=show&kfz_id=' . $newkfzID;
                return redirect()->to($url)->withInput();
            }
            
        // Dem Fahrzeug wird ein neuer Notizeintrag hinzugefügt.
        } elseif ($modus == 'insert' && $action == 'insertNoteButton'){
            
            if($this->insertNote()){
                $viewData['kennzeichen'] = $this->getKennzeichen();
                $viewData['fahrzeug'] = $this->getFahrzeugByID($kfzID);
                $viewData['allData'] = $this->getAllData();
                
                $this->showView($viewData);     
                $url = '/FahrzeugeVerwalten?modus=show&kfz_id=' . $kfzID;
                return redirect()->to($url)->withInput();
            }
                        
            
        // Das Fahrzeug wird gelöscht.
        } elseif ($modus == 'delete'){

            $this->deleteFahrzeug($kfzID);
            
            $viewData['kennzeichen'] = $this->getKennzeichen();
            $viewData['fahrzeug'] = $this->getFahrzeugRaw();
            $viewData['allData'] = $this->getAllData();
            $this->showView($viewData);
            
        // Die View wird ohne Fahrzeuginformationen angezeigt.
        } else {
           
        $viewData['kennzeichen'] = $this->getKennzeichen();
        $viewData['fahrzeug'] = $this->getFahrzeugRaw();
        $viewData['allData'] = $this->getAllData();
        $this->showView($viewData);
            
        }

    }
    
    /**
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function changeSaveButton() {
        $kfzID = set_value('kfz_id');
        $url = '/FahrzeugeVerwalten?modus=change&action=changeSaveButton&kfz_id=' . $kfzID;
        return redirect()->to($url)->withInput();
    }
    
    /**
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function insertSaveButton() {
        $url = '/FahrzeugeVerwalten?modus=insert&action=insertSaveButton';
        return redirect()->to($url)->withInput();
    }
    
    /**
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function insertNoteButton() {
        $kfzID = set_value('kfz_id');
        $url = '/FahrzeugeVerwalten?modus=insert&action=insertNoteButton&kfz_id=' . $kfzID;
        return redirect()->to($url)->withInput();
    }
    
    /**
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function deleteSaveButton() {
        $kfzID = set_value('kfz_id');        
        $url = '/FahrzeugeVerwalten?modus=delete&kfz_id=' . $kfzID;
        return redirect()->to($url)->withInput();
    }

    /** Den Anhängen wird eine Datei hinzugefügt
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function addFileAnhaenge() {
        
        $kfzID = set_value('hiddenKFZ_IDAnhaenge');        
        $fileName = $_FILES['fileAnhaenge']['name'];
        $fileTmpPath = $_FILES['fileAnhaenge']['tmp_name'];

        $savePath = 'Anhaenge/' . $kfzID . '/' . $fileName;
        if(move_uploaded_file($fileTmpPath, $savePath));
        else ;
        
        session()->setFlashdata('OK', 'Die Datei wurde erfolgreich angelegt.');
        $url = '/FahrzeugeVerwalten?modus=show&kfz_id=' . $kfzID;
        return redirect()->to($url)->withInput();
    }
    
    /** Aus den Anhängen wird eine Datei gelöscht.
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function deleteFileAnhaenge() {
        
        $kfzID = set_value('hiddenKFZ_IDAnhaenge');
        $deleteFiles = set_value('deleteFilesAnhaenge[]');
        
        foreach ($deleteFiles as $file){
            $deletePath = 'Anhaenge/' . $kfzID . '/' . $file;
            
            if (unlink($deletePath));
            else ;
        }
        
        $url = '/FahrzeugeVerwalten?modus=show&kfz_id=' . $kfzID;
        return redirect()->to($url)->withInput();
    }
    
    /** Den Schäden wird eine Datei hinzugefügt.
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function addFileSchaeden() {
        
        $kfzID = set_value('hiddenKFZ_IDSchaeden');
        $fileName = $_FILES['fileSchaeden']['name'];
        $fileTmpPath = $_FILES['fileSchaeden']['tmp_name'];
        
        $savePath = 'Schaeden/' . $kfzID . '/' . $fileName;
        if(move_uploaded_file($fileTmpPath, $savePath));
        else ;
        
        session()->setFlashdata('OK', 'Die Datei wurde erfolgreich angelegt.');
        $url = '/FahrzeugeVerwalten?modus=show&kfz_id=' . $kfzID;
        return redirect()->to($url)->withInput();
    }
    
    /** Aus den Schäden wird eine Datei gelöscht.
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function deleteFileSchaeden() {
        
        $kfzID = set_value('hiddenKFZ_IDSchaeden');
        $deleteFiles = set_value('deleteFilesSchaeden[]');
        
        foreach ($deleteFiles as $file){
            $deletePath = 'Schaeden/' . $kfzID . '/' . $file;
            
            if (unlink($deletePath));
            else ;
        }
        
        $url = '/FahrzeugeVerwalten?modus=show&kfz_id=' . $kfzID;
        return redirect()->to($url)->withInput();
    }
    
    /** Die View wird mit neuen Inhalten neu geladen.
     * 
     * @param $viewData
     */
    private function showView($viewData) {
        
        echo view('templates/header');
        echo view('templates/navigation');
        echo view('fahrzeuge_verwalten_view', $viewData);
        echo view('templates/footer');
    }
    
    /** Alle Dropdown Felder haben ihre eigene Tabelle.
     * Diese Tabellen werden in ein großes Array geschreiben und an die View übergeben.
     * @return array[] $allData
     */
    private function getAllData(){
        
        $allData = [
            'hersteller' => $this->getTableByName('t_hersteller'),
            'modell' => $this->getTableByName('t_modell'),
            'kfz_details' => $this->getTableByName('t_kfz_details'),
            'zusatzinformationen' => $this->getTableByName('t_zusatzinformationen'),
            'art' => $this->getTableByName('t_art'),
            'beschaffung' => $this->getTableByName('t_beschaffung'),
            'kraftstoff' => $this->getTableByName('t_kraftstoff'),
            'firma' => $this->getTableByName('t_firma'),
            'besitzer' => $this->getTableByName('t_besitzer'),
            'leasingNehmer' => $this->getTableByName('t_leasing_nehmer'),
            'leasingGeber' => $this->getTableByName('t_leasinggeber'),
            'versicherungGeber' => $this->getTableByName('t_versicherungsgeber'),
            'versicherungNehmer' => $this->getTableByName('t_versicherungsnehmer'),
            'versicherungZeitraum' => $this->getTableByName('t_versicherungszeitraum'),
            'notizen' => $this->getTableByName('t_notizen'),
        ];
        
        return $allData;
    }
    
    /** Eine Tabelle wird anhand des Namens ausgelesen und als Array zurückgegeben. 
     * 
     * @param $table
     * @return array[]
     */
    private function getTableByName($table){
        $dbGroup = ServerInfo::getDbGroup();
        $db1 = \Config\Database::connect($dbGroup);
        $builder1 = $db1->table($table);
        
        $query1   = $builder1->get();
        $fahrzeuge = $query1->getResult();
        
        $tableArray= array();
        
        // Jeder Eintrag aus der Tabelle wird einzeln in das Array $tableArray gepusht
        foreach($fahrzeuge as $fahrzeug){
            // Das Array wird doppelt konvertiert um sicherzustellen, dass auch alle Unterobjekte richtig konvertiert werden.
            $row = json_decode(json_encode($fahrzeug), true);
            
            array_push($tableArray, $row);
            
        }
        
        return $tableArray;
    }
    
    /** Die Fahrzeugdaten werden aus der View gelesen, auf Validierungsfehler überprüft und in einem Array zurückgegeben.
     *  Zahlen und Datum-Felder werden bei leerem Inhalt auf NULL gesetzt.
     * 
     * @return array[]
     */
    private function getFahrzeugData(){
        
        $kennzeichen = old('kennzeichen');
        $hersteller = old('dropDownHersteller');
        $modell = old('dropDownModell');
        $kfz_details = old('dropDownDetails');
        $zusatzinforamtionen = old('dropDownInformation');
        $fahrgestellnummer = old('fahrgestellnummer');
        $art = old('dropDownArt');
        $beschaffung = old('dropDownBeschaffung');
        $kaufdatum = old('kaufdatum');
        $baujahr = old('baujahr');
        $erstzulassung = old('erstzulassung');
        $leistungPS = old('leistungPS');
        $leistungKW = old('leistungKW');
        $kraftstoff = old('dropDownKraftstoff');
        $bruttoListenpreis = old('bruttoListenpreis');
        $naechsteHU = old('naechsteHU');
        $firma = old('dropDownFirma');
        $besitzer = old('dropDownBesitzer');
        $innenauftrag = old('innenauftrag');
        $activity = (int)old('activity');
                
        $valData = array(            
            'Kennzeichen' => $kennzeichen,
            'Hersteller' => $hersteller,
            'Modell' => $modell,
            'FIN' => $fahrgestellnummer,
            'Erstzulassung' => $erstzulassung,
            'PS' => $leistungPS,
            'KW' => $leistungKW,
            'Bruttolistenpreis' => $bruttoListenpreis,
            'Innenauftrag' => $innenauftrag,
        );
        
        $validation =  \Config\Services::validation();
        $validation->run($valData, 'fahrzeugData');
        $valErrors = $validation->getErrors();
        
        $errorText = '';
        // Für jeden Validierungsfehler wird der String in der Session erweitert
        if ($valErrors) {
            foreach($valErrors as $key => $val){
                $errorText = $errorText . '<br>' . $val . ' -> ' . $key;
            }
            session()->setFlashdata('fahrzeugError', 'Bei der Validierung sind Fehler aufgetreten!' . $errorText);
            
        }
        
        if($kaufdatum == '')$kaufdatum= null;
        if($baujahr == '')$baujahr= null;
        if($erstzulassung == '')$erstzulassung = null;
        if($naechsteHU== '')$naechsteHU = null;
        if($leistungPS == '')$leistungPS = null;
        if($leistungKW == '')$leistungKW = null;
        if($bruttoListenpreis == '')$bruttoListenpreis = null;
        if($fahrgestellnummer == '')$fahrgestellnummer = null;
        if($modell == '')$modell = 231;  
        
        $updateData = [
            'kennzeichen' => $kennzeichen,
            'fahrgestellnummer' => $fahrgestellnummer,
            'kaufdatum' => $kaufdatum,
            'baujahr' => $baujahr,
            'erstzulassung' => $erstzulassung,
            'leistung_ps' => $leistungPS,
            'leistung_kw' => $leistungKW,
            'brutto_listenpreis' => $bruttoListenpreis,
            'naechste_hu' => $naechsteHU . '-01',
            'innenauftrag' => $innenauftrag,
            'fahrzeug_inaktiv' => $activity,
            'hersteller_id' => $hersteller,
            'modell_id' => $modell,
            'kfz_details_id' => $kfz_details,
            'zusatzinformationen_id' => $zusatzinforamtionen,
            'art_id' => $art,
            'beschaffung_id' => $beschaffung,
            'kraftstoff_id' => $kraftstoff,
            'firma_id' => $firma,
            'besitzer_id' => $besitzer
        ];
        
        // Wenn ein Dropdown Feld nicht gefüllt ist wird es mit dem Wert 0 in der Datenbank gespeichert.
        foreach($updateData as $spalte => $value){
            if(str_ends_with($spalte, '_id') && empty($value)){
                $updateData[$spalte] = 0;
            }
        }
        return $updateData;        
    }
    

    /** Die Leasingdaten werden aus der View gelesen, auf Validierungsfehler überprüft und in einem Array zurückgegeben.
     *  Zahlen und Datum-Felder werden bei leerem Inhalt auf NULL gesetzt.
     *  
     * @return array[]
     */
    private function getLeasingData(){
        
        $leasingGeber = old('dropDownLeasinggeber');
        $leasingNehmer = old('dropDownLeasingnehmer');
        $leasingVertragsnummer = old('LeasingVertragsnummer');
        $leasingStart = old('leasingStart');
        $leasingEnde = old('leasingEnde');
        $leasingLaufleistung = old('LeasingLaufleistung');
        $mietsonderzahlung = old('mietsonderzahlung');
        $finanzrate = old('finanzrate');
        $wartungspauschale = old('wartungspauschale');
        $schadensmanagement = old('schadensmanagement');
        $reifenpauschale = old('reifenpauschale');
        $leasingkosten = old('leasingkosten');
        
        
        $valData = array(
            'Leasingvertragsnummer' => $leasingVertragsnummer,
            'Leasing Laufleistung' => $leasingLaufleistung,
            'Mietsonderzahlung' => $mietsonderzahlung,
            'Leasingrate monatlich' => $finanzrate,
            'Wartungspauschale' => $wartungspauschale,
            'Schadenspauschale' => $schadensmanagement,
            'Reifenpauschale' => $reifenpauschale,
            'Leasingkosten monatlich' => $leasingkosten,
        );
        
        $validation =  \Config\Services::validation();
        $validation->run($valData, 'leasingData');
        $valErrors = $validation->getErrors();
        
        $errorText = '';
        // Für jeden Validierungsfehler wird der String in der Session erweitert
        if ($valErrors) {
            foreach($valErrors as $key => $val){
                $errorText = $errorText . '<br>' . $val . ' -> ' . $key;
            }
            session()->setFlashdata('fahrzeugError', 'Bei der Validierung sind Fehler aufgetreten!' . $errorText);
        }
        
        
        if($leasingStart == '')$leasingStart = null;
        if($leasingEnde== '')$leasingEnde= null;
        if($leasingVertragsnummer == '')$leasingVertragsnummer = null;
        if($leasingLaufleistung== '')$leasingLaufleistung= null;
        if($mietsonderzahlung == '')$mietsonderzahlung = null;
        if($finanzrate== '')$finanzrate= null;
        if($wartungspauschale == '')$wartungspauschale = null;
        if($schadensmanagement== '')$schadensmanagement= null;
        if($reifenpauschale == '')$reifenpauschale = null;
        if($leasingkosten== '')$leasingkosten= null;
                
        $leasing = [
            'leasing_geber_id' => $leasingGeber,
            'leasing_nehmer_id' => $leasingNehmer,
            'leasing_vertragsnummer' => $leasingVertragsnummer,
            'leasing_start' => $leasingStart,
            'leasing_ende' => $leasingEnde,
            'leasing_laufleistung' => $leasingLaufleistung,
            'mietsonderzahlung' => $mietsonderzahlung,
            'finanzrate_monatlich' => $finanzrate,
            'wartungspauschale' => $wartungspauschale,
            'schadensmanagement' => $schadensmanagement,
            'reifenpauschale' => $reifenpauschale,
            'leasingkosten_monatlich' => $leasingkosten
        ];
        
        // Wenn ein Dropdown Feld nicht gefüllt ist wird es mit dem Wert 0 in der Datenbank gespeichert.
        foreach($leasing as $spalte => $value){
            if(str_ends_with($spalte, '_id') && empty($value)){
                $leasing[$spalte] = 0;
            }
        }
        return $leasing;
    }
    
    /** Die Leasingdaten werden aus der View gelesen, auf Validierungsfehler überprüft und in einem Array zurückgegeben.
     *  Zahlen und Datum-Felder werden bei leerem Inhalt auf NULL gesetzt.
     * 
     * @return array[]
     */
    private function getVersicherungData(){
        
        $versicherungGeber = old('dropDownVersicherunggeber');
        $versicherungNehmer = old('dropDownVersicherungnehmer');
        $versicherungSchein = old('versicherungSchein');
        $selbstbeteiligungTK = old('selbstbeteiligungTK');
        $selbstbeteiligungVK = old('selbstbeteiligungVK');
        $kfzSteuer = old('kfzSteuer');
        $versicherungZeitraum = old('dropDownVersicherungZeitraum');
              
        $valData = array(
            'Versicherungsschein' => $versicherungSchein,
            'Selbstbeteiligung TK' => $selbstbeteiligungTK,
            'Selbstbeteiligung VK' => $selbstbeteiligungVK,
            'KFZ Steuer' => $kfzSteuer,
        );
        
        $validation =  \Config\Services::validation();
        $validation->run($valData, 'versicherungData');
        $valErrors = $validation->getErrors();
        
        $errorText = '';
        // Für jeden Validierungsfehler wird der String in der Session erweitert
        if ($valErrors) {
            foreach($valErrors as $key => $val){
                $errorText = $errorText . '<br>' . $val . ' -> ' . $key;
            }
            session()->setFlashdata('fahrzeugError', 'Bei der Validierung sind Fehler aufgetreten!' . $errorText);
        }
        
        if($versicherungSchein == '')$versicherungSchein = null;
        if($selbstbeteiligungTK== '')$selbstbeteiligungTK= null;
        if($selbstbeteiligungVK == '')$selbstbeteiligungVK = null;
        if($kfzSteuer== '')$kfzSteuer= null;
        
        $versicherung = [
            'versicherung_geber_id' => $versicherungGeber,
            'versicherung_nehmer_id' => $versicherungNehmer,
            'versicherung_zeitraum_id' => $versicherungZeitraum,
            'versicherungsschein' => $versicherungSchein,
            'selbstbeteiligung_tk' => $selbstbeteiligungTK,
            'selbstbeteiligung_vk' => $selbstbeteiligungVK,
            'kfz_steuer' => $kfzSteuer
        ];
        
        // Wenn ein Dropdown Feld nicht gefüllt ist wird es mit dem Wert 0 in der Datenbank gespeichert.
        foreach($versicherung as $spalte => $value){
            if(str_ends_with($spalte, '_id') && empty($value)){
                $versicherung[$spalte] = 0;
            }
        }
        return $versicherung;
    }
    
    /** Die Notizen werden aus der View gelesen um sie anschließend zu updaten
     * @return NULL|array[]
     */
    private function getNotizenData(){
        $notizen_KFZ_ID = old('notizen_kfz_id');
        $notizenDatum = old('notizen_datum');
        $notizenText = old('notizen_text');
        $notizenArray = [];
        
        // Wenn keine Noltiz_KFZ_ID existiert, gibt es zu dem Fahrzeug auch keine Notizen.
        if(!$notizen_KFZ_ID) return null;
        
        // Jede einzelne Notiz muss validiert werden
        foreach($notizenText as $text){
            $valData = array(
                'Notizen' => $text
            );
            
            $validation =  \Config\Services::validation();
            $validation->run($valData, 'notizenData');
            $valErrors = $validation->getErrors();
            
            $errorText = '';
            // Für jeden Validierungsfehler wird der String in der Session erweitert
            if ($valErrors) {
                foreach($valErrors as $key => $val){
                    $errorText = $errorText . '<br>' . $val . ' -> ' . $key;
                }
                session()->setFlashdata('fahrzeugError', 'Bei der Validierung sind Fehler aufgetreten!' . $errorText);
            }            
        }
        
        for($x = 0; $x < count($notizen_KFZ_ID); $x++){
            if($notizenDatum[$x] == '')$notizenDatum[$x] = null;
            $notizenArray[] = [
                'notizen_kfz_id' => $notizen_KFZ_ID[$x],
                'datum' => $notizenDatum[$x],
                'notizen_text' => $notizenText[$x]
            ];            
        }        
   
        return $notizenArray;
    }

    /** Ein Fahrzeug kann mehrere Notizen haben.
     *  Diese Funktion gib ein Array mit allen Notiz_IDs zurück die das Fahrzeug hat. 
     * @return NULL|array[]
     */
    private function getNotizenIDs(){
        $notizenID = old('notizen_id');
        $noteArray = [];
        
        if(!$notizenID) return null;
        
        foreach ($notizenID as $note){
            $noteArray[] = $note;
        }
        return $noteArray;
    }
    
    /** Fahrzeug wird geupdated
     * @param $kfzID
     * @return boolean
     */
    private function updateFahrzeug($kfzID){
                
        $updateData = $this->getFahrzeugData();
        $versicherung= $this->getVersicherungData();
        $leasing = $this->getLeasingData();
        $notizen = $this->getNotizenData();
        $noteArrayID= $this->getNotizenIDs();
        
        // Bei Validierungsfehlern werden die aktuellen Änderungen aus der View biebehalten und das zu ändernde Fahrzeug wieder angezeigt. 
        if(session()->getFlashdata('fahrzeugError')){
            
            $updateData['kfz_id'] = $kfzID;
            $viewData['kennzeichen'] = $this->getKennzeichen();
            $viewData['allData'] = $this->getAllData();
            
            // Ein Array das gleich NULL ist kann nicht gemerged werden
            if($notizen == null) $viewData['fahrzeug'] = array_merge($updateData, $versicherung, $leasing);                
            else $viewData['fahrzeug'] = array_merge($updateData, $versicherung, $leasing, $notizen);
            
            $viewData['kennzeichen'] = $this->getKennzeichen();
            $viewData['allData'] = $this->getAllData();
            
            $this->showView($viewData);
            return false;
        }
        
        // Die ID des Leasings und der Versicherung werden aus den Arrays einer Variablen zugewiesen.
        $leasingArray = $this->getTableByName('t_leasing');        
        $leasingID = $leasingArray[0]['leasing_id'];
        
        $versicherungArray = $this->getTableByName('t_versicherung');
        $versicherungID = $versicherungArray[0]['versicherung_id'];   
                
        
        try {
            // Verbindung zur Datenbank wird aufgebaut
            $dbGroup = ServerInfo::getDbGroup();
            $db1 = \Config\Database::connect($dbGroup);
    
            // Die einzelnen Tabellen werden geupdated
            $builder = $db1->table('t_fahrzeuge');
            $builder->update($updateData, ['kfz_id' => $kfzID]);
            
            $builder = $db1->table('t_versicherung');
            $builder->update($versicherung,['versicherung_id' => $versicherungID]);
            
            $builder = $db1->table('t_leasing');
            $builder->update($leasing, ['leasing_id' => $leasingID]);
            
            // Bei den Notizen wird jede einzelnen Notiz geupdated.
            $count = 0;
            $builder = $db1->table('t_notizen');
            if(!$notizen == null){
                foreach ($notizen as $note){
                    $builder->update($note, ['notizen_id' => $noteArrayID[$count]]);
                    $count++;
                }
            }
                       
                if ($db1->transStatus() === FALSE) {
                    // Wenn ein Fehler aufgetreten ist, wird alles zurückgerollt
                    throw new \Exception('Transaktion fehlgeschlagen.');
                }
        } catch (Exception $e) {
            session()->setFlashdata('msgValidationError', 'Beim Updaten des Fahrzeugs ist ein Fehler aufgetreten.');
            return false;
        }
        session()->setFlashdata('OK', 'Das Fahrzeug wurde erfolgreich aktualisiert.');
        return true;
        
    }
    
    /** Ein neues Fahrzeug wird erstellt. 
     *  Durch die Rückgabe der kfzID kann das erstellte Fahrzeug direkt angezeigt werden.
     *  Konnte kein Fahrzeug erstellt werden wird 0 zurückgegeben 
     * 
     * @return number
     */
    private function insertFahrzeug(){
        $updateData = $this->getFahrzeugData();
        $versicherung = $this->getVersicherungData();
        $leasing = $this->getLeasingData();
        $newNote = $this->getNewNote();
        
        // Bei Validierungsfehlern wird der Insert abgebrochen.
        if(session()->getFlashdata('fahrzeugError')){

            $updateData["kfz_id"] = "";
            // Ein Array das gleich NULL ist kann nicht gemerged werden
            if($newNote == null) $viewData['fahrzeug'] = array_merge($updateData, $versicherung, $leasing);
            else $viewData['fahrzeug'] = array_merge($updateData, $versicherung, $leasing, $newNote);

            $viewData['kennzeichen'] = $this->getKennzeichen();
            $viewData['allData'] = $this->getAllData();
            
            $this->showView($viewData);
            // Der Rückgebewert dient als false Wert um beim Aufruf der Funktion zu erkennen, ob die Initialisierung erfolgreich war.
            return 0;
        }
        
        $dbGroup = ServerInfo::getDbGroup();
        $db1 = \Config\Database::connect($dbGroup);

        try {
            
            // Als erstes muss der neue Eintrag der Leasing- und Versicherung-Tabelle hinzugefügt werden, da die IDs per auto increment gefüllt werden.
            // Außerdem wird die neu generierte ID direkt dem Array für die Fahrzeug-Tabelle angehängt. 
            $builder = $db1->table('t_versicherung');
            $builder->insert($versicherung);
            $versicherungArray   = $builder->get();
            $versicherungRow = $versicherungArray->getLastRow('array');
            $updateData = array_merge($updateData, ["versicherung_id" => $versicherungRow['versicherung_id']]);
            
            $builder = $db1->table('t_leasing');
            $builder->insert($leasing);
            $leasingArray   = $builder->get();
            $leasingRow = $leasingArray->getLastRow('array');
            $updateData = array_merge($updateData, ["leasing_id" => $leasingRow['leasing_id']]);
            
            // Nach Leasing und Versicherung kann dann auch der Eintrag in die Fahrzeug-Tabelle geschrieben werden. 
            $builder = $db1->table('t_fahrzeuge');            
            $builder->insert($updateData);
            $kfzID = $db1->insertID();
            
            // Zuletzt wird der Eintrag in der Notizen-Tabelle eingetragen, da diese auf die KFZ-ID in der Fahrzeug-Tabelle referenziert. 
            $notizenArray   = $builder->get();
            $notizenRow = $notizenArray->getLastRow('array');
            $newNote = array_merge($newNote, ["notizen_kfz_id" => $notizenRow['kfz_id']]);
            
            $builder = $db1->table('t_notizen');            
            $builder->insert($newNote);
            if ($db1->transStatus() === FALSE) {
                // Wenn ein Fehler aufgetreten ist, wird alles zurückgerollt
                throw new \Exception('Transaktion fehlgeschlagen.');
                return 0;
            } else {
                
                // Wenn das Fahrzeug erfolgreich angelegt wurde, werden auch die Ordner angelegt
                $path = 'Anhaenge/' . $kfzID;
                if(mkdir($path, 777, true));
                else;
                
                $path = 'Schaeden/' . $kfzID;
                if(mkdir($path, 777, true));
                else;
            }
            
        } catch (Exception $e) {
            print($e);
            return 0;
        }
        session()->setFlashdata('OK', 'Das Fahrzeug wurde erfolgreich angelegt.');
        // Die ID wird zurückgegeben, um direkt das Fahrzeug anzuzeigen
        return $kfzID;
    }
    
    /** Ein Fahrzeug wird gelöscht.
     * @param $kfzID
     */
    private function deleteFahrzeug($kfzID){
        $dbGroup = ServerInfo::getDbGroup();
        $db1 = \Config\Database::connect($dbGroup);
        
        // Die Fahrzeug-Tabelle kan aufgrund der Referenzen zu Leasing und Versicherung nicht direkt gelöscht werden.
        $builder = $db1->table('t_fahrzeuge');
        $builder->where('kfz_id', $kfzID);
        $fahrzeuge   = $builder->get();
        $fahrzeugArray = $fahrzeuge->getRowArray();
        
        $leasingID = $fahrzeugArray['leasing_id'];
        $versicherungID = $fahrzeugArray['versicherung_id'];
        
        // Als erstes wird der Eintrag aus der Notizen-Tabelle gelöscht, da keine andere Tabelle auf sie verweist.
        $builder = $db1->table('t_notizen');
        $builder->delete(['notizen_kfz_id' => $kfzID]);

        // Danach kann der Eintrag aus der Fahrzeug-Tabelle gelöscht werden.
        $builder = $db1->table('t_fahrzeuge');
        $builder->delete(['kfz_id' => $kfzID]);
     
        // Zuletzt werden die Einträge aus Leasing und Versicherung gelöscht.
        $builder = $db1->table('t_versicherung');
        $builder->delete(['versicherung_id' => $versicherungID]);
        
        $builder = $db1->table('t_leasing');
        $builder->delete(['leasing_id' => $leasingID]);
        session()->setFlashdata('OK', 'Das Fahrzeug wurde erfolgreich gelöscht.');
    }
    
    /** Die Einträge aus einer neuen Notiz werden aus der View gelesen und in einem Array zurückgegeben.
     * 
     * @return array[]
     */
    private function getNewNote(){
        $kfzID = old('hidden_kfz_id');
        $notizenDatum = old('noteDate');
        $notizenText = old('noteText');
        
        $valData = array(
            'Notizen' => $notizenText
        );
        
        $validation =  \Config\Services::validation();
        $validation->run($valData, 'notizenData');
        $valErrors = $validation->getErrors();
        
        $errorText = '';
        // Für jeden Validierungsfehler wird der String in der Session erweitert
        if ($valErrors) {
            foreach($valErrors as $key => $val){
                $errorText = $errorText . '<br>' . $val . ' -> ' . $key;
            }
            session()->setFlashdata('fahrzeugError', 'Bei der Validierung sind Fehler aufgetreten!' . $errorText);
        }
        
        if($notizenDatum == '')$notizenDatum = null;
        
        $notizenArray = array();
        
        $notizenArray = [
            'notizen_kfz_id' => $kfzID,
            'datum' => $notizenDatum,
            'notizen_text' => $notizenText
        ];
        
        return $notizenArray;
    }
    
    /** Eine neue Notiz wird einem Fahrzeug hinzugefügt.
     * 
     * @return boolean
     */
    private function insertNote(){
        
        $newNote = $this->getNewNote();
                    
        $dbGroup = ServerInfo::getDbGroup();
        $db1 = \Config\Database::connect($dbGroup);
        
        try {
            
            $builder = $db1->table('t_notizen');
            $builder->insert($newNote);
            
            if ($db1->transStatus() === FALSE) {
                // Wenn ein Fehler aufgetreten ist, wird alles zurückgerollt
                throw new \Exception('Transaktion fehlgeschlagen.');
                session()->setFlashdata('fahrzeugError', 'Die Notiz konnte nicht angelegt werden.');
                return false;
            }
        } catch (Exception $e) {
            print($e);
            session()->setFlashdata('fahrzeugError', 'Die Notiz konnte nicht angelegt werden.');
            return false;
        }
        session()->setFlashdata('OK', 'Die Notiz wurde erfolgreich angelegt.');
        return true;
    }
    
    
    /** In dieser Funktion werden alle Felder aus der View mit einem leeren Wert gefüllt, 
     *  der dann standardmäßig angezeigt wird.
     *  Wird ein Array ohne keys übergeben kommt es in der View zu Fehlerlmeldungen.
     * 
     * @return string[]|number[]|array[]
     */
    private function getFahrzeugRaw() {
        
        $fahrerRaw = [
            "kennzeichen" => "",
            "kfz_id" => "",
            "hersteller_name" => "",
            "hersteller_id" => "",
            "modell_name" => "",
            "modell_id" => 231, // In der Datenbank ist dem Modell 231 kein Hersteller zugewiesen
            "kfz_details_name" => "",
            "kfz_details_id" => "",
            "zusatzinformationen_name" => "",
            "zusatzinformationen_id" => "",
            "fahrgestellnummer" => "",
            "art_name" => "",
            "art_id" => "",
            "beschaffung_name" => "",
            "beschaffung_id" => "",
            "kaufdatum" => "",
            "baujahr" => "",
            "erstzulassung" => "",
            "leistung_ps" => "",
            "leistung_kw" => "",
            "kraftstoff_name" => "",
            "kraftstoff_id" => "",
            "brutto_listenpreis" => "",
            "naechste_hu" => "",
            "firma_name" => "",
            "firma_id" => "",
            "besitzer_name" => "",
            "besitzer_id" => "",
            "innenauftrag" => "",
            "fahrzeug_inaktiv" => "",
            "hersteller" => [],
            "modell" => [],
            "kfz_details" => [],
            "zusatzinformationen" => [],
            "art" => [],
            "beschaffung" => [],
            "kraftstoff" => [],
            "firma" => [],
            "besitzer" => [],
            
            "leasing_id" => "",
            "leasing_vertragsnummer" => "",
            "leasing_start" => "",
            "leasing_ende" => "",
            "leasing_laufleistung" => "",
            "mietsonderzahlung" => "",
            "finanzrate_monatlich" => "",
            "wartungspauschale" => "",
            "schadensmanagement" => "",
            "reifenpauschale" => "",
            "leasingkosten_monatlich" => "",
            "leasing_nehmer_name" => "",
            "leasing_geber_name" => "",
            "leasing_nehmer_id" => "",
            "leasing_geber_id" => "",
            "leasingGeber" => [],
            "leasingNehmer" => [],
            
            "versicherung_id" => "",
            "versicherungsschein" => "",
            "selbstbeteiligung_tk" => "",
            "selbstbeteiligung_vk" => "",
            "kfz_steuer" => "",
            "versicherung_geber_name" => "",
            "versicherung_nehmer_name" => "",
            "versicherung_zeitraum_name" => "",
            "versicherung_geber_id" => "",
            "versicherung_nehmer_id" => "",
            "versicherung_zeitraum_id" => "",
            "versicherungGeber" => [],
            "versicherungNehmer" => [],
            "versicherungZeitraum" => []
        ];
        
        return $fahrerRaw;
    }
    
    /** Die Daten eines Fahrzeugs werden anhand der KFZ-ID in einem Array zurückgegebn.
     * @param $kfzID
     * @return array
     */
    private function getFahrzeugByID($kfzID) {
        
        $dbGroup = ServerInfo::getDbGroup();
        $db1 = \Config\Database::connect($dbGroup);
        $builder1 = $db1->table('t_fahrzeuge');
        
        // Alle Joins werden in einem Array gesammelt.
        $joins = [
            't_hersteller' => 't_hersteller.hersteller_id = t_fahrzeuge.hersteller_id',
            't_modell' => 't_modell.modell_id = t_fahrzeuge.modell_id',
            't_zusatzinformationen' => 't_zusatzinformationen.zusatzinformationen_id = t_fahrzeuge.zusatzinformationen_id',
            't_kraftstoff' => 't_kraftstoff.kraftstoff_id = t_fahrzeuge.kraftstoff_id',
            't_kfz_details' => 't_kfz_details.kfz_details_id = t_fahrzeuge.kfz_details_id',
            't_firma' => 't_firma.firma_id = t_fahrzeuge.firma_id',
            't_besitzer' => 't_besitzer.besitzer_id = t_fahrzeuge.besitzer_id',
            't_beschaffung' => 't_beschaffung.beschaffung_id = t_fahrzeuge.beschaffung_id',
            't_art' => 't_art.art_id = t_fahrzeuge.art_id',
            
            't_leasing' => 't_leasing.leasing_id = t_fahrzeuge.leasing_id',
            't_leasing_nehmer' => 't_leasing_nehmer.leasing_nehmer_id = t_leasing.leasing_nehmer_id',
            't_leasinggeber' => 't_leasinggeber.leasing_geber_id = t_leasing.leasing_geber_id',
            
            't_versicherung' => 't_versicherung.versicherung_id = t_fahrzeuge.versicherung_id',
            't_versicherungsgeber' => 't_versicherungsgeber.versicherung_geber_id = t_versicherung.versicherung_geber_id',
            't_versicherungsnehmer' => 't_versicherungsnehmer.versicherung_nehmer_id = t_versicherung.versicherung_nehmer_id',
            't_versicherungszeitraum' => 't_versicherungszeitraum.versicherung_zeitraum_id = t_versicherung.versicherung_zeitraum_id'
        ];
        
        // Jede Tabelle wird durch den Join aus dem Array dem biulder hinzugefügt.
        foreach ($joins as $table => $condition) {
            
            $builder1->join($table, $condition); 
        }
        
        // Der builder beschränkt sich nur auf die ausgewählte KFZ-ID
        $builder1->where('kfz_id', $kfzID);
        $fahrzeug = $builder1->get();
        // Zurück kommt ein Array mit der entsprechenden Key-Value Zuweisung.
        $fahrzeugArray = $fahrzeug->getRowArray();      
        
        // Das Feld wird neu formatiert, damit nur Jahr und Monat angezeigt werden. Dafür werden nur die ersten 7 Zeichen gebraucht. 
        $fahrzeugArray['naechste_hu'] = substr($fahrzeugArray['naechste_hu'], 0, 7);
        
        // Die Notizen müssen separat dem Array hinzugefügt werden, da die Tabelle keinen FK in der Fahrzeugtabelle hat. 
        $builder1 = $db1->table('t_notizen');
        $builder1->where('notizen_kfz_id', $kfzID);
        $notizen = $builder1->get();
        $notizenArray = $notizen->getResultArray();
        
        $fahrzeugArray = array_merge($fahrzeugArray, [
            "notizen" => $notizenArray
        ]);
                
        return $fahrzeugArray;
    }
    
    /** Für das Suchfeld werden alle Kennzeichen mit ID aus der Datenbank gelesen und zurückgegeben.
     * 
     * @return array[]
     */
    private function getKennzeichen() {
        
        $dbGroup = ServerInfo::getDbGroup();
        $db1 = \Config\Database::connect($dbGroup);
        $builder1 = $db1->table('t_fahrzeuge');
        
        $query1   = $builder1->get();
        $fahrzeuge = $query1->getResult();
        
        $kennzeichenArray = array();
        
        // Für jedes Fahrzeug wird dem Array das Kennzeichen und die ID hinzugefügt
        foreach($fahrzeuge as $fahrzeug){
            // Das Array wird doppelt konvertiert um sicherzustellen, dass auch alle Unterobjekte richtig konvertiert werden.
            $row = json_decode(json_encode($fahrzeug), true);
            
            $kennzeichen = [
                "kennzeichen" => $row['kennzeichen'],
                "kfz_id" => $row['kfz_id']
                
            ];
            
            array_push($kennzeichenArray, $kennzeichen);
            
        }	
        
        return $kennzeichenArray;
        
    }
    
    /** Die KFZ-ID des nächsten Fahrzeugs aus der Datenbank wird zurückgegeben.
     * 
     * @param $kfzID
     * @return $kfzID
     */
    private function getNextID($kfzID) {
        
        $fahrzeuge = $this->getKennzeichen();
        $index = 0;
        $maxLength = count($fahrzeuge)-1;        
        
        foreach($fahrzeuge as $fahrzeug){
            // Entspricht die ID dem letzten Fahrzeug im Array, wird wieder ganz nach vorne gesprungen
            if($index == $maxLength)return $fahrzeuge[0]['kfz_id'];
            $index++;
            // wurde das Fahrzeug mit der richtigen ID gefunden wird der index erhöht und die neue ID zurückgegeben
            if($fahrzeug['kfz_id'] == $kfzID) return $fahrzeuge[$index]['kfz_id'];
            
        }

    }
    
    /** Die KFZ-ID des vorherigen Fahrzeugs aus der Datenbank wird zurückgegeben.
     * 
     * @param $kfzID
     * @return $kfzID
     */
    private function getFormerID($kfzID) {
        
        $fahrzeuge = $this->getKennzeichen();
        $index = 0;
        $maxLength = count($fahrzeuge)-1; 
                
        foreach ($fahrzeuge as $fahrzeug){

            if($fahrzeug['kfz_id'] == $kfzID) {
                // Entspricht die ID dem ersten Fahrzeug wird die ID des letzten Index aus dem Arrays zurückgegeben.
                if($index == 0)return $fahrzeuge[$maxLength]['kfz_id'];                
                // wurde das Fahrzeug mit der richtigen ID gefunden wird der index um 1 veringert und die neue ID zurückgegeben
                return $fahrzeuge[$index-1]['kfz_id'];
            }
            $index++;
            
        }
        
    }
}
?>