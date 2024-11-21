<?php
namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Validation\StrictRules\CreditCardRules;
use CodeIgniter\Validation\StrictRules\FileRules;
use CodeIgniter\Validation\StrictRules\FormatRules;
use CodeIgniter\Validation\StrictRules\Rules;

class Validation extends BaseConfig
{

    // --------------------------------------------------------------------
    // Setup
    // --------------------------------------------------------------------

    /**
     * Stores the classes that contain the
     * rules that are available.
     *
     * @var string[]
     */
    public array $ruleSets = [
        Rules::class,
        FormatRules::class,
        FileRules::class,
        CreditCardRules::class
    ];

    /**
     * Specifies the views that are used to display the
     * errors.
     *
     * @var array<string, string>
     */
    public array $templates = [
        'list' => 'CodeIgniter\Validation\Views\list',
        'single' => 'CodeIgniter\Validation\Views\single'
    ];

    // --------------------------------------------------------------------
    // Rules
    // --------------------------------------------------------------------
    public $accountChangePassword = [
        'newPassword' => [
            'rules' => 'required|min_length[6]',
            'errors' => [
                'required' => 'Dies ist ein Pflichtfeld.',
                'min_length' => 'Min. Länge ist 6 Zeichen.'
            ]
        ],
        'confirmPassword' => [
            'rules' => 'required|min_length[6]|matches[newPassword]',
            'errors' => [
                'required' => 'Dies ist ein Pflichtfeld.',
                'min_length' => 'Min. Länge ist 6 Zeichen.',
                'matches' => 'Stimmt nicht mit << Neues Passwort >> überein.'
            ]
        ]
    ];

    public $authRole = [
        'authRoleDesc' => [
            'rules'  => 'required|max_length[255]',
            'errors' => [
                'required' => 'Dies ist ein Pflichtfeld.',
                'max_length' => 'Max. Länge ist 255 Zeichen.',
            ],
        ],
    ];
    
    
    public $user = [
        'u_first_name' => [
            'rules'  => 'required|max_length[30]|alpha_space',
            'errors' => [
                'required' => 'Dies ist ein Pflichtfeld.',
                'max_length' => 'Max. Länge ist 30 Zeichen.',
                'alpha_space' => 'Es sind nur Buchstaben erlaubt.',
            ],
        ],
        'u_last_name'    => [
            'rules'  => 'required|max_length[30]|alpha_space',
            'errors' => [
                'required' => 'Dies ist ein Pflichtfeld.',
                'max_length' => 'Max. Länge ist 30 Zeichen.',
                'alpha_space' => 'Es sind nur Buchstaben erlaubt.'
            ],
        ],
        'u_mail'    => [
            'rules'  => 'required|valid_email',
            'errors' => [
                'required' => 'Dies ist ein Pfichfeld.',
                'valid_email' => 'Gültige E-Mail eingeben.',
            ],
        ],
        'u_status' => [
            'rules'  => 'differs[empty]',
            'errors' => [                
                'differs' => 'Dies ist ein Pflichtfeld.',
            ],
        ],
        'u_authRoleId' => [
            'rules'  => 'differs[empty]',
            'errors' => [
                'differs' => 'Dies ist ein Pflichtfeld.',
            ],
        ],
    ];
    
    
    public $userInsert = [
        'u_first_name' => [
            'rules'  => 'required|max_length[30]',
            'errors' => [
                'required' => 'Dies ist ein Pflichtfeld.',
                'max_length' => 'Max. Länge ist 30 Zeichen.',
            ],
        ],
        'u_last_name'    => [
            'rules'  => 'required|max_length[30]',
            'errors' => [
                'required' => 'Dies ist ein Pflichtfeld.',
                'max_length' => 'Max. Länge ist 30 Zeichen.',
            ],
        ],
        'u_mail'    => [
            'rules'  => 'required|valid_email',
            'errors' => [
                'required' => 'Dies ist ein Pfichfeld.',
                'valid_email' => 'Gültige E-Mail eingeben.',
            ],
        ],
        'u_status' => [
            'rules'  => 'differs[empty]',
            'errors' => [
                'differs' => 'Dies ist ein Pflichtfeld.',
            ],
        ],
        'u_authRoleId' => [
            'rules'  => 'differs[empty]',
            'errors' => [
                'differs' => 'Dies ist ein Pflichtfeld.',
            ],
        ],
        'u_password' => [
            'rules'  => 'required|min_length[6]',
            'errors' => [
                'required' => 'Dies ist ein Pflichtfeld.',
                'min_length' => 'Min. Länge ist 6 Zeichen.',
            ],
        ],
    ];
    
    public $fahrzeugData = [
        'Kennzeichen'    => [
            'rules'  => [
                'max_length[10]',
                'required',
                'regex_match[/^[A-Z0-9-]+$/]',
            ],
            'errors' => [
                'max_length' => 'Das Kennzeichen darf aus maximal 10 Zeichen bestehen.',
                'required' => 'Dies ist ein Pflichtfeld',
                'regex_match' => 'Bitte geben Sie für das Kennzeichen gültige Eingaben ein',
            ],
        ],
        'FIN' => [
            'rules' => 'required|exact_length[17]|regex_match[/^[A-Z0-9]+$/]|regex_match[/^[^OIQ]*$/]',
            'errors' => [
                'required' => 'Dies ist ein Pflichtfeld.',
                'exact_length' => 'Das Feld muss genau 17 Zeichen lang sein.',
                'regex_match' => 'Das Feld darf nur Großbuchstaben enthalten und nicht O, I und Q.',
            ],
        ],
        'PS'    => [
            'rules'  => [
                'max_length[4]',
                'numeric',
                'integer',
                'permit_empty',
                'greater_than[0]'
            ],
            'errors' => [
                'max_length' => 'Es darf maximal eine 4-Stellige Zahl eingegeben werden.',
                'numeric' => 'Bitte nur Zahlen eingeben',
                'integer' => 'Bitte geben Sie nur ganze Zahlen ein',
                'greater_than' => 'Bitte nur Positive Zahlen eingeben'
            ],
        ],
        'KW' => [
            'rules'  => [
                'max_length[4]',
                'numeric',
                'integer',
                'permit_empty',
                'greater_than[0]'
            ],
            'errors' => [
                'max_length' => 'Es darf maximal eine 4-Stellige Zahl eingegeben werden.',
                'numeric' => 'Bitte nur Zahlen eingeben',
                'integer' => 'Bitte geben Sie nur ganze Zahlen ein',
                'greater_than' => 'Bitte nur Positive Zahlen eingeben'
            ],
        ],
        'Bruttolistenpreis'    => [
            'rules'  => [
                'max_length[8]',
                'numeric',
                'permit_empty',
                'greater_than[0]'
            ],
            'errors' => [
                'max_length' => 'Maximal 8 Zahlen',
                'numeric' => 'Bitte nur Zahlen eingeben',
                'greater_than' => 'Bitte nur Positive Zahlen eingeben'
            ],
        ],
        'Innenauftrag' => [
            'rules'  => 'max_length[100]',
            'errors' => [
                'max_length' => 'Maximal 100 Zeichen',
            ],
        ],
    ];
    
    public $leasingData = [
        'Leasingvertragsnummer' => [
            'rules'  => [
                'max_length[10]',
                'numeric',
                'permit_empty',
                'greater_than[0]'
            ],
            'errors' => [
                'max_length' => 'Maximal 10 Zahlen',
                'numeric' => 'Bitte nur Zahlen eingeben',
                'greater_than' => 'Bitte nur Positive Zahlen eingeben'
            ],
        ],
        'Leasing Laufleistung' => [
            'rules'  => [
                'max_length[10]',
                'numeric',
                'permit_empty',
                'greater_than[0]'
            ],
            'errors' => [
                'max_length' => 'Maximal 10 Zahlen',
                'numeric' => 'Bitte nur Zahlen eingeben',
                'greater_than' => 'Bitte nur Positive Zahlen eingeben'
            ],
        ],
        'Mietsonderzahlung'    => [
            'rules'  => [
                'max_length[10]',
                'numeric',
                'permit_empty',
                'greater_than[0]'
            ],
            'errors' => [
                'max_length' => 'Maximal 10 Zahlen',
                'numeric' => 'Bitte nur Zahlen eingeben',
                'greater_than' => 'Bitte nur Positive Zahlen eingeben'
            ],
        ],
        'Leasingrate monatlich' => [
            'rules'  => [
                'max_length[10]',
                'numeric',
                'permit_empty',
                'greater_than[0]'
            ],
            'errors' => [
                'max_length' => 'Maximal 10 Zahlen',
                'numeric' => 'Bitte nur Zahlen eingeben',
                'greater_than' => 'Bitte nur Positive Zahlen eingeben'
            ],
        ],
        'Wartungspauschale'    => [
            'rules'  => [
                'max_length[10]',
                'numeric',
                'permit_empty',
                'greater_than[0]'
            ],
            'errors' => [
                'max_length' => 'Maximal 10 Zahlen',
                'numeric' => 'Bitte nur Zahlen eingeben',
                'greater_than' => 'Bitte nur Positive Zahlen eingeben'
            ],
        ],
        'Schadenspauschale'    => [
            'rules'  => [
                'max_length[10]',
                'numeric',
                'permit_empty',
                'greater_than[0]'
            ],
            'errors' => [
                'max_length' => 'Maximal 10 Zahlen',
                'numeric' => 'Bitte nur Zahlen eingeben',
                'greater_than' => 'Bitte nur Positive Zahlen eingeben'
            ],
        ],
        'Reifenpauschale'    => [
            'rules'  => [
                'max_length[10]',
                'numeric',
                'permit_empty',
                'greater_than[0]'
            ],
            'errors' => [
                'max_length' => 'Maximal 10 Zahlen',
                'numeric' => 'Bitte nur Zahlen eingeben',
                'greater_than' => 'Bitte nur Positive Zahlen eingeben'
            ],
        ],
        'Leasingkosten monatlich'    => [
            'rules'  => [
                'max_length[10]',
                'numeric',
                'permit_empty',
                'greater_than[0]'
            ],
            'errors' => [
                'max_length' => 'Maximal 10 Zahlen',
                'numeric' => 'Bitte nur Zahlen eingeben',
                'greater_than' => 'Bitte nur Positive Zahlen eingeben'
            ],
        ],
    ];
    
    public $versicherungData = [
        'Versicherungsschein' => [
            'rules'  => [
                'max_length[10]',
                'numeric',
                'integer',
                'permit_empty'
            ],
            'errors' => [
                'max_length' => 'Maximal 10 Zahlen',
                'numeric' => 'Bitte nur Zahlen eingeben',
                'integer' => 'Bitte geben Sie nur ganze Zahlen ein',
            ],
        ],
        'Selbstbeteiligung TK'    => [
            'rules'  => [
                'max_length[10]',
                'numeric',
                'permit_empty',
                'greater_than[0]'
            ],
            'errors' => [
                'max_length' => 'Maximal 10 Zahlen',
                'numeric' => 'Bitte nur Zahlen eingeben',
                'greater_than' => 'Bitte nur Positive Zahlen eingeben'
            ],
        ],
        'Selbstbeteiligung VK'    => [
            'rules'  => [
                'max_length[10]',
                'numeric',
                'permit_empty',
                'greater_than[0]'
            ],
            'errors' => [
                'max_length' => 'Maximal 10 Zahlen',
                'numeric' => 'Bitte nur Zahlen eingeben',
                'greater_than' => 'Bitte nur Positive Zahlen eingeben'
            ],
        ],
        'KFZ Steuer'    => [
            'rules'  => [
                'max_length[10]',
                'numeric',
                'permit_empty',
                'greater_than[0]'
            ],
            'errors' => [
                'max_length' => 'Maximal 10 Zahlen',
                'numeric' => 'Bitte nur Zahlen eingeben',
                'greater_than' => 'Bitte nur Positive Zahlen eingeben'
            ],
        ],
    ];
    
    public $notizenData = [
        'Notizen' => [
            'rules'  => [
                'max_length[250]',
                'permit_empty'
            ],
            'errors' => [
                'max_length' => 'Maximal 250 Zeichen eingeben'
            ],
        ]
    ];


}
