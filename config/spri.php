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
            'AVA' => [
                'services' => [
                    'v4' => [
                        'wsdl' => Router::get('soap_simulator_wsdl_icc',  ['version' => 4, 'icc' => 'DEU.AVA']),
                        'endpoints' => [
                            'orderer' => Router::get('soap_simulator_endpoint_orderer_icc',  ['version' => 4, 'icc' => 'DEU.AVA']),
                            'supplier' => false,
                        ],
                        'signature' => [
                            'issuer' => 'CN=AVAible Systems, OU=SPRI RS, O=AVAible Systems, L=Luebeck, ST=Schleswig-Holstein, C=DE',
                            'serial' => '2345766675432',
                            'certificate' => 'config:spri.partners.DEU.SWL451.certificate.x509',
                            'key' => 'config:spri.partners.DEU.SWL451.certificate.private_key',
                        ],
                    ]
                ],
                'version' => [
                    'preferred' => [
                        'major' => 4,
                        'minor' => 30
                    ]
                ]
            ],
            'NPS' => [
                'services' => [
                    'v4' => [
                        'wsdl' => Router::get('soap_simulator_wsdl_icc',  ['version' => 4, 'icc' => 'DEU.NPS']),
                        'endpoints' => [
                            'orderer' => Router::get('soap_simulator_endpoint_orderer_icc',  ['version' => 4, 'icc' => 'DEU.NPS']),
                            'supplier' => false,
                        ],
                        'signature' => [
                            'issuer' => 'CN=NPS Webservice, OU=SPRI RS, O=ACME Providing Systems, L=Luebeck, ST=Schleswig-Holstein, C=DE',
                            'serial' => '6662345775432',
                            'certificate' => 'config:spri.partners.DEU.SWL451.certificate.x509',
                            'key' => 'config:spri.partners.DEU.SWL451.certificate.private_key',
                        ],
                        'akm_frist' => 180
                    ]
                ],
                'version' => [
                    'preferred' => [
                        'major' => 4,
                        'minor' => 30
                    ]
                ],
                'certificate' => [
                    'x509' => '-----BEGIN CERTIFICATE-----
MIID8zCCAtugAwIBAgIUfLXef8XDXqb5BM25pfg1Y7PZlFswDQYJKoZIhvcNAQEL
BQAwgYgxCzAJBgNVBAYTAkRFMRswGQYDVQQIDBJTY2hsZXN3aWctSG9sc3RlaW4x
EDAOBgNVBAcMB0x1ZWJlY2sxHzAdBgNVBAoMFkFDTUUgUHJvdmlkaW5nIFN5c3Rl
bXMxEDAOBgNVBAsMB1NQUkkgUlMxFzAVBgNVBAMMDk5QUyBXZWJzZXJ2aWNlMB4X
DTI1MTEyMDA4NDYxOFoXDTI2MTEyMDA4NDYxOFowgYgxCzAJBgNVBAYTAkRFMRsw
GQYDVQQIDBJTY2hsZXN3aWctSG9sc3RlaW4xEDAOBgNVBAcMB0x1ZWJlY2sxHzAd
BgNVBAoMFkFDTUUgUHJvdmlkaW5nIFN5c3RlbXMxEDAOBgNVBAsMB1NQUkkgUlMx
FzAVBgNVBAMMDk5QUyBXZWJzZXJ2aWNlMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8A
MIIBCgKCAQEAn3cQ9R3Lzo80mxhEc12Ubj2wrGlOUXCys8UK/mwXj72J1raXozZj
z3UykariC4xkxzASqy4i+cTEremkfZf5xcBbBmlBMkI9fNa3Xa5NEhEduqwotdgA
gzn/1InftCcnsIaXfec5Xm6Rca/Bkd+lYq1V5nTBPgcYWuFZ2sMD7IF0y2WMSiuH
8sjLiM2gLrEe+v0+u0AuQ4cWh4Joiue5TORQfzhG/KUCp5nKtY/GtVNdCwIeALbQ
fC/MfcSKhqVW7+py/fJ9gdriL4w2vefoAP1vPyuA6PiZD4vwim1jFnZWOArSUwat
9Sxt2xMTpfq67SJVjvleTGr22AipdR0hBQIDAQABo1MwUTAdBgNVHQ4EFgQUE+Ie
SgezmyP8tNlWUIWsUfrOTLYwHwYDVR0jBBgwFoAUE+IeSgezmyP8tNlWUIWsUfrO
TLYwDwYDVR0TAQH/BAUwAwEB/zANBgkqhkiG9w0BAQsFAAOCAQEAWcDDZraiQ46N
Y0D9emGL9wE2iXZvrBZPDguvkWcuPeu5cz06aqsgVYT7kZ+0ptXAdnPWwwaRfRbW
gmCp9RFwpt/JeIPNSpRL47UIn8opskwdk+FdezFTMPTvawPb9043HULtgIdxsWWr
82YE6RB3GyHbm/zis6KoEijpE7ntfzA9K0HvpXfZtUoTMvUBUjstjC1eEkkD5qzZ
LQK0oM4G1PK1xuv/Rg4mPYFm5tiquXUbFBF47PBnvv6UlDDKGQFA0keBHAWwzS8z
7rpVWaB9bxcZ8pfMuswxlGWMtpRLGhKhQvYhEasjcHNR0onSZy6DlVyx4rXyd/on
rj/dEsOMlQ==
-----END CERTIFICATE-----',
                    'private_key' => '-----BEGIN ENCRYPTED PRIVATE KEY-----
MIIFHDBOBgkqhkiG9w0BBQ0wQTApBgkqhkiG9w0BBQwwHAQITOJ3gs0vcToCAggA
MAwGCCqGSIb3DQIJBQAwFAYIKoZIhvcNAwcECBG/v2VNosZFBIIEyFdFhDQZhzo8
vdkadYIYbgem8Jvzl3KqKkXpga2WXwt78D4g+cjqnXLUqr4GSpZWvwuWKb6BXfet
HXhkefWvYFL2BdXQe5A7yBOUBNJRq0ZKjz2TFXc9RnyrfpLoOoTqwE4+FnJ0Zsrv
Km1lqFR69+ZsjNT2kYUgUv2Knq+ZM0VyDq7QNBqyfKDqPYCjCwBXUVwrHPcgOvfg
pqXb0zi95ednbl78JIo9qOzuvJCwKFrrcsUThK8UcaMjS2Cd1UlWwnYadf1Qnmqo
vBOSVg0HMxlgl4IWiwQ1R0xa4YtsxRnNcTwYkmemGh0BD3rJXNPhcYWz1QbwQfeV
TwEAkULnmk5yQy0//kYSLBwC5+EtG+u1xqZ5ZOsTZAzhuOaikiz+124HRLiWepqy
r28qa3Rfgctz9ouggVR7szgVVjoqODmRxCJztbtqRjNVU3jvnVrj/tRV9/pcjbs1
4rKZ4wL5i6qLuS18wuLwdye8HWz9r8noJpoQjjgO4NOmbBlrV6QdrP4nwMXsBf16
JBwrY/oSwK08pgpE1w5yV2O215xFTJ3dE9j91qkuwWK3Hhv+FCIyIxQdiTVmeNyO
PYOl+Xq1GNcSNr8bTt/0Z+zJieMtXY3js3puxrx1gCug8eIl0g9JCSO2vE4cgZ82
5eR5+dB275TLnusN+xwoos6+h8bUQAJyVRiYliVqZMI+S++whSZxthaqzQeEb27a
b43+vA25eObttHdCbA8h3OfuH0V4Ulehsxt5Dv2Lwz+gGPiBNGGtbBqkmJznUhhA
pVKiZ/gzXuvxOVoyxR6//7QUCK4KP7qD3RRqU6BvNG+SmpCUnn0XQN+VTzbyQQz7
tzBqQf5PFF9NSLa7/bq5yCmeXa7GXKdIm4JPWf3ywa9H2ej4nbkuBbtXJCbOGrSl
Z4GfAzBxodLUJ9fkNNfyDWSAFxox1CRvh7vHV8VQnwuGuv1Ns56SMJQmEzkylXMm
6pn3SwJ1bzsNoOw2pN2zlAss2cHM437q3iVHKETM99U/uuEKduJxUPAaY6+LB+1A
muStByNJUfGeOAi3P32CKrJG0SopGves57qwns0omf1wCabWGm7P8rbYjBrew3IU
KQfXuTup3YBIoDTOy1JNP/9DHHIQ9lSAHomsDrirt6rV7zoeWKOFjYax8wyzXint
il23ArjBoK9JIJYXDf2Sh7uLrzMcRq04OMIfBu7380OSGj4Xa2Y3zhuvE7YikvQx
KGlT3GvFBvrjzDcS34hxybTsPI8s66ol+IuyqQo9OmWJ+xkgNqANqBGVb4e1j4uD
zns7h+H/6Tjg1Dkz+VSH8w58lSODAQoOIU/8lh/3UhF/DNWRe08XW1B1dfYy6cKx
0flKnK0HsAJ7+Ct+QUgTP8affJ+m3g0BW87RVt24yL/5Sl1E9FCUFABftrsb06WA
xZA8MDS+NvvnQuCC/RNaOCAG47snmu/QVR0iPkpsOyBeIczDqDZe2ATdK1Qi+nNZ
47VN3V29208z26QM8r20wH9jOm5JlqKrhekif+XnI/DK0JlXR53ovs2zkAJzIpxf
82YaUoNbrMvccB+wKNNeWK26uvmV5Ku/cNZ+SVpg7H8jcZ3yos0mHNU0IkTIlci/
8DZ39DSySyOnXBHpfGgjiw==
-----END ENCRYPTED PRIVATE KEY-----'
                ]
            ]

        ]
    ]
];