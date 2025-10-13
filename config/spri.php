<?php

use Fuel\Core\Response;
use Fuel\Core\Router;
use Fuel\Core\View;

return [
    // ITU-Carrier-Code
    'icc' => 'DEU.SWL451',
    // S/PRI-Teilnehmer
    'partners' => [
        // Deutschland
        'DEU' => [
            // Travenetz
            'SWL451' => [
                'services' => [
                    'v4' => [
                        'wsdl' => Router::get('oass_schema_wsdl',  ['version' => 4]),
                        'endpoints' => [
                            'orderer' => false,
                            'supplier' => Router::get('oass_endpoint',  ['version' => 4]),
                        ],
                        'signature' => [
                            'issuer' => 'config:spri.partners.DEU.SWL451.signature.issuer',
                            'serial' => 'config:spri.partners.DEU.SWL451.signature.serial',
                            'certificate' => 'config:spri.partners.DEU.SWL451.certificate.x509',
                            'key' => 'config:spri.partners.DEU.SWL451.certificate.private_key',
                        ],
                    ]
                ],
                'signature' => [
                    'issuer' => 'CN=TraveNetz OASS, O=TraveNetz GmbH, L=Luebeck, ST=Schleswig-Holstein, C=DE',
                    'serial' => '781c4a6093c253b9b5a118307d9a83f3f70404b0', //'685709792537091294148641704270170925660054488240', //'0x781C4A6093C253B9B5A118307D9A83F3F70404B0',
                ],
                'certificate' => [
                    'x509' => '-----BEGIN CERTIFICATE-----
MIIExjCCAy6gAwIBAgIUeBxKYJPCU7m1oRgwfZqD8/cEBLAwDQYJKoZIhvcNAQEL
BQAwbjELMAkGA1UEBhMCREUxGzAZBgNVBAgMElNjaGxlc3dpZy1Ib2xzdGVpbjEQ
MA4GA1UEBwwHTHVlYmVjazEXMBUGA1UECgwOVHJhdmVOZXR6IEdtYkgxFzAVBgNV
BAMMDlRyYXZlTmV0eiBPQVNTMB4XDTI0MDkwNTEwMTI1N1oXDTM0MDkwMzEwMTI1
N1owbjELMAkGA1UEBhMCREUxGzAZBgNVBAgMElNjaGxlc3dpZy1Ib2xzdGVpbjEQ
MA4GA1UEBwwHTHVlYmVjazEXMBUGA1UECgwOVHJhdmVOZXR6IEdtYkgxFzAVBgNV
BAMMDlRyYXZlTmV0eiBPQVNTMIIBojANBgkqhkiG9w0BAQEFAAOCAY8AMIIBigKC
AYEA5rOc/Og2pJT94iq4jtOG/knXNu7GAosU4f9B8S4rUTQ/63yeZVafN3jIhaIV
QQNPsyZtLni8bazsL/xmQ1rP2kmboom1rQYzTKf8c1ZtPOBzvPYveP2Umn0ivSK0
PlHGzLS/xAGSWiW5hMO4VDVdLHTpbAIEdHFz7pGZjtIQ3JSUCEyAjfC0CK+0H6TX
OeKxSpEDHRUr1aLDEPG/xTOi7j8XuKWFOaRNiCFFvBrbJdSAzwdirHRr2i3CIUvP
VwVhbodZH1gsulmWV4ZUMDr9jm6W9XADV0Bj5L0nx74cxnfTUOLlcvzidnCHqj2p
W+uJO+VuzxBShq+/n0WeF0nD2aS7tX+3Qkl7obRpPAR2ew3a3x+nM8lj/yHqbQpZ
5m2sKRM+f4mwqWn0O4nqSR7EfKHE/kvGoFInh6ynI9uvdiCQ+5vprlxSFLyTj/z5
JkZmJuYdb1cyygACfHMm2wEvj6dRgSsu0rYkW3rTVyuNL2jSwY92N//xFZOOqybt
jKupAgMBAAGjXDBaMAkGA1UdEwQCMAAwCwYDVR0PBAQDAgXgMCEGA1UdEQQaMBiC
FnNydmtzb2EwMS50cmF2ZWtvbS5uZXQwHQYDVR0OBBYEFOizAtzDFkOL+vrRYreI
fNbODrQqMA0GCSqGSIb3DQEBCwUAA4IBgQB28C3xrMLC9q71udqqM1jvL2DwitaN
ACTejkxPTPUpsmEvK6tUa3vTmd0Sny6Al2AZ3exvmb30lmPAlff/srjJMSBOZ3kP
lPpQBuq9kkMkmZgUcNYAswQ2tOcTyLfwibkj09aDWQBJqchP1ru5ePBx150vYR/O
n+diJ9C2Cc8p0qxPOWUP1b2mGESf4jEX0yO7PYg4lMLueVX7dcq2Ea3MFm/6hH2s
PRln22J/5CFV2GMsUZKYA/y5t+uLvx0FOqxtT/9drmsHQIRoekOD0ZYALyfJKvKs
J0k0KAO9u2pkB3m6bz9bMwfsp+y/KOtKDIkKnZ2cSmMoCG0ulAJyKGc7yAMWtYPt
ORj2stsfp8pzUZWNNZv0kdICC6mO7SFvSr0BrQaTLWNTriSMqfT2mCqAU4uy0QR/
Muge5ggGUKuD7+SzYQ53OdsCLmYHIlWvv/icUojH8OfEGCfk73vyFqVLYyKpTmzI
4vrmtAtNjuC6Y4tNDWefbc+YcVZgis6sQ/M=
-----END CERTIFICATE-----',
                    'private_key' => '-----BEGIN PRIVATE KEY-----
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
DT/+3yIVkVkIfcw9Yjrubb8=
-----END PRIVATE KEY-----'
                ]
            ],
            // Enghouse S/PRI-Simulator
            'XCON' => [
                'services' => [
                    'v4' => [
                        'wsdl' => 'https://proxy.xc.en.enghousehosted.com/sprirs/4.3/CarrierService-orderer?wsdl',
                        'endpoints' => [
                            'orderer' => 'https://proxy.xc.en.enghousehosted.com/sprirs/4.3/CarrierService-orderer',
                            'supplier' => false,
                        ],
                        'signature' => [
                            'issuer' => 'config:spri.partners.DEU.XCON.signature.issuer',
                            'serial' => 'config:spri.partners.DEU.XCON.signature.serial',
                        ],
                    ]
                ],
                'signature' => [
                    'issuer' => 'CN=Enghouse Systems Ltd., OU=SPRI RS, O=Enghouse Systems Ltd., L=DUEREN, ST=NRW, C=DE',
                    'serial' => '71850639',
                ],
            ],
            // 1&1 Versatel
            'VTEL' => [
                'services' => [
                    'v4' => [
                        'wsdl' => Router::get('oass_wsdl_partner',  ['version' => 4, 'icc' => 'DEU.VTEL']), //'https://ws-interfaces.1und1.net:443/wita/spri/4/service?wsdl',
                        'endpoints' => [
                            'orderer' => 'https://ws-interfaces.1und1.net:443/wita/spri/4/service',
                            'supplier' => false,
                        ],
                        'signature' => [
                            'issuer' => 'config:spri.partners.DEU.VTEL.signature.issuer',
                            'serial' => 'config:spri.partners.DEU.VTEL.signature.serial',
                        ],
                    ]
                ],
                'signature' => [
                    'issuer' => 'CN=Versatel WITA WebService - Development, OU=IT, O=Versatel, L=Flensburg, S=SH, C=DE',
                    'serial' => '3f633e03',
                ],
            ],
            'AVA' => [
                'services' => [
                    'v4' => [
                        'wsdl' => Router::get('oass_wsdl_partner',  ['version' => 4, 'icc' => 'DEU.AVA']),
                        'endpoints' => [
                            'orderer' => 'http://localhost/soap/v4/orderer',
                            'supplier' => false,
                        ],
                        'signature' => [
                            'issuer' => 'config:spri.partners.DEU.AVA.signature.issuer',
                            'serial' => 'config:spri.partners.DEU.AVA.signature.serial',
                            'certificate'   => 'config:spri.partners.DEU.AVA.certificate.x509',
                            'key'           => 'config:spri.partners.DEU.AVA.certificate.private_key',
                        ],
                    ]
                ],
                'version' => [
                    'preferred' => [
                        'major' => 4,
                        'minor' => 30
                    ]
                ],
                'signature' => [
                    'issuer' => 'CN=AVAible Systems, OU=SPRI RS, O=AVAible Systems, L=Luebeck, ST=Schleswig-Holstein, C=DE',
                    'serial' => '2345766675432',
                ],
                'certificate' => [
                    'x509' => '-----BEGIN CERTIFICATE-----
MIIExjCCAy6gAwIBAgIUeBxKYJPCU7m1oRgwfZqD8/cEBLAwDQYJKoZIhvcNAQEL
BQAwbjELMAkGA1UEBhMCREUxGzAZBgNVBAgMElNjaGxlc3dpZy1Ib2xzdGVpbjEQ
MA4GA1UEBwwHTHVlYmVjazEXMBUGA1UECgwOVHJhdmVOZXR6IEdtYkgxFzAVBgNV
BAMMDlRyYXZlTmV0eiBPQVNTMB4XDTI0MDkwNTEwMTI1N1oXDTM0MDkwMzEwMTI1
N1owbjELMAkGA1UEBhMCREUxGzAZBgNVBAgMElNjaGxlc3dpZy1Ib2xzdGVpbjEQ
MA4GA1UEBwwHTHVlYmVjazEXMBUGA1UECgwOVHJhdmVOZXR6IEdtYkgxFzAVBgNV
BAMMDlRyYXZlTmV0eiBPQVNTMIIBojANBgkqhkiG9w0BAQEFAAOCAY8AMIIBigKC
AYEA5rOc/Og2pJT94iq4jtOG/knXNu7GAosU4f9B8S4rUTQ/63yeZVafN3jIhaIV
QQNPsyZtLni8bazsL/xmQ1rP2kmboom1rQYzTKf8c1ZtPOBzvPYveP2Umn0ivSK0
PlHGzLS/xAGSWiW5hMO4VDVdLHTpbAIEdHFz7pGZjtIQ3JSUCEyAjfC0CK+0H6TX
OeKxSpEDHRUr1aLDEPG/xTOi7j8XuKWFOaRNiCFFvBrbJdSAzwdirHRr2i3CIUvP
VwVhbodZH1gsulmWV4ZUMDr9jm6W9XADV0Bj5L0nx74cxnfTUOLlcvzidnCHqj2p
W+uJO+VuzxBShq+/n0WeF0nD2aS7tX+3Qkl7obRpPAR2ew3a3x+nM8lj/yHqbQpZ
5m2sKRM+f4mwqWn0O4nqSR7EfKHE/kvGoFInh6ynI9uvdiCQ+5vprlxSFLyTj/z5
JkZmJuYdb1cyygACfHMm2wEvj6dRgSsu0rYkW3rTVyuNL2jSwY92N//xFZOOqybt
jKupAgMBAAGjXDBaMAkGA1UdEwQCMAAwCwYDVR0PBAQDAgXgMCEGA1UdEQQaMBiC
FnNydmtzb2EwMS50cmF2ZWtvbS5uZXQwHQYDVR0OBBYEFOizAtzDFkOL+vrRYreI
fNbODrQqMA0GCSqGSIb3DQEBCwUAA4IBgQB28C3xrMLC9q71udqqM1jvL2DwitaN
ACTejkxPTPUpsmEvK6tUa3vTmd0Sny6Al2AZ3exvmb30lmPAlff/srjJMSBOZ3kP
lPpQBuq9kkMkmZgUcNYAswQ2tOcTyLfwibkj09aDWQBJqchP1ru5ePBx150vYR/O
n+diJ9C2Cc8p0qxPOWUP1b2mGESf4jEX0yO7PYg4lMLueVX7dcq2Ea3MFm/6hH2s
PRln22J/5CFV2GMsUZKYA/y5t+uLvx0FOqxtT/9drmsHQIRoekOD0ZYALyfJKvKs
J0k0KAO9u2pkB3m6bz9bMwfsp+y/KOtKDIkKnZ2cSmMoCG0ulAJyKGc7yAMWtYPt
ORj2stsfp8pzUZWNNZv0kdICC6mO7SFvSr0BrQaTLWNTriSMqfT2mCqAU4uy0QR/
Muge5ggGUKuD7+SzYQ53OdsCLmYHIlWvv/icUojH8OfEGCfk73vyFqVLYyKpTmzI
4vrmtAtNjuC6Y4tNDWefbc+YcVZgis6sQ/M=
-----END CERTIFICATE-----',
                    'private_key' => '-----BEGIN PRIVATE KEY-----
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
DT/+3yIVkVkIfcw9Yjrubb8=
-----END PRIVATE KEY-----'
                ]
            ],
            'OAPS' => [
                'services' => [
                    'v4' => [
                        'wsdl' => Router::get('oass_wsdl_partner',  ['version' => 4, 'icc' => 'DEU.OAPS']),
                        'endpoints' => [
                            'orderer' => 'http://localhost/soap/v4/orderer',
                            'supplier' => false,
                        ],
                        'signature' => [
                            'issuer' => 'config:spri.partners.DEU.OAPS.signature.issuer',
                            'serial' => 'config:spri.partners.DEU.OAPS.signature.serial',
                            'certificate'   => 'config:spri.partners.DEU.OAPS.certificate.x509',
                            'key'           => 'config:spri.partners.DEU.OAPS.certificate.private_key',
                        ],
                    ]
                ],
                'version' => [
                    'preferred' => [
                        'major' => 4,
                        'minor' => 30
                    ]
                ],
                'signature' => [
                    'issuer' => 'CN=AVAible Systems, OU=SPRI RS, O=AVAible Systems, L=Luebeck, ST=Schleswig-Holstein, C=DE',
                    'serial' => '2345766675432',
                ],
                'certificate' => [
                    'x509' => '-----BEGIN CERTIFICATE-----
MIIExjCCAy6gAwIBAgIUeBxKYJPCU7m1oRgwfZqD8/cEBLAwDQYJKoZIhvcNAQEL
BQAwbjELMAkGA1UEBhMCREUxGzAZBgNVBAgMElNjaGxlc3dpZy1Ib2xzdGVpbjEQ
MA4GA1UEBwwHTHVlYmVjazEXMBUGA1UECgwOVHJhdmVOZXR6IEdtYkgxFzAVBgNV
BAMMDlRyYXZlTmV0eiBPQVNTMB4XDTI0MDkwNTEwMTI1N1oXDTM0MDkwMzEwMTI1
N1owbjELMAkGA1UEBhMCREUxGzAZBgNVBAgMElNjaGxlc3dpZy1Ib2xzdGVpbjEQ
MA4GA1UEBwwHTHVlYmVjazEXMBUGA1UECgwOVHJhdmVOZXR6IEdtYkgxFzAVBgNV
BAMMDlRyYXZlTmV0eiBPQVNTMIIBojANBgkqhkiG9w0BAQEFAAOCAY8AMIIBigKC
AYEA5rOc/Og2pJT94iq4jtOG/knXNu7GAosU4f9B8S4rUTQ/63yeZVafN3jIhaIV
QQNPsyZtLni8bazsL/xmQ1rP2kmboom1rQYzTKf8c1ZtPOBzvPYveP2Umn0ivSK0
PlHGzLS/xAGSWiW5hMO4VDVdLHTpbAIEdHFz7pGZjtIQ3JSUCEyAjfC0CK+0H6TX
OeKxSpEDHRUr1aLDEPG/xTOi7j8XuKWFOaRNiCFFvBrbJdSAzwdirHRr2i3CIUvP
VwVhbodZH1gsulmWV4ZUMDr9jm6W9XADV0Bj5L0nx74cxnfTUOLlcvzidnCHqj2p
W+uJO+VuzxBShq+/n0WeF0nD2aS7tX+3Qkl7obRpPAR2ew3a3x+nM8lj/yHqbQpZ
5m2sKRM+f4mwqWn0O4nqSR7EfKHE/kvGoFInh6ynI9uvdiCQ+5vprlxSFLyTj/z5
JkZmJuYdb1cyygACfHMm2wEvj6dRgSsu0rYkW3rTVyuNL2jSwY92N//xFZOOqybt
jKupAgMBAAGjXDBaMAkGA1UdEwQCMAAwCwYDVR0PBAQDAgXgMCEGA1UdEQQaMBiC
FnNydmtzb2EwMS50cmF2ZWtvbS5uZXQwHQYDVR0OBBYEFOizAtzDFkOL+vrRYreI
fNbODrQqMA0GCSqGSIb3DQEBCwUAA4IBgQB28C3xrMLC9q71udqqM1jvL2DwitaN
ACTejkxPTPUpsmEvK6tUa3vTmd0Sny6Al2AZ3exvmb30lmPAlff/srjJMSBOZ3kP
lPpQBuq9kkMkmZgUcNYAswQ2tOcTyLfwibkj09aDWQBJqchP1ru5ePBx150vYR/O
n+diJ9C2Cc8p0qxPOWUP1b2mGESf4jEX0yO7PYg4lMLueVX7dcq2Ea3MFm/6hH2s
PRln22J/5CFV2GMsUZKYA/y5t+uLvx0FOqxtT/9drmsHQIRoekOD0ZYALyfJKvKs
J0k0KAO9u2pkB3m6bz9bMwfsp+y/KOtKDIkKnZ2cSmMoCG0ulAJyKGc7yAMWtYPt
ORj2stsfp8pzUZWNNZv0kdICC6mO7SFvSr0BrQaTLWNTriSMqfT2mCqAU4uy0QR/
Muge5ggGUKuD7+SzYQ53OdsCLmYHIlWvv/icUojH8OfEGCfk73vyFqVLYyKpTmzI
4vrmtAtNjuC6Y4tNDWefbc+YcVZgis6sQ/M=
-----END CERTIFICATE-----',
                    'private_key' => '-----BEGIN PRIVATE KEY-----
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
DT/+3yIVkVkIfcw9Yjrubb8=
-----END PRIVATE KEY-----'
                ]
            ]

        ]
    ]
];