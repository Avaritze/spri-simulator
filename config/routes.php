<?php
return [
    'soap/v4/simulator'                                 => ['sprisimulator/soapsimulator/simulator',        'name' => 'soap_simulator_default'],
    'soap/v4/auftragsstatus'                            => ['sprisimulator/soapsimulator/auftragsstatus',   'name' => 'soap_simulator_auftrag_status'],

    'soap/v(:version)/supplier'                         => ['sprisimulator/soapsimulator/supplier',         'name' => 'soap_simulator_endpoint_supplier'],

    'soap/v(:version)/(:icc)/orderer'                   => ['sprisimulator/soapsimulator/orderer_icc',      'name' => 'soap_simulator_endpoint_orderer_icc'],
    'soap/v(:version)/orderer'                          => ['sprisimulator/soapsimulator/orderer',          'name' => 'soap_simulator_endpoint_orderer'],

    'soap/v(:version)/(:icc)/wsdl'                      => ['sprisimulator/soapsimulator/wsdl_icc',         'name' => 'soap_simulator_wsdl_icc'],
    'soap/v(:version)/wsdl'                             => ['sprisimulator/soapsimulator/wsdl',             'name' => 'soap_simulator_wsdl'],

    'soap/v(:version)/sendeMeldung/(:auftrag_id)/(:meldung)/(:mcode)'
                    => [(isset($_GET['wsdl']) ? 'sprisimulator/soapsimulator/wsdl' : 'sprisimulator/soapsimulator/sendeMeldung'), 'name' => 'sende_meldung_request_ag'],
];
