<?php

namespace Sprisimulator;

use Fuel\Core\Controller_Hybrid;
use Fuel\Core\Response;
use Fuel\Core\View;
use Fuel\Core\Presenter;
use Fuel\Core\Package;
use Fuel\Core\Request;
use Fuel\Core\Router;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Spri\Exception\Factory\DataRequirement;
use Spri\Exception\Factory\NotFoundException;
use Spri\Exception\Issue\Recoverable\Network;
use Spri\Exception\Issue\Recoverable\Recipient;
use Spri\Exception\Issue\Unrecoverable\Data;
use Spri\Exception\Issue\Unrecoverable\Partner;
use Spri\MeldungsversandService;
use Spri\Model\Spri;

use Spri\Model\Spri\Meldungscode;
use Spri\Soap\Server as SoapServer;
use Spri\Endpoint\Supplier;
use Spri\Endpoint\Orderer;

use Spri\Soap\Security\Wsse;
use Spri\Soap\Security\Certificate\Signing;

use Spri\SpriClassmap;


class Controller_Soapsimulator extends Controller_Hybrid
{
    protected SoapServer|null $soap_server = null;
    protected string $icc = 'DEU.AVA';
    protected string $spri_role;
    protected null|Logger $customLogger = null;
    public $template = 'soapsimulator/simulator';

    public function before(): void
    {
        parent::before();
        Package::load('laminas');
        Package::load('spriv4');

        ini_set('soap.wsdl_cache_enabled', 0);
        ini_set('soap.wsdl_cache_ttl', 0);

        $contextOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ];
        $sslContext = stream_context_create($contextOptions);
        // init server
        $this->soap_server = new SoapServer(Router::get('soap_simulator_wsdl',  ['version' => 4]), [ 'stream_context' => $sslContext ]);
        $this->soap_server->setWSDLCache(WSDL_CACHE_NONE);
        $this->soap_server->setClassmap(SpriClassmap::getCollection());
        $this->soap_server->setSendErrors(true);
        $this->soap_server->setReturnResponse(true);
        $this->soap_server->setSoapVersion(SOAP_1_1);
    }

    public function action_simulator(): Response
    {
        $this->getSimulatorLogger()->info("simulator->simulator()");
        return Response::forge( Presenter::forge('soapsimulator/simulator', 'view',false) );
    }

    public function action_auftragsstatus(): Response
    {
        $this->getSimulatorLogger()->info("simulator->auftragsstatus()". json_encode(Request::active()->params()));
        return Response::forge( Presenter::forge('soapsimulator/auftragsstatus', 'view',false) );
    }

    public function action_wsdl() : Response
    {
        $this->getSimulatorLogger()->info("simulator->wsdl(): schema/orderer/wsdl");
        return Response::forge(View::forge('schema/orderer/wsdl'));
    }

    public function action_wsdl_icc() : Response
    {
        $params = Request::active()->params();
        $icc = strtolower($params['icc']);
        $this->getSimulatorLogger()->info("simulator->wsdl_icc(): schema/".$icc."/wsdl", Request::active()->params());
        return Response::forge(View::forge('schema/'.$icc.'/wsdl'));
    }



    public function action_orderer() : Response
    {
        $this->getSimulatorLogger()->info("simulator->orderer()", Request::active()->params());
        $this->soap_server->setClass(Orderer::class, ['CarrierCode', $this->icc]);
        $this->spri_role = Spri::SPRI_ROLE_ORDERER;

        return $this->action_index();
    }

    public function action_orderer_icc() : Response
    {
        $params = Request::active()->params();
        $this->icc = "DEU.".strtoupper($params['icc']);
        $this->getSimulatorLogger()->info("simulator->orderer(icc:".$this->icc.")", Request::active()->params());
        $this->soap_server->setClass(Orderer::class, ['CarrierCode', $this->icc]);
        $this->spri_role = Spri::SPRI_ROLE_ORDERER;

        return $this->action_index();
    }

    public function action_index()
    {
        try {
            $this->soap_server->handle();
        }
        catch (\SoapFault $soapFault) {
            return Response::forge(View::forge('soap/fault/details',['code' => $soapFault->faultcode, 'msg' => $soapFault->faultstring, 'actor' => $soapFault->faultactor, 'details' => $soapFault->detail->ExceptionDetail->Type.' at '.$soapFault->getFile().':'.$soapFault->getLine()]));
        }
        catch (\Exception $exception) {
            return Response::forge(View::forge('exception/default',['class' => get_class($exception), 'msg' => $exception->getMessage(), 'source' => $exception->getFile()."(at line:".$exception->getLine().")", 'trace' => $exception->getTraceAsString()]));
        }

        $signedResponse = Wsse::sign($this->soap_server->getResponse(), new Signing(
                Config::get('spri.partners.'.$this->icc.'.services.v4.signature.certificate', ''),
                Config::get('spri.partners.'.$this->icc.'.services.v4.signature.issuer'),
                Config::get('spri.partners.'.$this->icc.'.services.v4.signature.serial'),
                Config::get('spri.partners.'.$this->icc.'.services.v4.signature.key', ''))
        );

        $request = new \DOMDocument();
        $request->loadXML($this->soap_server->getLastRequest());

        $response = new \DOMDocument();
        $response->loadXML($this->soap_server->getResponse());


        if($request->getElementsByTagName('meldung')->count() > 0)
        {
            $this->getSimulatorLogger()->warn("annehmenMeldungRequest: ", ["Request" => $request->saveXML($request->getElementsByTagName('meldung')->item(0))]);
            $this->getSimulatorLogger()->warn("annehmenMeldungResponse: ", ["Response" => $response->saveXML($response->getElementsByTagName('quittung')->item(0))]);
        }
        else if($request->getElementsByTagName('auftrag')->count() > 0)
        {
            $this->getSimulatorLogger()->warn("annehmenAuftragRequest : ", ["Request" => $request->saveXML($request->getElementsByTagName('auftrag')->item(0))]);
            $this->getSimulatorLogger()->warn("annehmenAuftragResponse: ", ["Response" => $response->saveXML($response->getElementsByTagName('quittung')->item(0))]);
        }

        return Response::forge($signedResponse);
    }

    public function action_sendeMeldung() : Response
    {
        $this->spri_role = Spri::SPRI_ROLE_ORDERER;
        $this->getSimulatorLogger()->info("simulator->sendeMeldung() ", Request::active()->params());

        $params = Request::active()->params();
        $mcode = Meldungscode::get_by_mc_id(intval($params['mcode']));
        $meldungscode = $mcode[Meldungscode::COLUMN_MELDUNGS_CODE];

        try
        {
            $PartnerMeldungsversand = new MeldungsversandService();
            $PartnerMeldungsversand->setCarrierCode($this->icc);
            $PartnerMeldungsversand->setPartnerCarrierCode("DEU.SWL451");
            $PartnerMeldungsversand->setSpriRole($this->spri_role);
            $PartnerMeldungsversand->setPartnerIpId(1);
            $success = $PartnerMeldungsversand->sendeMeldung($params['auftrag_id'], $params['meldung'], $meldungscode);
            if($success)
            {
                $xmlRequest = $PartnerMeldungsversand->getXmlRequest();
                $xmlResponse = $PartnerMeldungsversand->getXmlResponse();
                $request = new \DOMDocument();
                $request->loadXML($xmlRequest);
                $msgRequest = $request->getElementsByTagName('annehmenMeldungRequest');

                $response = new \DOMDocument();
                $response->loadXML($xmlResponse);
                $msgResponse = $response->getElementsByTagName('annehmenMeldungResponse');

                $this->getSimulatorLogger()->warn("annehmenMeldungRequest: ", ["Request" => $request->saveXML($request->getElementsByTagName('meldung')->item(0))]);
                $this->getSimulatorLogger()->warn("annehmenMeldungResponse: ", ["Response" => $response->saveXML($response->getElementsByTagName('quittung')->item(0))]);

                $ret = Response::forge(json_encode([$request->saveXML($msgRequest->item(0)),$response->saveXML($msgResponse->item(0))]),200, ['Content-Type' => 'application/json']);
            }
            else
            {
                $this->getSimulatorLogger()->error("Something went wrong! SendeMeldungRequest(".$params['auftrag_id'].", ".$params['meldung'].", ".$params['code'].")");
                $ret = Response::forge(json_encode("Something went wrong! SendeMeldungRequest(".$params['auftrag_id'].", ".$params['meldung'].", ".$params['code'].")",200, ['Content-Type' => 'application/json']));
            }
        }
        catch (\SoapFault $soapFault)
        {
            return Response::forge(View::forge('soap/fault/details',['code' => $soapFault->faultcode, 'msg' => $soapFault->faultstring, "details" => $soapFault->getFile()." at line ".$soapFault->getLine()]),200, ['Content-Type' => 'text/xml']);
        }
        catch(DataRequirement $exception)
        {
            return Response::forge(View::forge('exception/default',['class' => 'Required-Factory-Data-Exception', 'msg' => $exception->getMessage(), 'model' => $exception->getType().": ".$exception->getElement(), 'parameter' => $exception->getField(), 'trace' => $exception->getTraceAsString()]));
        }
        catch(NotFoundException $exception)
        {
            return Response::forge(View::forge('exception/default',['class' => 'Not-found-by-Reference-Exception', 'msg' => $exception->getMessage(), 'model' => $exception->getType().": ".$exception->getElement(), 'parameter' => $exception->getElement(), 'trace' => $exception->getTraceAsString()]));
        }
        catch (Partner $exception) {
            return Response::forge(View::forge('exception/default',['class' => 'No-Partner-Exception', 'msg' => $exception->getMessage(), 'source' => $exception->getFile()." at Line ".$exception->getLine(), 'trace' => $exception->getTraceAsString()]));
        }
        catch (Data|Recipient|Network $exception) {
            return Response::forge(View::forge('exception/default',['class' => 'Connection-Problem-Exception', 'msg' => $exception->getMessage(), 'source' => $exception->getFile()." at Line ".$exception->getLine(), 'trace' => $exception->getTraceAsString()]));
        }
        catch (\Exception $exception) {
            return Response::forge(View::forge('exception/default',['class' => 'PHP-Error-Exception', 'msg' => $exception->getMessage(), 'source' => $exception->getFile()." at Line ".$exception->getLine(), 'trace' => $exception->getTraceAsString()]));
        }

        return $ret;
    }

    /**
     * @return Logger
     * @throws \Exception
     */
    protected function getSimulatorLogger() : Logger
    {
        if(!$this->customLogger)
        {
            $logger = new Logger("Simulator");
            $logHandler = new StreamHandler(APPPATH."logs".DS."custom".DS.date('Y').DS.date('m').DS.date('d').DS."simulator.log");
            $logHandler->setFormatter(new LineFormatter());
            $logger->pushHandler($logHandler);
            $this->customLogger = $logger;
        }

        return $this->customLogger;
    }

    /**
     * @return Response
     * @throws \Exception
     */
    public function action_supplier() : Response
    {
        $this->getSimulatorLogger()->info("simulator->supplier()", Request::active()->params());
        $this->soap_server->setClass(Supplier::class, ['CarrierCode', $this->icc]);
        $this->spri_role = Spri::SPRI_ROLE_SUPPLIER;

        return $this->action_index();
    }


}