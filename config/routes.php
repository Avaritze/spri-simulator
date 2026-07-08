<?php
return [
    '_404_'                                             => ['sprisimulator/soapsimulator/404',              'name' => 'soap_simulator_404'],
    'soap/v4/simulator'                                 => ['sprisimulator/soapsimulator/simulator',        'name' => 'soap_simulator_default'],

    'soap/v4/auftragsstatus'                            => ['sprisimulator/soapsimulator/auftragsstatus',   'name' => 'soap_simulator_auftrag_status'],

    'soap/v4/dataformat'                                 => ['sprisimulator/soapsimulator/dataFormat',        'name' => 'soap_simulator_xml_format'],

    'soap/v(:version)/supplier'                         => ['sprisimulator/soapsimulator/supplier',         'name' => 'soap_simulator_endpoint_supplier'],

    'soap/v(:version)/(:icc)/orderer'                   => ['sprisimulator/soapsimulator/orderer_icc',      'name' => 'soap_simulator_endpoint_orderer_icc'],
    'soap/v(:version)/orderer'                          => ['sprisimulator/soapsimulator/orderer',          'name' => 'soap_simulator_endpoint_orderer'],

    'soap/v(:version)/(:icc)/wsdl'                      => ['sprisimulator/soapsimulator/wsdl_icc',         'name' => 'soap_simulator_wsdl_icc'],
    'soap/v(:version)/wsdl'                             => ['sprisimulator/soapsimulator/wsdl',             'name' => 'soap_simulator_wsdl'],

    'soap/v4/auftrag'                                   => ['sprisimulator/soapsimulator/auftrag',          'name' => 'soap_simulator_auftrag'],
    'soap/v4/sendeAuftrag'                              => ['sprisimulator/soapsimulator/sendeAuftrag',     'name' => 'soap_simulator_sende_auftrag_request'],

    'soap/v(:version)/sendeMeldung/(:icc)/(:auftrag_id)/(:meldung)/(:mcode)'
                    => [(isset($_GET['wsdl']) ? 'sprisimulator/soapsimulator/wsdl_icc' : 'sprisimulator/soapsimulator/sendeMeldung'), 'name' => 'sende_meldung_request'],
];
