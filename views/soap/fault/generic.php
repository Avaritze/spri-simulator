<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/">
	<SOAP-ENV:Body>
		<SOAP-ENV:Fault>
			<faultcode><?php echo $code ?? 'Receiver'; ?></faultcode>
			<faultstring><?php echo $msg ?? 'Internal Error'; ?></faultstring>
        <?php
            if(isset($role)) { echo "<faultendpoint>$role</faultendpoint>";  }
            if(isset($request)) { echo "<faultrequest>$request</faultrequest>";  }
        ?>
		</SOAP-ENV:Fault>
	</SOAP-ENV:Body>
</SOAP-ENV:Envelope>