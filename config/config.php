<?php
return [
    'log_path' => APPPATH.'logs/',
    'log_file' => 'orderer.log',
    'schema' => [
        'xsd' => [
            'oss-envelope',
            'oss-message',
            'oss-order',
            'oss-product-fttx',
            'oss-type-complex',
            'oss-type-enum',
            'oss-type-simple'
        ]
    ]
];
