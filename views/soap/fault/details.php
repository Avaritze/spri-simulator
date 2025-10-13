<tns:Envelope xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:tns="http://schemas.xmlsoap.org/soap/envelope/">
    <tns:Body>
        <tns:Fault>
            <faultcode><?php echo $code ?? 'Receiver'; ?></faultcode>
            <faultstring><?php echo $msg ?? 'Internal Error'; ?></faultstring>
            <faultactor><?php echo $details ?? 'Unknown Source'; ?></faultactor>
        </tns:Fault>
    </tns:Body>
</tns:Envelope>