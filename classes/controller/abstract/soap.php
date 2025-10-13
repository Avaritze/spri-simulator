<?php

namespace Sprisimulator;


use Fuel\Core\Controller_Hybrid;
use Fuel\Core\Controller_Rest;
use Fuel\Core\Fuel;
use Fuel\Core\Log;
use Fuel\Core\Response;
use Fuel\Core\View;

abstract class Controller_Abstract_Soap extends Controller_Hybrid
{
	public function after($response)
	{
		$response = parent::after($response);
        if ( ! $this->is_restful())
        {
            $response->set_header('Content-Type', 'text/html; charset=utf-8');
        }
        else
        {
            $response->set_header('Content-Type', 'application/xml; charset=utf-8');
        }

		return $response;
	}


	/**
	 * Extends the original router method to wrap any exceptions into a SOAP error-handler
	 * @param string $resource
	 * @param array $arguments
	 * @return Response|false
	 */
	public function router($resource, $arguments) : Response|false
	{
		try
		{
			// run standard router
			$response = parent::router($resource, $arguments);

			if ($response instanceof Response)
			{
				return $response;
			}
		}
		catch (\Exception $e)
		{
            if( $e instanceof \SoapFault)
            {
                return $this->handle_error($e);
            }

		}

		return false;
	}


	public function handle_error(\SoapFault $exception) : Response
	{
        Log::error("Simulator->handle_error() ".get_class($exception).'-Handling: '.$exception->faultcode.': '.$exception->faultactor." in ".$exception->getFile()." line ".$exception->getLine());
        if (Fuel::$env === Fuel::DEVELOPMENT)
        {
            $response = View::forge('soap/fault/generic', ['code' => 'Simulator', 'msg' => $exception->getMessage()." in ".$exception->getFile()." line: ".$exception->getLine()]);
        }
        else
        {
            $response = View::forge('soap/fault/generic');
        }

		return Response::forge($response);
	}
}