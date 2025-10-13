<?php

namespace Sprisimulator;

use Fuel\Core\Log;
use Fuel\Core\Package;
use Fuel\Core\Response;
use Fuel\Core\View;

class Controller_Abstract_Spri extends \Sprisimulator\Controller_Abstract_Soap
{
	protected static ?string $spri_role = null;

	public function before() : void
	{
		parent::before();
        View::set_global('spri_major_version', '4');
        View::set_global('spri_package_name', 'spriv4');

        Package::load('laminas');
        Package::load('spriv4');
	}


	public function after($response)
	{
		return parent::after($response);
	}


	/**
	 * Extends the SOAP-enabled router by adding a logging instance
	 * @param string $resource
	 * @param array $arguments
	 * @return Response|false
	 * @throws \Exception
	 */
	public function router($resource, $arguments) : Response|false
	{
		try
		{
			// run parent router
			return parent::router($resource, $arguments);
		}
		catch (\Exception $e)
		{

            if( $e instanceof \SoapFault)
            {
                return parent::handle_error($e);
            }
		}
	}

}