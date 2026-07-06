<?php
namespace Sprisimulator;

use Fuel\Core\Package;
\Fuel\Core\Package::load('spriv4');
use Spri\Model\Spri\Auftrag;
use Fuel\Core\Presenter;
use Spri\Model\Spri\Geschaeftsfall;

class Presenter_Soapsimulator_Simulator extends Presenter
{
    /**
     * Prepare the view data, keeping this in here helps clean up
     * the controller.
     *
     * @return void
     */
    public function view()
    {
        //$request_xml = new AnnehmenAuftragRequest();
        $this->h_simulator = 'sende Auftrag';
        $this->h_simulator_msg = 'Meldungsversand';

        $this->arr_AuftragRequests = array_merge(Auftrag::get_all_by_auftraggeber_nr('AVA1203666'),Auftrag::get_all_by_auftraggeber_nr('NPS6663021'));
        $this->arr_AuftragProvider = Auftrag::get_all_by_auftraggeber_nr('NPS6663021');
        $this->count_Auftrag = Auftrag::count() + 1;
        $this->arr_vertragsnummern = Geschaeftsfall::get_all_gf_with_vertrag_lineid();
        $this->arr_home_ids = ["G12119T10627C", "GHT0AS1666", "G13983I12491Q","G6TVV3W6H4","GMX1K5C5Z9","G4280F2788N"];
        $this->arr_Auftraggeber = [
            "ava" => ["icc" => "AVA", "agnr" => "AVA1203666", "serial" => "1235766675432", "issuer" => "CN=AVAible S/PRI Simulator, OU=SPRI RS, O=AVAible OA Systems, L=Luebeck, ST=Schleswig-Holstein, C=DE"],
            "nps" => ["icc" => "NPS", "agnr" => "NPS6663021", "serial" => "6662345775432", "issuer" => "CN=NPS Webservice, OU=SPRI RS, O=ACME Providing Systems, L=Luebeck, ST=Schleswig-Holstein, C=DE"],
        ];
        $this->arr_Auftragstyp = ['10' => 'NEU', '20' => 'KUE-AG', '30' => 'KUE-LE', '40' => 'AEN-LMAE', '50' => 'LAE', '60' => 'PV', '70' => 'EST', '80' => 'SET', '90' => 'GET'];
        $this->request_auftrag_bereitstellung_neu ='<?xml version="1.0" encoding="UTF-8"?>
<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/">
  <SOAP-ENV:Header>
    <wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" SOAP-ENV:mustUnderstand="1">
      <wsse:BinarySecurityToken EncodingType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary" ValueType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-x509-token-profile-1.0#X509v3" wsu:Id="X509-ad6d2677-9a0f-4493-aff9-e3d7abddbea2">MIIDgTCCAmmgAwIBAgIERCh/IDANBgkqhkiG9w0BAQsFADBxMQswCQYDVQQGEwJERTELMAkGA1UECBMCU0gxEjAQBgNVBAcTCUZsZW5zYnVyZzERMA8GA1UEChMIVmVyc2F0ZWwxCzAJBgNVBAsTAklUMSEwHwYDVQQDExhWZXJzYXRlbCBXSVRBIFdlYlNlcnZpY2UwHhcNMTYwMTEzMTAwNzA3WhcNMjUxMTIxMTAwNzA3WjBxMQswCQYDVQQGEwJERTELMAkGA1UECBMCU0gxEjAQBgNVBAcTCUZsZW5zYnVyZzERMA8GA1UEChMIVmVyc2F0ZWwxCzAJBgNVBAsTAklUMSEwHwYDVQQDExhWZXJzYXRlbCBXSVRBIFdlYlNlcnZpY2UwggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQCSHyEafG10j6A4qiY9Kc2W+vxgLlV55z0E+x2QgvKoOXRSDSnyt+ydyam1gHCjADo53FLquhGFVwu4FONa02h7WG1Wuzk62JsGzSAPUIonDcBp1pB/0ck6MpI88pOzDV7E7mLnC+YoeCpvYGpqlHxOK2CjzdLgbFb1TrD69yWM0KHpZXBuWSWwFjMOTUMhDYG3vuJ5Zy2+4U4KQ7C5qQzM8dRHrJZOwjoSR3zWMqiNfRNZ4kN31K2EuCzzV+8+IzlkkZp3DXbArYTBTOD5HVrn30j4447zQLAxocyPys1mmadv30CW3etO5vF8WzZMAUDDdsjTqGWeP/9zQJ1p77brAgMBAAGjITAfMB0GA1UdDgQWBBTGswkybxXBGQf53KKjRPmcl/bD0DANBgkqhkiG9w0BAQsFAAOCAQEAKljW1Z5E750u/ax1brOEEEBKqSromOLn9gYzD4gPCokThgpHbstSocHhFYzcusUav8RaFW+zR+rvdQVBb/PmPhqzF5Ei41lMHyJfQoq+IFt/9P9nJuakt4+AgKEFnnErD+qYvaPJmbMaahB1G8swGPFfs7E00M8PWkrVoiVkR+WpElqg/V1Q2kBdceQLohJi/7uFm7ha6juBZs4QMQcJo5Dy3ETNSLexuhc7BOssUEjJcg46Crknv9p3983MJyUFtlRHBGbCe3XnDFqyN2nHlD6dQh9x4jyxUKO0ShPeN47YVvir5A0z2ROtPzi64hQuWgKag1TT1elgPe9sgWUpZg==</wsse:BinarySecurityToken>
      <ds:Signature xmlns:ds="http://www.w3.org/2000/09/xmldsig#" Id="SIG-fb483f5e-e8fe-4791-88c4-5998738321be">
        <ds:SignedInfo>
          <ds:CanonicalizationMethod Algorithm="http://www.w3.org/2001/10/xml-exc-c14n#">
            <ec:InclusiveNamespaces xmlns:ec="http://www.w3.org/2001/10/xml-exc-c14n#" PrefixList="SOAP-ENV"/>
          </ds:CanonicalizationMethod>
          <ds:SignatureMethod Algorithm="http://www.w3.org/2001/04/xmldsig-more#rsa-sha256"/>
          <ds:Reference URI="#id-fee40aa5-6cdf-44d0-8361-3d558047833e">
            <ds:Transforms>
              <ds:Transform Algorithm="http://www.w3.org/2001/10/xml-exc-c14n#"/>
            </ds:Transforms>
            <ds:DigestMethod Algorithm="http://www.w3.org/2001/04/xmlenc#sha256"/>
            <ds:DigestValue>LMJe3Nql29tk54NkcqXnEOg/S2gTpv72WO4NoSlzPxs=</ds:DigestValue>
          </ds:Reference>
        </ds:SignedInfo>
        <ds:SignatureValue>FKzwfkZEXNz2bvCWF2pW3AfYBMuT5gCjFMlnQ/n2mXzE1RIXuD0M+9gwF1Wn8I3KwrO9bIXAgEyy9ub5Ov6ESNEPa/UBv7DpATjadG3/dxxYS3d/n5oyh4ScCl3vXrUo3s5vgPDTFKYUeBm5AiYHtKqCVzITOs+xW+S3tKRcGfyHOkeolYCy9aIqDcs9f1f3NPaZD+zFq3xzqUKSYdzQ3Fcm3z/ZFUvTHco56ldiCYQUxrraaRm4qpigWJ4dfnplR0NzACDp77LWXj/x2dtQ5IaToKKhPsl7M9hVMcZKMmcM19p6xP+jqGKVFIScKT26evFfuPHmYNCqoRIvyiBjeA==</ds:SignatureValue>
        <ds:KeyInfo Id="KI-b2fff0c8-0b3f-4b1d-a881-036e4780e23d">
          <wsse:SecurityTokenReference wsu:Id="STR-70229234-1a87-4417-8187-6604add16fbf">
            <wsse:Reference URI="#X509-ad6d2677-9a0f-4493-aff9-e3d7abddbea2" ValueType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-x509-token-profile-1.0#X509v3"/>
          </wsse:SecurityTokenReference>
        </ds:KeyInfo>
      </ds:Signature>
    </wsse:Security>
  </SOAP-ENV:Header>
    <SOAP-ENV:Body xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" wsu:Id="id-fee40aa5-6cdf-44d0-8361-3d558047833e">
      <ns2:annehmenAuftragRequest xmlns:ns2="http://spri.telekom.de/oss/v4/envelope">
        <control>
          <zeitstempel>'.date(\DateTimeInterface::RFC3339_EXTENDED).'</zeitstempel>
          <majorRelease>04</majorRelease>
          <minorRelease>00</minorRelease>
          <signaturId>
            <issuer>CN=OA Providing Systems, OU=SPRI RS, O=OAP Systems, L=Luebeck, ST=Schleswig-Holstein, C=DE</issuer>
            <serial>6662345775432</serial>
          </signaturId>
        </control>
        <auftrag>
          <externeAuftragsnummer></externeAuftragsnummer>
          <auftraggeber>
            <auftraggebernummer>BE10001061</auftraggebernummer>
            <leistungsnummer>BLE1001061</leistungsnummer>
          </auftraggeber>
          <besteller>
            <auftraggebernummer>NPS6663021</auftraggebernummer>
            <leistungsnummer>NPSL'.substr(time(),5, strlen(time())).'</leistungsnummer>
        </besteller>
          <geschaeftsfall xmlns:ns4="http://spri.telekom.de/oss/v4/order" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="ns4:BereitstellungType">
            <ansprechpartner>
              <auftragsmanagement>
                <anrede>1</anrede>
                <vorname>Sven</vorname>
                <nachname>Rauschning</nachname>
                <telefonnummer>04619099223</telefonnummer>
                <emailadresse>ooc1und1@1und1.net</emailadresse>
              </auftragsmanagement>
            </ansprechpartner>
            <termine>
              <auftraggeberWunschtermin></auftraggeberWunschtermin>
            </termine>
            <auftragsposition>
              <produkt xmlns:ns5="http://spri.telekom.de/oss/v4/fttx" xsi:type="ns5:ProduktFTTXType">
                <bezeichner>FTTH L2 XGSPON 300 150</bezeichner>
              </produkt>
              <geschaeftsfallProdukt xmlns:ns5="http://spri.telekom.de/oss/v4/fttx" xsi:type="ns5:FTTXBereitstellungType">
                <standortA>
                  <person>
                    <anrede>1</anrede>
                    <vorname>Frank</vorname>
                    <nachname>Qaaccessft-Qaaiatm-33865</nachname>
                    <telefonnummer>01590 1890067</telefonnummer>
                  </person>
                  <strasse>
                    <strassenname>Geniner Str.</strassenname>
                    <hausnummer>80</hausnummer>
                  </strasse>
                  <postleitzahl>23560</postleitzahl>
                  <ort>
                    <ortsname>Lübeck</ortsname>
                  </ort>
                </standortA>
                <montageleistung>
                  <ansprechpartner>
                    <anrede>1</anrede>
                    <vorname>Frank</vorname>
                    <nachname>Qaaccessft-Qaaiatm-33865</nachname>
                    <telefonnummer>01590 1890067</telefonnummer>
                    <emailadresse>qaaccessft@1und1.de</emailadresse>
                  </ansprechpartner>
                </montageleistung>
              </geschaeftsfallProdukt>
            </auftragsposition>
          </geschaeftsfall>
          <geschaeftsfallArt>Bereitstellung</geschaeftsfallArt>
          <aenderungskennzeichen>Standard</aenderungskennzeichen>
        </auftrag>
      </ns2:annehmenAuftragRequest>
    </SOAP-ENV:Body>
 </SOAP-ENV:Envelope>';
        $this->request_auftrag_bereitstellung_komplett =
            '<?xml version="1.0" encoding="UTF-8"?>
<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Header>
    <wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" soap:mustUnderstand="1">
      <wsse:BinarySecurityToken EncodingType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary" ValueType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-x509-token-profile-1.0#X509v3" wsu:Id="X509-95eb5ead-2678-4fc4-8abd-59f722fb6455">MIIDmzCCAoOgAwIBAgIEcYUGOTANBgkqhkiG9w0BAQsFADB+MQswCQYDVQQGEwJERTEMMAoGA1UECBMDTlJXMQ8wDQYDVQQHEwZEVUVSRU4xHjAcBgNVBAoTFUVuZ2hvdXNlIFN5c3RlbXMgTHRkLjEQMA4GA1UECxMHU1BSSSBSUzEeMBwGA1UEAxMVRW5naG91c2UgU3lzdGVtcyBMdGQuMB4XDTIxMDYyMzE2MzkzMVoXDTMxMDYyMTE2MzkzMVowfjELMAkGA1UEBhMCREUxDDAKBgNVBAgTA05SVzEPMA0GA1UEBxMGRFVFUkVOMR4wHAYDVQQKExVFbmdob3VzZSBTeXN0ZW1zIEx0ZC4xEDAOBgNVBAsTB1NQUkkgUlMxHjAcBgNVBAMTFUVuZ2hvdXNlIFN5c3RlbXMgTHRkLjCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBAIpv2i4uPVsJAwMV+5i/JOCsJPGcU8hEaezvS4kdJcELwLCHWiD6DThKb6ou23lk64nkA7bztpsuRQ9mOl8d89RtN/azKpZ13pMVkC7hXHyTr3kBGTPyYEQJq2pPwYJwB2yq/s8eZtPnw/4N2aa+vxmdglTpLy/RFb9wS0sY76ql8PhyIqmJ/voz02AdAdJPgiVypteKHL9qNvBRsP6M58WiS4GCoS4RoaG3SSqhpGzwM3MysmRuttQSN8Leh6ndDOpx2H7CVEEA/oiXgoYm77pIOOHq2p+8r+cMnjk5Q0WFn0vfs4jeWX5UCZjx/2QGQjJa4R1Se1UZkCSoygZt8P8CAwEAAaMhMB8wHQYDVR0OBBYEFOZgtmMVgA9GI9+vM5PWesJ1UUB+MA0GCSqGSIb3DQEBCwUAA4IBAQAbR649eRec/TKCg0AG+m8WO0svI/IH3oUAIaf+TN5CT23U4JJZVoKY8DlO9zlVFaVK7jKjJAdbOqpHIUPbSMwr0TX6DqMuVgGSzs3Zham4Uflv2kslbwDBhiadKbf2dSqkVHt2oVLodzH7lFQ17Y1KWvl8Ss68gcaH6XqXOB54/iCms8zp+SwYLoykNcF0e2WR5BrnQHjQ1wdJeiVnEmFcMXNN9tdhRMt6zIUWDbnwkFQM9ZQfQf+JAj2OmtrtjLqy9fktj2TZByQ7D8fZUFPRSFktb1tA5zM7AoHcqeeDMA/HI/XOC9B7HE44j0xIzJ2OSmLh8l2fgYcxTyPWhKTd</wsse:BinarySecurityToken>
      <ds:Signature xmlns:ds="http://www.w3.org/2000/09/xmldsig#" Id="SIG-da20b0e7-c23b-43a3-b9bb-9b2e9161c421">
        <ds:SignedInfo>
          <ds:CanonicalizationMethod Algorithm="http://www.w3.org/2001/10/xml-exc-c14n#">
            <ec:InclusiveNamespaces xmlns:ec="http://www.w3.org/2001/10/xml-exc-c14n#" PrefixList="soap"/>
          </ds:CanonicalizationMethod>
          <ds:SignatureMethod Algorithm="http://www.w3.org/2000/09/xmldsig#rsa-sha1"/>
          <ds:Reference URI="#id-cf96cc70-e6bd-45b6-afc1-e2deccf7821c">
            <ds:Transforms>
              <ds:Transform Algorithm="http://www.w3.org/2001/10/xml-exc-c14n#"/>
            </ds:Transforms>
            <ds:DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1"/>
            <ds:DigestValue>//pvoOEpKvve/Zk++yiXmJS4cfA=</ds:DigestValue>
          </ds:Reference>
        </ds:SignedInfo>
        <ds:SignatureValue>GaBlpXtlHH+FdtXi+DqiMCqASRa0KtwPlqAyva7H0Z2lqQpUXGH2+z1vPOPAuZ6e79l/MEvyMhiVyjbOGaSuH80/u0fM4JBUTyO2ovKkiN4tSn7nCCPuvki2gu7dk5ZNcy8rKh0p4vQKFpb24/EH2pYiGqve52ar80m06Gy5eqH7hO+lPoc5xzvsyuljNg9eBWdOV2KDdcII+aGjOYZmCYORS4SEtkVF74p+mJfOjb8tAVfUbORA3jZjDkxsKjtv99niI+V2dAyMdCGDjhfg/fI63cFnYuL8T0pJ049syEZBZmh3pvnYG90w/jXf1S1E/7ooNEBtFLHQ+T+dTXirow==</ds:SignatureValue>
        <ds:KeyInfo Id="KI-cbafba29-fb7f-4140-bc64-243c4d996d1d">
          <wsse:SecurityTokenReference wsu:Id="STR-8032f2ca-c552-4ae8-b769-83c70090afef">
            <wsse:Reference URI="#X509-95eb5ead-2678-4fc4-8abd-59f722fb6455" ValueType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-x509-token-profile-1.0#X509v3"/>
          </wsse:SecurityTokenReference>
        </ds:KeyInfo>
      </ds:Signature>
    </wsse:Security>
  </soap:Header>
  <soap:Body xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" wsu:Id="id-cf96cc70-e6bd-45b6-afc1-e2deccf7821c">
    <ns3:annehmenAuftragRequest xmlns:ns3="http://spri.telekom.de/oss/v4/envelope" xmlns:ns2="http://spri.telekom.de/oss/v4/message" xmlns:ns4="http://spri.telekom.de/oss/v4/order" xmlns:ns5="http://spri.telekom.de/oss/v4/complex" xmlns:ns6="http://spri.telekom.de/oss/v4/fttx">
      <control>
        <zeitstempel>'.date(\DateTimeInterface::RFC3339_EXTENDED).'</zeitstempel>
        <majorRelease>04</majorRelease>
        <minorRelease>30</minorRelease>
        <signaturId>
          <issuer>CN=AVAible Systems, OU=SPRI RS, O=AVAible Systems, L=Luebeck, ST=Schleswig-Holstein, C=DE</issuer>
          <serial>1235766675432</serial>
        </signaturId>
      </control>
      <auftrag>
        <externeAuftragsnummer></externeAuftragsnummer>
        <auftraggeber>
          <auftraggebernummer>AVA1203666</auftraggebernummer>
          <leistungsnummer>AVAL'.substr(time(),5, strlen(time())).'</leistungsnummer>
        </auftraggeber>
        <geschaeftsfall xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="ns4:BereitstellungType">
          <ansprechpartner>
            <auftragsmanagement>
              <anrede>2</anrede>
              <vorname>Auftrags</vorname>
              <nachname>Managment</nachname>
              <telefonnummer>+49 451 36089186</telefonnummer>
              <mobilfunknummer>+49 170 2222222</mobilfunknummer>
              <faxnummer>0451 36089188</faxnummer>
              <emailadresse>auftrag@mail.de</emailadresse>
            </auftragsmanagement>
            <ansprechpartner>
              <anrede>1</anrede>
              <vorname>Peter</vorname>
              <nachname>Endkunde</nachname>
              <telefonnummer>0451 36089186</telefonnummer>
              <mobilfunknummer>0170 2222222</mobilfunknummer>
              <faxnummer>+49 451 36089188</faxnummer>
              <emailadresse>endkunde@mail.de</emailadresse>
              <rolle>Endkunde</rolle>
            </ansprechpartner>
          </ansprechpartner>
          <termine>
            <auftraggeberWunschtermin></auftraggeberWunschtermin>
          </termine>
          <auftragsposition>
            <produkt xsi:type="ns6:ProduktFTTXType">
              <bezeichner>FTTH L2 XGSPON 1000 500</bezeichner>
            </produkt>
            <geschaeftsfallProdukt xsi:type="ns6:FTTXBereitstellungType">
              <standortA>
                <person>
                  <anrede>2</anrede>
                  <vorname>Anna</vorname>
                  <nachname>Lyse</nachname>
                  <telefonnummer>+49 451 4567890</telefonnummer>
                  <mobilfunknummer>+49 151 7654321</mobilfunknummer>
                </person>
                <strasse>
                  <strassenname>Lachswehrallee</strassenname>
                  <hausnummer>13</hausnummer>
                </strasse>
                <gebaeudeteil>
                  <gebaeudeteilName>MFH,VH</gebaeudeteilName>
                  <gebaeudeteilZusatz>Mehrfamilienhaus</gebaeudeteilZusatz>
                </gebaeudeteil>
                <land>DE</land>
                <postleitzahl>23558</postleitzahl>
                <ort>
                  <ortsname>Lübeck</ortsname>
                  <ortsteil>St. Lorenz Süd</ortsteil>
                </ort>
                <lageTAE_ONT>MFH,VH,2.OG,li</lageTAE_ONT>
              </standortA>
              <standortVersand>
                <firma>
                  <anrede>4</anrede>
                  <firmenname>Convotis GmbH</firmenname>
                  <firmennameZweiterTeil>Lübeck</firmennameZweiterTeil>
                </firma>
                <strasse>
                  <strassenname>Niels-Bohr-Ring</strassenname>
                  <hausnummer>15</hausnummer>               
                </strasse>
                <land>DE</land>
                <postleitzahl>23568</postleitzahl>
                <ort>
                  <ortsname>Lübeck</ortsname>
                  <ortsteil>St. Gertrud</ortsteil>
                </ort>
              </standortVersand>
              <vormieter>
                <person>
                  <vorname>Paula</vorname>
                  <nachname>Mietnomadin</nachname>
                </person>
                <homeId>
                  <homeIdNummer>G5156G3664O</homeIdNummer>
                </homeId>
                <seriennummerONT>AVMG9CB86D1B</seriennummerONT>
              </vormieter>
              <technologie>FTTH</technologie>
              <abgebenderEKP>false</abgebenderEKP>
            </geschaeftsfallProdukt>
          </auftragsposition>
        </geschaeftsfall>
        <geschaeftsfallArt>Bereitstellung</geschaeftsfallArt>
        <aenderungskennzeichen></aenderungskennzeichen>
      </auftrag>
    </ns3:annehmenAuftragRequest>
  </soap:Body>
</soap:Envelope>';

        $this->request_auftrag_kuendigung_komplett = '<?xml version="1.0" encoding="UTF-8"?>
<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Header>
    <wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" soap:mustUnderstand="1">
      <wsse:BinarySecurityToken EncodingType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary" ValueType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-x509-token-profile-1.0#X509v3" wsu:Id="X509-3c628327-4f2a-4dde-bedc-3e30514a51ff">MIIDmzCCAoOgAwIBAgIEcYUGOTANBgkqhkiG9w0BAQsFADB+MQswCQYDVQQGEwJERTEMMAoGA1UECBMDTlJXMQ8wDQYDVQQHEwZEVUVSRU4xHjAcBgNVBAoTFUVuZ2hvdXNlIFN5c3RlbXMgTHRkLjEQMA4GA1UECxMHU1BSSSBSUzEeMBwGA1UEAxMVRW5naG91c2UgU3lzdGVtcyBMdGQuMB4XDTIxMDYyMzE2MzkzMVoXDTMxMDYyMTE2MzkzMVowfjELMAkGA1UEBhMCREUxDDAKBgNVBAgTA05SVzEPMA0GA1UEBxMGRFVFUkVOMR4wHAYDVQQKExVFbmdob3VzZSBTeXN0ZW1zIEx0ZC4xEDAOBgNVBAsTB1NQUkkgUlMxHjAcBgNVBAMTFUVuZ2hvdXNlIFN5c3RlbXMgTHRkLjCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBAIpv2i4uPVsJAwMV+5i/JOCsJPGcU8hEaezvS4kdJcELwLCHWiD6DThKb6ou23lk64nkA7bztpsuRQ9mOl8d89RtN/azKpZ13pMVkC7hXHyTr3kBGTPyYEQJq2pPwYJwB2yq/s8eZtPnw/4N2aa+vxmdglTpLy/RFb9wS0sY76ql8PhyIqmJ/voz02AdAdJPgiVypteKHL9qNvBRsP6M58WiS4GCoS4RoaG3SSqhpGzwM3MysmRuttQSN8Leh6ndDOpx2H7CVEEA/oiXgoYm77pIOOHq2p+8r+cMnjk5Q0WFn0vfs4jeWX5UCZjx/2QGQjJa4R1Se1UZkCSoygZt8P8CAwEAAaMhMB8wHQYDVR0OBBYEFOZgtmMVgA9GI9+vM5PWesJ1UUB+MA0GCSqGSIb3DQEBCwUAA4IBAQAbR649eRec/TKCg0AG+m8WO0svI/IH3oUAIaf+TN5CT23U4JJZVoKY8DlO9zlVFaVK7jKjJAdbOqpHIUPbSMwr0TX6DqMuVgGSzs3Zham4Uflv2kslbwDBhiadKbf2dSqkVHt2oVLodzH7lFQ17Y1KWvl8Ss68gcaH6XqXOB54/iCms8zp+SwYLoykNcF0e2WR5BrnQHjQ1wdJeiVnEmFcMXNN9tdhRMt6zIUWDbnwkFQM9ZQfQf+JAj2OmtrtjLqy9fktj2TZByQ7D8fZUFPRSFktb1tA5zM7AoHcqeeDMA/HI/XOC9B7HE44j0xIzJ2OSmLh8l2fgYcxTyPWhKTd</wsse:BinarySecurityToken>
      <ds:Signature xmlns:ds="http://www.w3.org/2000/09/xmldsig#" Id="SIG-30496c8f-5ccc-4409-be7d-8dae5dc48c1e">
        <ds:SignedInfo>
          <ds:CanonicalizationMethod Algorithm="http://www.w3.org/2001/10/xml-exc-c14n#">
            <ec:InclusiveNamespaces xmlns:ec="http://www.w3.org/2001/10/xml-exc-c14n#" PrefixList="soap"/>
          </ds:CanonicalizationMethod>
          <ds:SignatureMethod Algorithm="http://www.w3.org/2000/09/xmldsig#rsa-sha1"/>
          <ds:Reference URI="#id-117b9a7d-006f-48e8-b48a-09a5129aa9f0">
            <ds:Transforms>
              <ds:Transform Algorithm="http://www.w3.org/2001/10/xml-exc-c14n#"/>
            </ds:Transforms>
            <ds:DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1"/>
            <ds:DigestValue>uIPu3Q1EdmsbU0VL8d4WRU+nM0Y=</ds:DigestValue>
          </ds:Reference>
        </ds:SignedInfo>
        <ds:SignatureValue>d9e2EjMMcQLHrga08KAZza5gT4+wkXM9JK1vDyjNxPC9cK0+dt0ZmY2zM04qesp+uuTIcSjIq/boqtPMFXvEeaK4Ov37unCrYNPF5VtJNcPn1pHllAGjW3c2xxyAnuDdD+ZRlzZps4+OzFoORKrcWD4x51UPbK7qrdPUx7fEnT1Cakcgkq1u+V4kOj1i04480/mlYUxA+lq+glb1oWB5AzLyGu6MDVcp6FijG+arSPTT7nbsACIdxe+PzCaaq3D/ZE5M1BY5u/9ILAtdwmDkvlJMxbGqm8Jt54hAVGm5N8+43nCt+3cjlFWV2h58tXB1GD020mTNqxDX0HHXK3g4XQ==</ds:SignatureValue>
        <ds:KeyInfo Id="KI-d1b4f608-31c4-44c7-861e-5226670da0a6">
          <wsse:SecurityTokenReference wsu:Id="STR-87c5602f-74be-47db-8e5b-52bf2a21fe48">
            <wsse:Reference URI="#X509-3c628327-4f2a-4dde-bedc-3e30514a51ff" ValueType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-x509-token-profile-1.0#X509v3"/>
          </wsse:SecurityTokenReference>
        </ds:KeyInfo>
      </ds:Signature>
    </wsse:Security>
  </soap:Header>
  <soap:Body xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" wsu:Id="id-117b9a7d-006f-48e8-b48a-09a5129aa9f0">
    <ns3:annehmenAuftragRequest xmlns:ns3="http://spri.telekom.de/oss/v4/envelope" xmlns:ns2="http://spri.telekom.de/oss/v4/message" xmlns:ns4="http://spri.telekom.de/oss/v4/order" xmlns:ns5="http://spri.telekom.de/oss/v4/complex" xmlns:ns6="http://spri.telekom.de/oss/v4/fttx">
      <control>
        <zeitstempel>'.date(\DateTimeInterface::RFC3339_EXTENDED).'</zeitstempel>
        <majorRelease>04</majorRelease>
        <minorRelease>30</minorRelease>
        <signaturId>
          <issuer>CN=AVAible Systems, OU=SPRI RS, O=AVAible Systems, L=Luebeck, ST=Schleswig-Holstein, C=DE</issuer>
          <serial>1235766675432</serial>
        </signaturId>
      </control>
      <auftrag>
        <externeAuftragsnummer>EXT.9.1.'.sprintf('%02d', $this->count_Auftrag).'</externeAuftragsnummer>
        <auftraggeber>
          <auftraggebernummer>AVA1203666</auftraggebernummer>
          <leistungsnummer>AVAL'.substr(time(),5, strlen(time())).'</leistungsnummer>
        </auftraggeber>
        <geschaeftsfall xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="ns4:KuendigungType">
          <vertragsnummer></vertragsnummer>
          <lineId></lineId>
          <vorabstimmungId>DEU.AVA.'.substr(time(),1, strlen(time())).'</vorabstimmungId>
          <ansprechpartner>
            <auftragsmanagement>
              <anrede>2</anrede>
              <vorname>Brigitte</vorname>
              <nachname>Z_9_1_01_LE</nachname>
              <telefonnummer>+49 89 36089186</telefonnummer>
              <mobilfunknummer>+49 170 2222222</mobilfunknummer>
              <faxnummer>089 36089188</faxnummer>
              <emailadresse>auftrag@mail.de</emailadresse>
            </auftragsmanagement>
            <ansprechpartner>
              <anrede>1</anrede>
              <vorname>Peter</vorname>
              <nachname>Klein</nachname>
              <telefonnummer>089 36089186</telefonnummer>
              <mobilfunknummer>0170 2222222</mobilfunknummer>
              <faxnummer>+49 89 36089188</faxnummer>
              <emailadresse>technik@mail.de</emailadresse>
              <rolle>Technik</rolle>
            </ansprechpartner>
          </ansprechpartner>
          <termine>
            <auftraggeberWunschtermin></auftraggeberWunschtermin>
          </termine>
        </geschaeftsfall>
        <geschaeftsfallArt>Kuendigung</geschaeftsfallArt>
        <aenderungskennzeichen></aenderungskennzeichen>
      </auftrag>
    </ns3:annehmenAuftragRequest>
  </soap:Body>
</soap:Envelope>';
        $this->request_auftrag_leistungsaenderung = '<?xml version="1.0" encoding="UTF-8"?>
            <soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
              <soap:Header>
                <wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" soap:mustUnderstand="1">
                  <wsse:BinarySecurityToken EncodingType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary" ValueType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-x509-token-profile-1.0#X509v3" wsu:Id="X509-932f8a9c-f08f-4eb3-935e-3f151f82938f">MIIDmzCCAoOgAwIBAgIEcYUGOTANBgkqhkiG9w0BAQsFADB+MQswCQYDVQQGEwJERTEMMAoGA1UECBMDTlJXMQ8wDQYDVQQHEwZEVUVSRU4xHjAcBgNVBAoTFUVuZ2hvdXNlIFN5c3RlbXMgTHRkLjEQMA4GA1UECxMHU1BSSSBSUzEeMBwGA1UEAxMVRW5naG91c2UgU3lzdGVtcyBMdGQuMB4XDTIxMDYyMzE2MzkzMVoXDTMxMDYyMTE2MzkzMVowfjELMAkGA1UEBhMCREUxDDAKBgNVBAgTA05SVzEPMA0GA1UEBxMGRFVFUkVOMR4wHAYDVQQKExVFbmdob3VzZSBTeXN0ZW1zIEx0ZC4xEDAOBgNVBAsTB1NQUkkgUlMxHjAcBgNVBAMTFUVuZ2hvdXNlIFN5c3RlbXMgTHRkLjCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBAIpv2i4uPVsJAwMV+5i/JOCsJPGcU8hEaezvS4kdJcELwLCHWiD6DThKb6ou23lk64nkA7bztpsuRQ9mOl8d89RtN/azKpZ13pMVkC7hXHyTr3kBGTPyYEQJq2pPwYJwB2yq/s8eZtPnw/4N2aa+vxmdglTpLy/RFb9wS0sY76ql8PhyIqmJ/voz02AdAdJPgiVypteKHL9qNvBRsP6M58WiS4GCoS4RoaG3SSqhpGzwM3MysmRuttQSN8Leh6ndDOpx2H7CVEEA/oiXgoYm77pIOOHq2p+8r+cMnjk5Q0WFn0vfs4jeWX5UCZjx/2QGQjJa4R1Se1UZkCSoygZt8P8CAwEAAaMhMB8wHQYDVR0OBBYEFOZgtmMVgA9GI9+vM5PWesJ1UUB+MA0GCSqGSIb3DQEBCwUAA4IBAQAbR649eRec/TKCg0AG+m8WO0svI/IH3oUAIaf+TN5CT23U4JJZVoKY8DlO9zlVFaVK7jKjJAdbOqpHIUPbSMwr0TX6DqMuVgGSzs3Zham4Uflv2kslbwDBhiadKbf2dSqkVHt2oVLodzH7lFQ17Y1KWvl8Ss68gcaH6XqXOB54/iCms8zp+SwYLoykNcF0e2WR5BrnQHjQ1wdJeiVnEmFcMXNN9tdhRMt6zIUWDbnwkFQM9ZQfQf+JAj2OmtrtjLqy9fktj2TZByQ7D8fZUFPRSFktb1tA5zM7AoHcqeeDMA/HI/XOC9B7HE44j0xIzJ2OSmLh8l2fgYcxTyPWhKTd</wsse:BinarySecurityToken>
                  <ds:Signature xmlns:ds="http://www.w3.org/2000/09/xmldsig#" Id="SIG-b22b333a-9eef-413c-a434-10ae7214b386">
                    <ds:SignedInfo>
                      <ds:CanonicalizationMethod Algorithm="http://www.w3.org/2001/10/xml-exc-c14n#">
                        <ec:InclusiveNamespaces xmlns:ec="http://www.w3.org/2001/10/xml-exc-c14n#" PrefixList="soap"/>
                      </ds:CanonicalizationMethod>
                      <ds:SignatureMethod Algorithm="http://www.w3.org/2000/09/xmldsig#rsa-sha1"/>
                      <ds:Reference URI="#id-588f15b3-9e45-481c-a7c9-a6612bcf3c43">
                        <ds:Transforms>
                          <ds:Transform Algorithm="http://www.w3.org/2001/10/xml-exc-c14n#"/>
                        </ds:Transforms>
                        <ds:DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1"/>
                        <ds:DigestValue>RYP/wK0UfLV3/IP1U2GEv+YNvCI=</ds:DigestValue>
                      </ds:Reference>
                    </ds:SignedInfo>
                    <ds:SignatureValue>ZWkc8i/cHhpg3cI+GyBmtWQYj1fpdGc++2fFU45doFvMug962Yscqd+Lboimnt38eh2wkzzG73LrewLmpail3/gBe0JQ7zJh3LcfxEoZij9ZoyAh562EXwPOEx9I6TB1UH7ffLzz46aYWPKSIfUyvnE4DpGZ1K9wn3rW30XM2THRFylgXrM9bayECpsZ8VDWFLLHC2COrHoaG/33nXIHg13WeYZ+f/L1PZ7ilg2vnjtmChg+NCVujWKmkQKfcqGbPDcxzFm8JhpY6iSvwvvpsdScPEEpWnLbsLLslrJbzYmuSNccg058lLN9HquPXdi92Jf63l+VNWV1WBr8l9wFGQ==</ds:SignatureValue>
                    <ds:KeyInfo Id="KI-ff5027dc-800f-455f-ae24-6c31255cb354">
                      <wsse:SecurityTokenReference wsu:Id="STR-c1899688-27da-4031-b6bc-51b63c8e9d46">
                        <wsse:Reference URI="#X509-932f8a9c-f08f-4eb3-935e-3f151f82938f" ValueType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-x509-token-profile-1.0#X509v3"/>
                      </wsse:SecurityTokenReference>
                    </ds:KeyInfo>
                  </ds:Signature>
                </wsse:Security>
              </soap:Header>
              <soap:Body xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" wsu:Id="id-588f15b3-9e45-481c-a7c9-a6612bcf3c43">
                <ns3:annehmenAuftragRequest xmlns:ns3="http://spri.telekom.de/oss/v4/envelope" xmlns:ns2="http://spri.telekom.de/oss/v4/message" xmlns:ns4="http://spri.telekom.de/oss/v4/order" xmlns:ns5="http://spri.telekom.de/oss/v4/complex" xmlns:ns6="http://spri.telekom.de/oss/v4/fttx">
                  <control>
                    <zeitstempel>'.date(\DateTimeInterface::RFC3339_EXTENDED).'</zeitstempel>
                    <majorRelease>04</majorRelease>
                    <minorRelease>30</minorRelease>
                    <signaturId>
                      <issuer>CN=AVAible Systems, OU=SPRI RS, O=AVAible Systems, L=Luebeck, ST=Schleswig-Holstein, C=DE</issuer>
                      <serial>1235766675432</serial>
                    </signaturId>
                  </control>
                  <auftrag>
                    <externeAuftragsnummer>EXT.11.1.'.sprintf('%02d', $this->count_Auftrag).'</externeAuftragsnummer>
                    <auftraggeber>
                      <auftraggebernummer>AVA1203666</auftraggebernummer>
                      <leistungsnummer>AVAL'.substr(time(),5, strlen(time())).'</leistungsnummer>
                    </auftraggeber>
                    <geschaeftsfall xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="ns4:LeistungsaenderungType">
                      <vertragsnummer></vertragsnummer>
                      <lineId></lineId>
                      <ansprechpartner>
                        <auftragsmanagement>
                          <anrede>2</anrede>
                          <nachname>Z_12_1_01_LE</nachname>
                          <telefonnummer>0451666999</telefonnummer>
                        </auftragsmanagement>
                      </ansprechpartner>
                      <termine>
                        <auftraggeberWunschtermin></auftraggeberWunschtermin>
                      </termine>
                      <auftragsposition xsi:type="ns4:AuftragspositionLeistungsaenderungType">
                        <produkt xsi:type="ns6:ProduktFTTXType">
                          <bezeichner>FTTH L2 XGSPON 600 300</bezeichner>
                        </produkt>
                        <position xsi:type="ns4:AenderungPositionType">
                          <produkt xsi:type="ns6:ProduktFTTXType">
                            <bezeichner>FTTH L2 XGSPON 1000 500</bezeichner>
                          </produkt>
                          <aktionscode>A</aktionscode>
                        </position>
                      </auftragsposition>
                    </geschaeftsfall>
                    <geschaeftsfallArt>Aenderung</geschaeftsfallArt>
                    <aenderungskennzeichen></aenderungskennzeichen>
                  </auftrag>
                </ns3:annehmenAuftragRequest>
              </soap:Body>
            </soap:Envelope>';
        $this->request_auftrag_leistungsmerkmalaenderung = '<?xml version="1.0" encoding="UTF-8"?>
            <soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
              <soap:Header>
                <wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" soap:mustUnderstand="1">
                  <wsse:BinarySecurityToken EncodingType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary" ValueType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-x509-token-profile-1.0#X509v3" wsu:Id="X509-932f8a9c-f08f-4eb3-935e-3f151f82938f">MIIDmzCCAoOgAwIBAgIEcYUGOTANBgkqhkiG9w0BAQsFADB+MQswCQYDVQQGEwJERTEMMAoGA1UECBMDTlJXMQ8wDQYDVQQHEwZEVUVSRU4xHjAcBgNVBAoTFUVuZ2hvdXNlIFN5c3RlbXMgTHRkLjEQMA4GA1UECxMHU1BSSSBSUzEeMBwGA1UEAxMVRW5naG91c2UgU3lzdGVtcyBMdGQuMB4XDTIxMDYyMzE2MzkzMVoXDTMxMDYyMTE2MzkzMVowfjELMAkGA1UEBhMCREUxDDAKBgNVBAgTA05SVzEPMA0GA1UEBxMGRFVFUkVOMR4wHAYDVQQKExVFbmdob3VzZSBTeXN0ZW1zIEx0ZC4xEDAOBgNVBAsTB1NQUkkgUlMxHjAcBgNVBAMTFUVuZ2hvdXNlIFN5c3RlbXMgTHRkLjCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBAIpv2i4uPVsJAwMV+5i/JOCsJPGcU8hEaezvS4kdJcELwLCHWiD6DThKb6ou23lk64nkA7bztpsuRQ9mOl8d89RtN/azKpZ13pMVkC7hXHyTr3kBGTPyYEQJq2pPwYJwB2yq/s8eZtPnw/4N2aa+vxmdglTpLy/RFb9wS0sY76ql8PhyIqmJ/voz02AdAdJPgiVypteKHL9qNvBRsP6M58WiS4GCoS4RoaG3SSqhpGzwM3MysmRuttQSN8Leh6ndDOpx2H7CVEEA/oiXgoYm77pIOOHq2p+8r+cMnjk5Q0WFn0vfs4jeWX5UCZjx/2QGQjJa4R1Se1UZkCSoygZt8P8CAwEAAaMhMB8wHQYDVR0OBBYEFOZgtmMVgA9GI9+vM5PWesJ1UUB+MA0GCSqGSIb3DQEBCwUAA4IBAQAbR649eRec/TKCg0AG+m8WO0svI/IH3oUAIaf+TN5CT23U4JJZVoKY8DlO9zlVFaVK7jKjJAdbOqpHIUPbSMwr0TX6DqMuVgGSzs3Zham4Uflv2kslbwDBhiadKbf2dSqkVHt2oVLodzH7lFQ17Y1KWvl8Ss68gcaH6XqXOB54/iCms8zp+SwYLoykNcF0e2WR5BrnQHjQ1wdJeiVnEmFcMXNN9tdhRMt6zIUWDbnwkFQM9ZQfQf+JAj2OmtrtjLqy9fktj2TZByQ7D8fZUFPRSFktb1tA5zM7AoHcqeeDMA/HI/XOC9B7HE44j0xIzJ2OSmLh8l2fgYcxTyPWhKTd</wsse:BinarySecurityToken>
                  <ds:Signature xmlns:ds="http://www.w3.org/2000/09/xmldsig#" Id="SIG-b22b333a-9eef-413c-a434-10ae7214b386">
                    <ds:SignedInfo>
                      <ds:CanonicalizationMethod Algorithm="http://www.w3.org/2001/10/xml-exc-c14n#">
                        <ec:InclusiveNamespaces xmlns:ec="http://www.w3.org/2001/10/xml-exc-c14n#" PrefixList="soap"/>
                      </ds:CanonicalizationMethod>
                      <ds:SignatureMethod Algorithm="http://www.w3.org/2000/09/xmldsig#rsa-sha1"/>
                      <ds:Reference URI="#id-588f15b3-9e45-481c-a7c9-a6612bcf3c43">
                        <ds:Transforms>
                          <ds:Transform Algorithm="http://www.w3.org/2001/10/xml-exc-c14n#"/>
                        </ds:Transforms>
                        <ds:DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1"/>
                        <ds:DigestValue>RYP/wK0UfLV3/IP1U2GEv+YNvCI=</ds:DigestValue>
                      </ds:Reference>
                    </ds:SignedInfo>
                    <ds:SignatureValue>ZWkc8i/cHhpg3cI+GyBmtWQYj1fpdGc++2fFU45doFvMug962Yscqd+Lboimnt38eh2wkzzG73LrewLmpail3/gBe0JQ7zJh3LcfxEoZij9ZoyAh562EXwPOEx9I6TB1UH7ffLzz46aYWPKSIfUyvnE4DpGZ1K9wn3rW30XM2THRFylgXrM9bayECpsZ8VDWFLLHC2COrHoaG/33nXIHg13WeYZ+f/L1PZ7ilg2vnjtmChg+NCVujWKmkQKfcqGbPDcxzFm8JhpY6iSvwvvpsdScPEEpWnLbsLLslrJbzYmuSNccg058lLN9HquPXdi92Jf63l+VNWV1WBr8l9wFGQ==</ds:SignatureValue>
                    <ds:KeyInfo Id="KI-ff5027dc-800f-455f-ae24-6c31255cb354">
                      <wsse:SecurityTokenReference wsu:Id="STR-c1899688-27da-4031-b6bc-51b63c8e9d46">
                        <wsse:Reference URI="#X509-932f8a9c-f08f-4eb3-935e-3f151f82938f" ValueType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-x509-token-profile-1.0#X509v3"/>
                      </wsse:SecurityTokenReference>
                    </ds:KeyInfo>
                  </ds:Signature>
                </wsse:Security>
              </soap:Header>
              <soap:Body xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" wsu:Id="id-588f15b3-9e45-481c-a7c9-a6612bcf3c43">
                <ns3:annehmenAuftragRequest xmlns:ns3="http://spri.telekom.de/oss/v4/envelope" xmlns:ns2="http://spri.telekom.de/oss/v4/message" xmlns:ns4="http://spri.telekom.de/oss/v4/order" xmlns:ns5="http://spri.telekom.de/oss/v4/complex" xmlns:ns6="http://spri.telekom.de/oss/v4/fttx">
                  <control>
                    <zeitstempel>'.date(\DateTimeInterface::RFC3339_EXTENDED).'</zeitstempel>
                    <majorRelease>04</majorRelease>
                    <minorRelease>30</minorRelease>
                    <signaturId>
                      <issuer>CN=AVAible Systems, OU=SPRI RS, O=AVAible Systems, L=Luebeck, ST=Schleswig-Holstein, C=DE</issuer>
                      <serial>1235766675432</serial>
                    </signaturId>
                  </control>
                  <auftrag>
                    <externeAuftragsnummer>EXT.12.1.'.sprintf('%02d', $this->count_Auftrag).'</externeAuftragsnummer>
                    <auftraggeber>
                      <auftraggebernummer>AVA1203666</auftraggebernummer>
                      <leistungsnummer>AVAL'.substr(time(),5, strlen(time())).'</leistungsnummer>
                    </auftraggeber>
                    <geschaeftsfall xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="ns4:LeistungsmerkmalAenderungType">
                      <vertragsnummer></vertragsnummer>
                      <lineId></lineId>
                      <ansprechpartner>
                        <auftragsmanagement>
                          <anrede>2</anrede>
                          <nachname>Z_11_1_01_LE</nachname>
                          <telefonnummer>0451666999</telefonnummer>
                        </auftragsmanagement>
                      </ansprechpartner>
                      <termine>
                        <auftraggeberWunschtermin></auftraggeberWunschtermin>
                      </termine>
                      <auftragsposition xsi:type="ns4:AuftragspositionLeistungsmerkmalAenderungType">
                        <produkt xsi:type="ns6:ProduktFTTXType">
                          <bezeichner>FTTH L2 XGSPON 600 300</bezeichner>
                        </produkt>
                        <position xsi:type="ns4:AenderungPositionType">
                          <produkt xsi:type="ns6:ProduktFTTXType">
                            <bezeichner>Expressentstörung</bezeichner>
                          </produkt>
                          <aktionscode>W</aktionscode>
                        </position>
                      </auftragsposition>
                    </geschaeftsfall>
                    <geschaeftsfallArt>Aenderung</geschaeftsfallArt>
                    <aenderungskennzeichen></aenderungskennzeichen>
                  </auftrag>
                </ns3:annehmenAuftragRequest>
              </soap:Body>
            </soap:Envelope>';
        $this->request_auftrag_entstoerung = '<?xml version="1.0" encoding="UTF-8"?>
            <soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
              <soap:Header>
                <wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" soap:mustUnderstand="1">
                  <wsse:BinarySecurityToken EncodingType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary" ValueType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-x509-token-profile-1.0#X509v3" wsu:Id="X509-932f8a9c-f08f-4eb3-935e-3f151f82938f">MIIDmzCCAoOgAwIBAgIEcYUGOTANBgkqhkiG9w0BAQsFADB+MQswCQYDVQQGEwJERTEMMAoGA1UECBMDTlJXMQ8wDQYDVQQHEwZEVUVSRU4xHjAcBgNVBAoTFUVuZ2hvdXNlIFN5c3RlbXMgTHRkLjEQMA4GA1UECxMHU1BSSSBSUzEeMBwGA1UEAxMVRW5naG91c2UgU3lzdGVtcyBMdGQuMB4XDTIxMDYyMzE2MzkzMVoXDTMxMDYyMTE2MzkzMVowfjELMAkGA1UEBhMCREUxDDAKBgNVBAgTA05SVzEPMA0GA1UEBxMGRFVFUkVOMR4wHAYDVQQKExVFbmdob3VzZSBTeXN0ZW1zIEx0ZC4xEDAOBgNVBAsTB1NQUkkgUlMxHjAcBgNVBAMTFUVuZ2hvdXNlIFN5c3RlbXMgTHRkLjCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBAIpv2i4uPVsJAwMV+5i/JOCsJPGcU8hEaezvS4kdJcELwLCHWiD6DThKb6ou23lk64nkA7bztpsuRQ9mOl8d89RtN/azKpZ13pMVkC7hXHyTr3kBGTPyYEQJq2pPwYJwB2yq/s8eZtPnw/4N2aa+vxmdglTpLy/RFb9wS0sY76ql8PhyIqmJ/voz02AdAdJPgiVypteKHL9qNvBRsP6M58WiS4GCoS4RoaG3SSqhpGzwM3MysmRuttQSN8Leh6ndDOpx2H7CVEEA/oiXgoYm77pIOOHq2p+8r+cMnjk5Q0WFn0vfs4jeWX5UCZjx/2QGQjJa4R1Se1UZkCSoygZt8P8CAwEAAaMhMB8wHQYDVR0OBBYEFOZgtmMVgA9GI9+vM5PWesJ1UUB+MA0GCSqGSIb3DQEBCwUAA4IBAQAbR649eRec/TKCg0AG+m8WO0svI/IH3oUAIaf+TN5CT23U4JJZVoKY8DlO9zlVFaVK7jKjJAdbOqpHIUPbSMwr0TX6DqMuVgGSzs3Zham4Uflv2kslbwDBhiadKbf2dSqkVHt2oVLodzH7lFQ17Y1KWvl8Ss68gcaH6XqXOB54/iCms8zp+SwYLoykNcF0e2WR5BrnQHjQ1wdJeiVnEmFcMXNN9tdhRMt6zIUWDbnwkFQM9ZQfQf+JAj2OmtrtjLqy9fktj2TZByQ7D8fZUFPRSFktb1tA5zM7AoHcqeeDMA/HI/XOC9B7HE44j0xIzJ2OSmLh8l2fgYcxTyPWhKTd</wsse:BinarySecurityToken>
                  <ds:Signature xmlns:ds="http://www.w3.org/2000/09/xmldsig#" Id="SIG-b22b333a-9eef-413c-a434-10ae7214b386">
                    <ds:SignedInfo>
                      <ds:CanonicalizationMethod Algorithm="http://www.w3.org/2001/10/xml-exc-c14n#">
                        <ec:InclusiveNamespaces xmlns:ec="http://www.w3.org/2001/10/xml-exc-c14n#" PrefixList="soap"/>
                      </ds:CanonicalizationMethod>
                      <ds:SignatureMethod Algorithm="http://www.w3.org/2000/09/xmldsig#rsa-sha1"/>
                      <ds:Reference URI="#id-588f15b3-9e45-481c-a7c9-a6612bcf3c43">
                        <ds:Transforms>
                          <ds:Transform Algorithm="http://www.w3.org/2001/10/xml-exc-c14n#"/>
                        </ds:Transforms>
                        <ds:DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1"/>
                        <ds:DigestValue>RYP/wK0UfLV3/IP1U2GEv+YNvCI=</ds:DigestValue>
                      </ds:Reference>
                    </ds:SignedInfo>
                    <ds:SignatureValue>ZWkc8i/cHhpg3cI+GyBmtWQYj1fpdGc++2fFU45doFvMug962Yscqd+Lboimnt38eh2wkzzG73LrewLmpail3/gBe0JQ7zJh3LcfxEoZij9ZoyAh562EXwPOEx9I6TB1UH7ffLzz46aYWPKSIfUyvnE4DpGZ1K9wn3rW30XM2THRFylgXrM9bayECpsZ8VDWFLLHC2COrHoaG/33nXIHg13WeYZ+f/L1PZ7ilg2vnjtmChg+NCVujWKmkQKfcqGbPDcxzFm8JhpY6iSvwvvpsdScPEEpWnLbsLLslrJbzYmuSNccg058lLN9HquPXdi92Jf63l+VNWV1WBr8l9wFGQ==</ds:SignatureValue>
                    <ds:KeyInfo Id="KI-ff5027dc-800f-455f-ae24-6c31255cb354">
                      <wsse:SecurityTokenReference wsu:Id="STR-c1899688-27da-4031-b6bc-51b63c8e9d46">
                        <wsse:Reference URI="#X509-932f8a9c-f08f-4eb3-935e-3f151f82938f" ValueType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-x509-token-profile-1.0#X509v3"/>
                      </wsse:SecurityTokenReference>
                    </ds:KeyInfo>
                  </ds:Signature>
                </wsse:Security>
              </soap:Header>
              <soap:Body xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" wsu:Id="id-588f15b3-9e45-481c-a7c9-a6612bcf3c43">
                <ns3:annehmenAuftragRequest xmlns:ns3="http://spri.telekom.de/oss/v4/envelope" xmlns:ns2="http://spri.telekom.de/oss/v4/message" xmlns:ns4="http://spri.telekom.de/oss/v4/order" xmlns:ns5="http://spri.telekom.de/oss/v4/complex" xmlns:ns6="http://spri.telekom.de/oss/v4/fttx">
      <control>
        <zeitstempel>2025-12-19T14:18:40.921+01:00</zeitstempel>
        <majorRelease>04</majorRelease>
        <minorRelease>30</minorRelease>
        <signaturId>
           <issuer>CN=AVAible Systems, OU=SPRI RS, O=AVAible Systems, L=Luebeck, ST=Schleswig-Holstein, C=DE</issuer>
                      <serial>1235766675432</serial>
        </signaturId>
      </control>
      <auftrag>
        <externeAuftragsnummer>EST.Z.14.1.03</externeAuftragsnummer>
        <auftraggeber>
          <auftraggebernummer>LE10001061</auftraggebernummer>
          <leistungsnummer>LLE1001061</leistungsnummer>
        </auftraggeber>
        <geschaeftsfall xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="ns4:EntstoerungType">
          <vertragsnummer></vertragsnummer>
          <lineId></lineId>
          <ansprechpartner>
            <auftragsmanagement>
              <anrede>2</anrede>
              <vorname>Esta</vorname>
              <nachname>Auftragsmanagement</nachname>
              <telefonnummer>0451666999</telefonnummer>
              <emailadresse>simulator@auftragsmanagement.de</emailadresse>
            </auftragsmanagement>
          </ansprechpartner>
          <kontaktEndkunde>
            <anrede>9</anrede>
            <vorname>E.</vorname>
            <nachname>zu Entstörender</nachname>
            <mobilfunknummer>017915118418</mobilfunknummer>
            <emailadresse>endkunde@entstoerung.net</emailadresse>
            <kontaktaufnahmeErwuenscht>true</kontaktaufnahmeErwuenscht>
          </kontaktEndkunde>
          <termine>
            <auftraggeberWunschtermin></auftraggeberWunschtermin>
          </termine>
          <stoerungsNrAuftraggeber>EST20251219</stoerungsNrAuftraggeber>
          <vorpruefungErfolgt>true</vorpruefungErfolgt>
          <stoerungsbeschreibung>Funktionierte der Anschluss bereits zu einem früheren Zeitpunkt? Y Funktioniert die Internetverbindung? N Wenn vorhanden physikalische Parameter: Down-/Upload Ist Leuchtanzeige für Synchronität dauernd grün? N Angeschlossene Kunden-CPE: Fritzbox  Freitext:Die Box ist nicht mehr online. Verkabelung wurde überprüft...  Mal sehen wie weit die Entstörung durchläuft  Störungsart: Komplettausfall Beschreibung der Störung: Keine Synchronisation</stoerungsbeschreibung>
          <expressentstoerung>false</expressentstoerung>
        </geschaeftsfall>
        <geschaeftsfallArt>Entstoerung</geschaeftsfallArt>
        <aenderungskennzeichen>Standard</aenderungskennzeichen>
      </auftrag>
    </ns3:annehmenAuftragRequest>
              </soap:Body>
            </soap:Envelope>';
        $this->request_auftrag_reklamation = '<?xml version="1.0" encoding="UTF-8"?>
            <soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
              <soap:Header>
                <wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" soap:mustUnderstand="1">
                  <wsse:BinarySecurityToken EncodingType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary" ValueType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-x509-token-profile-1.0#X509v3" wsu:Id="X509-932f8a9c-f08f-4eb3-935e-3f151f82938f">MIIDmzCCAoOgAwIBAgIEcYUGOTANBgkqhkiG9w0BAQsFADB+MQswCQYDVQQGEwJERTEMMAoGA1UECBMDTlJXMQ8wDQYDVQQHEwZEVUVSRU4xHjAcBgNVBAoTFUVuZ2hvdXNlIFN5c3RlbXMgTHRkLjEQMA4GA1UECxMHU1BSSSBSUzEeMBwGA1UEAxMVRW5naG91c2UgU3lzdGVtcyBMdGQuMB4XDTIxMDYyMzE2MzkzMVoXDTMxMDYyMTE2MzkzMVowfjELMAkGA1UEBhMCREUxDDAKBgNVBAgTA05SVzEPMA0GA1UEBxMGRFVFUkVOMR4wHAYDVQQKExVFbmdob3VzZSBTeXN0ZW1zIEx0ZC4xEDAOBgNVBAsTB1NQUkkgUlMxHjAcBgNVBAMTFUVuZ2hvdXNlIFN5c3RlbXMgTHRkLjCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBAIpv2i4uPVsJAwMV+5i/JOCsJPGcU8hEaezvS4kdJcELwLCHWiD6DThKb6ou23lk64nkA7bztpsuRQ9mOl8d89RtN/azKpZ13pMVkC7hXHyTr3kBGTPyYEQJq2pPwYJwB2yq/s8eZtPnw/4N2aa+vxmdglTpLy/RFb9wS0sY76ql8PhyIqmJ/voz02AdAdJPgiVypteKHL9qNvBRsP6M58WiS4GCoS4RoaG3SSqhpGzwM3MysmRuttQSN8Leh6ndDOpx2H7CVEEA/oiXgoYm77pIOOHq2p+8r+cMnjk5Q0WFn0vfs4jeWX5UCZjx/2QGQjJa4R1Se1UZkCSoygZt8P8CAwEAAaMhMB8wHQYDVR0OBBYEFOZgtmMVgA9GI9+vM5PWesJ1UUB+MA0GCSqGSIb3DQEBCwUAA4IBAQAbR649eRec/TKCg0AG+m8WO0svI/IH3oUAIaf+TN5CT23U4JJZVoKY8DlO9zlVFaVK7jKjJAdbOqpHIUPbSMwr0TX6DqMuVgGSzs3Zham4Uflv2kslbwDBhiadKbf2dSqkVHt2oVLodzH7lFQ17Y1KWvl8Ss68gcaH6XqXOB54/iCms8zp+SwYLoykNcF0e2WR5BrnQHjQ1wdJeiVnEmFcMXNN9tdhRMt6zIUWDbnwkFQM9ZQfQf+JAj2OmtrtjLqy9fktj2TZByQ7D8fZUFPRSFktb1tA5zM7AoHcqeeDMA/HI/XOC9B7HE44j0xIzJ2OSmLh8l2fgYcxTyPWhKTd</wsse:BinarySecurityToken>
                  <ds:Signature xmlns:ds="http://www.w3.org/2000/09/xmldsig#" Id="SIG-b22b333a-9eef-413c-a434-10ae7214b386">
                    <ds:SignedInfo>
                      <ds:CanonicalizationMethod Algorithm="http://www.w3.org/2001/10/xml-exc-c14n#">
                        <ec:InclusiveNamespaces xmlns:ec="http://www.w3.org/2001/10/xml-exc-c14n#" PrefixList="soap"/>
                      </ds:CanonicalizationMethod>
                      <ds:SignatureMethod Algorithm="http://www.w3.org/2000/09/xmldsig#rsa-sha1"/>
                      <ds:Reference URI="#id-588f15b3-9e45-481c-a7c9-a6612bcf3c43">
                        <ds:Transforms>
                          <ds:Transform Algorithm="http://www.w3.org/2001/10/xml-exc-c14n#"/>
                        </ds:Transforms>
                        <ds:DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1"/>
                        <ds:DigestValue>RYP/wK0UfLV3/IP1U2GEv+YNvCI=</ds:DigestValue>
                      </ds:Reference>
                    </ds:SignedInfo>
                    <ds:SignatureValue>ZWkc8i/cHhpg3cI+GyBmtWQYj1fpdGc++2fFU45doFvMug962Yscqd+Lboimnt38eh2wkzzG73LrewLmpail3/gBe0JQ7zJh3LcfxEoZij9ZoyAh562EXwPOEx9I6TB1UH7ffLzz46aYWPKSIfUyvnE4DpGZ1K9wn3rW30XM2THRFylgXrM9bayECpsZ8VDWFLLHC2COrHoaG/33nXIHg13WeYZ+f/L1PZ7ilg2vnjtmChg+NCVujWKmkQKfcqGbPDcxzFm8JhpY6iSvwvvpsdScPEEpWnLbsLLslrJbzYmuSNccg058lLN9HquPXdi92Jf63l+VNWV1WBr8l9wFGQ==</ds:SignatureValue>
                    <ds:KeyInfo Id="KI-ff5027dc-800f-455f-ae24-6c31255cb354">
                      <wsse:SecurityTokenReference wsu:Id="STR-c1899688-27da-4031-b6bc-51b63c8e9d46">
                        <wsse:Reference URI="#X509-932f8a9c-f08f-4eb3-935e-3f151f82938f" ValueType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-x509-token-profile-1.0#X509v3"/>
                      </wsse:SecurityTokenReference>
                    </ds:KeyInfo>
                  </ds:Signature>
                </wsse:Security>
              </soap:Header>
              <soap:Body xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" wsu:Id="id-588f15b3-9e45-481c-a7c9-a6612bcf3c43">
                <ns3:annehmenAuftragRequest xmlns:ns3="http://spri.telekom.de/oss/v4/envelope" xmlns:ns2="http://spri.telekom.de/oss/v4/message" xmlns:ns4="http://spri.telekom.de/oss/v4/order" xmlns:ns5="http://spri.telekom.de/oss/v4/complex" xmlns:ns6="http://spri.telekom.de/oss/v4/fttx">
                  <control>
                    <zeitstempel>'.date(\DateTimeInterface::RFC3339_EXTENDED).'</zeitstempel>
                    <majorRelease>04</majorRelease>
                    <minorRelease>30</minorRelease>
                    <signaturId>
                      <issuer>CN=AVAible Systems, OU=SPRI RS, O=AVAible Systems, L=Luebeck, ST=Schleswig-Holstein, C=DE</issuer>
                      <serial>1235766675432</serial>
                    </signaturId>
                  </control>
                  <auftrag>
                    <externeAuftragsnummer>EXT.14.2.'.sprintf('%02d', $this->count_Auftrag).'</externeAuftragsnummer>
                    <auftraggeber>
                      <auftraggebernummer>AVA1203666</auftraggebernummer>
                      <leistungsnummer>AVAL'.substr(time(),5, strlen(time())).'</leistungsnummer>
                    </auftraggeber>
                    <geschaeftsfall xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="ns4:EntstoerungType">
                      <vertragsnummer></vertragsnummer>
                      <lineId></lineId>
                      <ansprechpartner>
                        <auftragsmanagement>
                          <anrede>2</anrede>
                          <nachname>Z_11_1_01_LE</nachname>
                          <telefonnummer>0451666999</telefonnummer>
                        </auftragsmanagement>
                      </ansprechpartner>
                      <kontaktEndkunde>
                          <anrede>2</anrede>
                          <vorname>Eddy</vorname>
                          <nachname>Endkunde</nachname>
                          <emailadresse>eddy@gestoert.de</emailadresse>
                          <rolle>Endkunde</rolle>
                          <kontaktaufnahmeErwuenscht>true</kontaktaufnahmeErwuenscht>
                      </kontaktEndkunde>
                      <termine>
                        <auftraggeberWunschtermin></auftraggeberWunschtermin>
                      </termine>
                      <stoerungsNrAuftraggeber>21541198191</stoerungsNrAuftraggeber>
                      <reklamation>
                          <stoerungsNrLeistungserbringer>11981912154</stoerungsNrLeistungserbringer>
                          <reklamationsgrund>Fehler wurde nicht behoben!</reklamationsgrund>
                      </reklamation> 
                    </geschaeftsfall>
                    <geschaeftsfallArt>Entstoerung</geschaeftsfallArt>
                    <aenderungskennzeichen></aenderungskennzeichen>
                  </auftrag>
                </ns3:annehmenAuftragRequest>
              </soap:Body>
            </soap:Envelope>';
        $this->request_auftrag_providerwechsel_ava = '<?xml version="1.0" encoding="UTF-8"?>
            <soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
              <soap:Header>
                <wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" soap:mustUnderstand="1">
                  <wsse:BinarySecurityToken EncodingType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary" ValueType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-x509-token-profile-1.0#X509v3" wsu:Id="X509-e4795298-c4a8-467a-a10a-638ffb58a38a">MIIDmzCCAoOgAwIBAgIEcYUGOTANBgkqhkiG9w0BAQsFADB+MQswCQYDVQQGEwJERTEMMAoGA1UECBMDTlJXMQ8wDQYDVQQHEwZEVUVSRU4xHjAcBgNVBAoTFUVuZ2hvdXNlIFN5c3RlbXMgTHRkLjEQMA4GA1UECxMHU1BSSSBSUzEeMBwGA1UEAxMVRW5naG91c2UgU3lzdGVtcyBMdGQuMB4XDTIxMDYyMzE2MzkzMVoXDTMxMDYyMTE2MzkzMVowfjELMAkGA1UEBhMCREUxDDAKBgNVBAgTA05SVzEPMA0GA1UEBxMGRFVFUkVOMR4wHAYDVQQKExVFbmdob3VzZSBTeXN0ZW1zIEx0ZC4xEDAOBgNVBAsTB1NQUkkgUlMxHjAcBgNVBAMTFUVuZ2hvdXNlIFN5c3RlbXMgTHRkLjCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBAIpv2i4uPVsJAwMV+5i/JOCsJPGcU8hEaezvS4kdJcELwLCHWiD6DThKb6ou23lk64nkA7bztpsuRQ9mOl8d89RtN/azKpZ13pMVkC7hXHyTr3kBGTPyYEQJq2pPwYJwB2yq/s8eZtPnw/4N2aa+vxmdglTpLy/RFb9wS0sY76ql8PhyIqmJ/voz02AdAdJPgiVypteKHL9qNvBRsP6M58WiS4GCoS4RoaG3SSqhpGzwM3MysmRuttQSN8Leh6ndDOpx2H7CVEEA/oiXgoYm77pIOOHq2p+8r+cMnjk5Q0WFn0vfs4jeWX5UCZjx/2QGQjJa4R1Se1UZkCSoygZt8P8CAwEAAaMhMB8wHQYDVR0OBBYEFOZgtmMVgA9GI9+vM5PWesJ1UUB+MA0GCSqGSIb3DQEBCwUAA4IBAQAbR649eRec/TKCg0AG+m8WO0svI/IH3oUAIaf+TN5CT23U4JJZVoKY8DlO9zlVFaVK7jKjJAdbOqpHIUPbSMwr0TX6DqMuVgGSzs3Zham4Uflv2kslbwDBhiadKbf2dSqkVHt2oVLodzH7lFQ17Y1KWvl8Ss68gcaH6XqXOB54/iCms8zp+SwYLoykNcF0e2WR5BrnQHjQ1wdJeiVnEmFcMXNN9tdhRMt6zIUWDbnwkFQM9ZQfQf+JAj2OmtrtjLqy9fktj2TZByQ7D8fZUFPRSFktb1tA5zM7AoHcqeeDMA/HI/XOC9B7HE44j0xIzJ2OSmLh8l2fgYcxTyPWhKTd</wsse:BinarySecurityToken>
                  <ds:Signature xmlns:ds="http://www.w3.org/2000/09/xmldsig#" Id="SIG-e1a666ac-0d63-453e-a54e-e74d9b013cc3">
                    <ds:SignedInfo>
                      <ds:CanonicalizationMethod Algorithm="http://www.w3.org/2001/10/xml-exc-c14n#">
                        <ec:InclusiveNamespaces xmlns:ec="http://www.w3.org/2001/10/xml-exc-c14n#" PrefixList="soap"/>
                      </ds:CanonicalizationMethod>
                      <ds:SignatureMethod Algorithm="http://www.w3.org/2000/09/xmldsig#rsa-sha1"/>
                      <ds:Reference URI="#id-e0b059b4-ae41-4af8-b4ee-8a842238f53c">
                        <ds:Transforms>
                          <ds:Transform Algorithm="http://www.w3.org/2001/10/xml-exc-c14n#"/>
                        </ds:Transforms>
                        <ds:DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1"/>
                        <ds:DigestValue>FFvsa6AmFaMM79xs9DmvycAq2Iw=</ds:DigestValue>
                      </ds:Reference>
                    </ds:SignedInfo>
                    <ds:SignatureValue>IJtjurkOfMn3Z8GCABXzNjjXwjtt+A5WtzHkUxjCCP+iKrQzwtCWl10GnXUdshY0afwA07puiBKoBxD0azIL7mjTyQ0Z5o7uXWTg9jo1H/Y9DGRa2iuFhtwnBP1PMlN6zpC1XK4z4K3SNyK+uAUfVk6aTJH7+lNB6gHl3njgrcFeDext6h+XcTT5Q6QawVr5GiZYv7PThBxIbGoH8yDsxn8VRZ8vMUIiRF/+wNKKOLZRPpiyJdC/4S6uyAR68frqpdoqH/T4iLcRiM3eqzN3J4JHZaRPQqU5OFRIBS6C+LspHSTsnQp2gUHvZW5UexrulOJk8waQWAuC5gg+hofTpg==</ds:SignatureValue>
                    <ds:KeyInfo Id="KI-dc93bdc4-dddf-4b97-89a1-088deb06cdd2">
                      <wsse:SecurityTokenReference wsu:Id="STR-57f15772-5394-4e45-9c80-d380249bc663">
                        <wsse:Reference URI="#X509-e4795298-c4a8-467a-a10a-638ffb58a38a" ValueType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-x509-token-profile-1.0#X509v3"/>
                      </wsse:SecurityTokenReference>
                    </ds:KeyInfo>
                  </ds:Signature>
                </wsse:Security>
              </soap:Header>
              <soap:Body xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" wsu:Id="id-e0b059b4-ae41-4af8-b4ee-8a842238f53c">
                <ns3:annehmenAuftragRequest xmlns:ns3="http://spri.telekom.de/oss/v4/envelope" xmlns:ns2="http://spri.telekom.de/oss/v4/message" xmlns:ns4="http://spri.telekom.de/oss/v4/order" xmlns:ns5="http://spri.telekom.de/oss/v4/complex" xmlns:ns6="http://spri.telekom.de/oss/v4/fttx">
                  <control>
                    <zeitstempel>'.date(\DateTimeInterface::RFC3339_EXTENDED).'</zeitstempel>
                    <majorRelease>04</majorRelease>
                    <minorRelease>30</minorRelease>
                    <signaturId>
                      <issuer>CN=AVAible Systems, OU=SPRI RS, O=AVAible Systems, L=Luebeck, ST=Schleswig-Holstein, C=DE</issuer>
                      <serial>1235766675432</serial>
                    </signaturId>
                  </control>
                  <auftrag>
                    <externeAuftragsnummer>PV.AVA.13.1.'.sprintf('%02d', $this->count_Auftrag).'</externeAuftragsnummer>
                    <auftraggeber>
                      <auftraggebernummer>AVA1203666</auftraggebernummer>
                      <leistungsnummer>AVA7'.substr(time(),5, strlen(time())).'</leistungsnummer>
                    </auftraggeber>
                    <geschaeftsfall xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="ns4:ProviderwechselType">
                      <lineId>DEU.SWL451.0000300666</lineId>
                      <vorabstimmungId>DEU.PV.SWL.ELR3666</vorabstimmungId>
                      <ansprechpartner>
                        <auftragsmanagement>
                          <anrede>2</anrede>
                          <nachname>Provider-Neu</nachname>
                          <telefonnummer>+49 451 61310</telefonnummer>
                        </auftragsmanagement>
                      </ansprechpartner>
                      <termine>
                        <auftraggeberWunschtermin></auftraggeberWunschtermin>
                      </termine>
                      <auftragsposition>
                        <produkt xsi:type="ns6:ProduktFTTXType">
                          <bezeichner>FTTH L2 XGSPON 600 300</bezeichner>
                        </produkt>
                        <geschaeftsfallProdukt xsi:type="ns6:FTTXProviderwechselType">
                          <standortA>
                            <person>
                              <anrede>2</anrede>
                              <nachname>Anbieterwechsler</nachname>
                            </person>
                            <strasse>
                              <strassenname>Niels-Bohr-Ring</strassenname>
                              <hausnummer>15</hausnummer>
                            </strasse>
                            <land>de</land>
                            <postleitzahl>23568</postleitzahl>
                            <ort>
                              <ortsname>Lübeck</ortsname>
                            </ort>
                          </standortA>
                        </geschaeftsfallProdukt>
                      </auftragsposition>
                    </geschaeftsfall>
                    <geschaeftsfallArt>Endkundenanbieterwechsel</geschaeftsfallArt>
                    <aenderungskennzeichen>Standard</aenderungskennzeichen>
                  </auftrag>
                </ns3:annehmenAuftragRequest>
              </soap:Body>
            </soap:Envelope>';

        $this->request_auftrag_providerwechsel_nps = '<?xml version="1.0" encoding="UTF-8"?>
            <soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
              <soap:Header>
                <wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" soap:mustUnderstand="1">
                  <wsse:BinarySecurityToken EncodingType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary" ValueType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-x509-token-profile-1.0#X509v3" wsu:Id="X509-e4795298-c4a8-467a-a10a-638ffb58a38a">MIIDmzCCAoOgAwIBAgIEcYUGOTANBgkqhkiG9w0BAQsFADB+MQswCQYDVQQGEwJERTEMMAoGA1UECBMDTlJXMQ8wDQYDVQQHEwZEVUVSRU4xHjAcBgNVBAoTFUVuZ2hvdXNlIFN5c3RlbXMgTHRkLjEQMA4GA1UECxMHU1BSSSBSUzEeMBwGA1UEAxMVRW5naG91c2UgU3lzdGVtcyBMdGQuMB4XDTIxMDYyMzE2MzkzMVoXDTMxMDYyMTE2MzkzMVowfjELMAkGA1UEBhMCREUxDDAKBgNVBAgTA05SVzEPMA0GA1UEBxMGRFVFUkVOMR4wHAYDVQQKExVFbmdob3VzZSBTeXN0ZW1zIEx0ZC4xEDAOBgNVBAsTB1NQUkkgUlMxHjAcBgNVBAMTFUVuZ2hvdXNlIFN5c3RlbXMgTHRkLjCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBAIpv2i4uPVsJAwMV+5i/JOCsJPGcU8hEaezvS4kdJcELwLCHWiD6DThKb6ou23lk64nkA7bztpsuRQ9mOl8d89RtN/azKpZ13pMVkC7hXHyTr3kBGTPyYEQJq2pPwYJwB2yq/s8eZtPnw/4N2aa+vxmdglTpLy/RFb9wS0sY76ql8PhyIqmJ/voz02AdAdJPgiVypteKHL9qNvBRsP6M58WiS4GCoS4RoaG3SSqhpGzwM3MysmRuttQSN8Leh6ndDOpx2H7CVEEA/oiXgoYm77pIOOHq2p+8r+cMnjk5Q0WFn0vfs4jeWX5UCZjx/2QGQjJa4R1Se1UZkCSoygZt8P8CAwEAAaMhMB8wHQYDVR0OBBYEFOZgtmMVgA9GI9+vM5PWesJ1UUB+MA0GCSqGSIb3DQEBCwUAA4IBAQAbR649eRec/TKCg0AG+m8WO0svI/IH3oUAIaf+TN5CT23U4JJZVoKY8DlO9zlVFaVK7jKjJAdbOqpHIUPbSMwr0TX6DqMuVgGSzs3Zham4Uflv2kslbwDBhiadKbf2dSqkVHt2oVLodzH7lFQ17Y1KWvl8Ss68gcaH6XqXOB54/iCms8zp+SwYLoykNcF0e2WR5BrnQHjQ1wdJeiVnEmFcMXNN9tdhRMt6zIUWDbnwkFQM9ZQfQf+JAj2OmtrtjLqy9fktj2TZByQ7D8fZUFPRSFktb1tA5zM7AoHcqeeDMA/HI/XOC9B7HE44j0xIzJ2OSmLh8l2fgYcxTyPWhKTd</wsse:BinarySecurityToken>
                  <ds:Signature xmlns:ds="http://www.w3.org/2000/09/xmldsig#" Id="SIG-e1a666ac-0d63-453e-a54e-e74d9b013cc3">
                    <ds:SignedInfo>
                      <ds:CanonicalizationMethod Algorithm="http://www.w3.org/2001/10/xml-exc-c14n#">
                        <ec:InclusiveNamespaces xmlns:ec="http://www.w3.org/2001/10/xml-exc-c14n#" PrefixList="soap"/>
                      </ds:CanonicalizationMethod>
                      <ds:SignatureMethod Algorithm="http://www.w3.org/2000/09/xmldsig#rsa-sha1"/>
                      <ds:Reference URI="#id-e0b059b4-ae41-4af8-b4ee-8a842238f53c">
                        <ds:Transforms>
                          <ds:Transform Algorithm="http://www.w3.org/2001/10/xml-exc-c14n#"/>
                        </ds:Transforms>
                        <ds:DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1"/>
                        <ds:DigestValue>FFvsa6AmFaMM79xs9DmvycAq2Iw=</ds:DigestValue>
                      </ds:Reference>
                    </ds:SignedInfo>
                    <ds:SignatureValue>IJtjurkOfMn3Z8GCABXzNjjXwjtt+A5WtzHkUxjCCP+iKrQzwtCWl10GnXUdshY0afwA07puiBKoBxD0azIL7mjTyQ0Z5o7uXWTg9jo1H/Y9DGRa2iuFhtwnBP1PMlN6zpC1XK4z4K3SNyK+uAUfVk6aTJH7+lNB6gHl3njgrcFeDext6h+XcTT5Q6QawVr5GiZYv7PThBxIbGoH8yDsxn8VRZ8vMUIiRF/+wNKKOLZRPpiyJdC/4S6uyAR68frqpdoqH/T4iLcRiM3eqzN3J4JHZaRPQqU5OFRIBS6C+LspHSTsnQp2gUHvZW5UexrulOJk8waQWAuC5gg+hofTpg==</ds:SignatureValue>
                    <ds:KeyInfo Id="KI-dc93bdc4-dddf-4b97-89a1-088deb06cdd2">
                      <wsse:SecurityTokenReference wsu:Id="STR-57f15772-5394-4e45-9c80-d380249bc663">
                        <wsse:Reference URI="#X509-e4795298-c4a8-467a-a10a-638ffb58a38a" ValueType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-x509-token-profile-1.0#X509v3"/>
                      </wsse:SecurityTokenReference>
                    </ds:KeyInfo>
                  </ds:Signature>
                </wsse:Security>
              </soap:Header>
              <soap:Body xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" wsu:Id="id-e0b059b4-ae41-4af8-b4ee-8a842238f53c">
                <ns3:annehmenAuftragRequest xmlns:ns3="http://spri.telekom.de/oss/v4/envelope" xmlns:ns2="http://spri.telekom.de/oss/v4/message" xmlns:ns4="http://spri.telekom.de/oss/v4/order" xmlns:ns5="http://spri.telekom.de/oss/v4/complex" xmlns:ns6="http://spri.telekom.de/oss/v4/fttx">
                  <control>
                    <zeitstempel>'.date(\DateTimeInterface::RFC3339_EXTENDED).'</zeitstempel>
                    <majorRelease>04</majorRelease>
                    <minorRelease>30</minorRelease>
                    <signaturId>
                      <issuer>CN=NPS Webservice, OU=SPRI RS, O=ACME Providing Systems, L=Luebeck, ST=Schleswig-Holstein, C=DE</issuer>
                      <serial>6662345775432</serial>
                    </signaturId>
                  </control>
                  <auftrag>
                    <externeAuftragsnummer>NPS.13.1.'.sprintf('%02d', $this->count_Auftrag).'</externeAuftragsnummer>
                    <auftraggeber>
                      <auftraggebernummer>NPS6663021</auftraggebernummer>
                      <leistungsnummer>NPSS'.substr(time(),5, strlen(time())).'</leistungsnummer>
                    </auftraggeber>
                    <geschaeftsfall xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="ns4:ProviderwechselType">
                      <lineId></lineId>
                      <vorabstimmungId>DEU.ACME.PV.'.substr(time(),8, strlen(time())).'</vorabstimmungId>
                      <ansprechpartner>
                        <auftragsmanagement>
                          <anrede>2</anrede>
                          <nachname>Provider-Neu</nachname>
                          <telefonnummer>+49 451 61310</telefonnummer>
                        </auftragsmanagement>
                      </ansprechpartner>
                      <termine>
                        <auftraggeberWunschtermin></auftraggeberWunschtermin>
                      </termine>
                      <auftragsposition>
                        <produkt xsi:type="ns6:ProduktFTTXType">
                          <bezeichner>FTTH L2 XGSPON 600 300</bezeichner>
                        </produkt>
                        <geschaeftsfallProdukt xsi:type="ns6:FTTXProviderwechselType">
                          <standortA>
                            <person>
                              <anrede>2</anrede>
                              <nachname>Anbieterwechsler</nachname>
                            </person>
                            <strasse>
                              <strassenname>Niels-Bohr-Ring</strassenname>
                              <hausnummer>15</hausnummer>
                            </strasse>
                            <land>de</land>
                            <postleitzahl>23568</postleitzahl>
                            <ort>
                              <ortsname>Lübeck</ortsname>
                            </ort>
                          </standortA>
                        </geschaeftsfallProdukt>
                      </auftragsposition>
                    </geschaeftsfall>
                    <geschaeftsfallArt>Endkundenanbieterwechsel</geschaeftsfallArt>
                    <aenderungskennzeichen>Standard</aenderungskennzeichen>
                  </auftrag>
                </ns3:annehmenAuftragRequest>
              </soap:Body>
            </soap:Envelope>';

        $this->msg_ruem_pv = '<?xml version="1.0" encoding="UTF-8"?>
            <SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ns1="http://spri.telekom.de/oss/v4/message" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:ns2="http://spri.telekom.de/oss/v4/envelope"><SOAP-ENV:Header><wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" SOAP-ENV:mustUnderstand="1"><wsse:BinarySecurityToken xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" EncodingType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary" wsu:Id="pfx5fc09ad5-5662-ff45-78c2-065dbbb338a5" ValueType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-x509-token-profile-1.0#X509v3">MIIExjCCAy6gAwIBAgIUeBxKYJPCU7m1oRgwfZqD8/cEBLAwDQYJKoZIhvcNAQELBQAwbjELMAkGA1UEBhMCREUxGzAZBgNVBAgMElNjaGxlc3dpZy1Ib2xzdGVpbjEQMA4GA1UEBwwHTHVlYmVjazEXMBUGA1UECgwOVHJhdmVOZXR6IEdtYkgxFzAVBgNVBAMMDlRyYXZlTmV0eiBPQVNTMB4XDTI0MDkwNTEwMTI1N1oXDTM0MDkwMzEwMTI1N1owbjELMAkGA1UEBhMCREUxGzAZBgNVBAgMElNjaGxlc3dpZy1Ib2xzdGVpbjEQMA4GA1UEBwwHTHVlYmVjazEXMBUGA1UECgwOVHJhdmVOZXR6IEdtYkgxFzAVBgNVBAMMDlRyYXZlTmV0eiBPQVNTMIIBojANBgkqhkiG9w0BAQEFAAOCAY8AMIIBigKCAYEA5rOc/Og2pJT94iq4jtOG/knXNu7GAosU4f9B8S4rUTQ/63yeZVafN3jIhaIVQQNPsyZtLni8bazsL/xmQ1rP2kmboom1rQYzTKf8c1ZtPOBzvPYveP2Umn0ivSK0PlHGzLS/xAGSWiW5hMO4VDVdLHTpbAIEdHFz7pGZjtIQ3JSUCEyAjfC0CK+0H6TXOeKxSpEDHRUr1aLDEPG/xTOi7j8XuKWFOaRNiCFFvBrbJdSAzwdirHRr2i3CIUvPVwVhbodZH1gsulmWV4ZUMDr9jm6W9XADV0Bj5L0nx74cxnfTUOLlcvzidnCHqj2pW+uJO+VuzxBShq+/n0WeF0nD2aS7tX+3Qkl7obRpPAR2ew3a3x+nM8lj/yHqbQpZ5m2sKRM+f4mwqWn0O4nqSR7EfKHE/kvGoFInh6ynI9uvdiCQ+5vprlxSFLyTj/z5JkZmJuYdb1cyygACfHMm2wEvj6dRgSsu0rYkW3rTVyuNL2jSwY92N//xFZOOqybtjKupAgMBAAGjXDBaMAkGA1UdEwQCMAAwCwYDVR0PBAQDAgXgMCEGA1UdEQQaMBiCFnNydmtzb2EwMS50cmF2ZWtvbS5uZXQwHQYDVR0OBBYEFOizAtzDFkOL+vrRYreIfNbODrQqMA0GCSqGSIb3DQEBCwUAA4IBgQB28C3xrMLC9q71udqqM1jvL2DwitaNACTejkxPTPUpsmEvK6tUa3vTmd0Sny6Al2AZ3exvmb30lmPAlff/srjJMSBOZ3kPlPpQBuq9kkMkmZgUcNYAswQ2tOcTyLfwibkj09aDWQBJqchP1ru5ePBx150vYR/On+diJ9C2Cc8p0qxPOWUP1b2mGESf4jEX0yO7PYg4lMLueVX7dcq2Ea3MFm/6hH2sPRln22J/5CFV2GMsUZKYA/y5t+uLvx0FOqxtT/9drmsHQIRoekOD0ZYALyfJKvKsJ0k0KAO9u2pkB3m6bz9bMwfsp+y/KOtKDIkKnZ2cSmMoCG0ulAJyKGc7yAMWtYPtORj2stsfp8pzUZWNNZv0kdICC6mO7SFvSr0BrQaTLWNTriSMqfT2mCqAU4uy0QR/Muge5ggGUKuD7+SzYQ53OdsCLmYHIlWvv/icUojH8OfEGCfk73vyFqVLYyKpTmzI4vrmtAtNjuC6Y4tNDWefbc+YcVZgis6sQ/M=</wsse:BinarySecurityToken><ds:Signature xmlns:ds="http://www.w3.org/2000/09/xmldsig#">
              <ds:SignedInfo><ds:CanonicalizationMethod Algorithm="http://www.w3.org/2001/10/xml-exc-c14n#"/>
                <ds:SignatureMethod Algorithm="http://www.w3.org/2000/09/xmldsig#rsa-sha1"/>
              <ds:Reference URI="#pfxf27cdd6e-6e84-f9b9-3ccb-c3ef1496ed60"><ds:Transforms><ds:Transform Algorithm="http://www.w3.org/2001/10/xml-exc-c14n#"/></ds:Transforms><ds:DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1"/><ds:DigestValue>+uF9Gvl6UfLcnzm7ADv+Zu2OfMc=</ds:DigestValue></ds:Reference></ds:SignedInfo><ds:SignatureValue>rx3Y4586SEuNRZGoTPGOMGosUHTMg8bq5gBVdNryBc8nZkG1swjfNlytHBhocLf1zTbCKyst1rOE9mfvjG4Yl2hN07O6Qm9iLBiv57ZbfyPfpWHkCjfETLLAhn9Enkef0hN3hiC+ThgOHivZrxkgGD3MK7djmhNNqgG+LENbxiD0Gz6eI+mfLgbyS3fDsedxa1tN76qGi29uQMK/6Sa+gYYo3Hg9UKa0s/4UMrAR7xHSdi0hLIbx7a8GwPbKUYBmluKRO7i42gmWioqzB6qvxKdGmPmq4cN7/qRoEV8Z1QYLy42vD0sVySuV0h7F3CxkOpYyE1YN6wU+Y7Jqogv60mYrVU0V4fmQWYNYRiGoM2+nwndNintit20S8y34ptXXvBxAMOcP1rr2GbcbNIvWUgk2I4VtJYtP2LjYUuQeF5OAoSNppKugEf8TO9Lhlf0Nu1N4cWmTWqgJaZZIRO1FT6Q7fnQSU00jSGnekIWvgv2QPKZx1raJQOhzYRBaNHia</ds:SignatureValue>
            <ds:KeyInfo><wsse:SecurityTokenReference><wsse:Reference ValueType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-x509-token-profile-1.0#X509v3" URI="#pfx5fc09ad5-5662-ff45-78c2-065dbbb338a5"/></wsse:SecurityTokenReference></ds:KeyInfo></ds:Signature></wsse:Security></SOAP-ENV:Header><SOAP-ENV:Body xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" wsu:Id="pfxf27cdd6e-6e84-f9b9-3ccb-c3ef1496ed60">
            <ns2:annehmenMeldungRequest>
              <control>
                <zeitstempel>2025-03-13T15:59:16+00:00</zeitstempel><majorRelease>04</majorRelease><minorRelease>30</minorRelease>
                <signaturId><issuer>CN=AVAible Systems, OU=SPRI RS, O=AVAible Systems, L=Luebeck, ST=Schleswig-Holstein, C=DE</issuer><serial>1235766675432</serial></signaturId>
              </control>
              <meldung>
                <auftragstyp>
                  <geschaeftsfall>PV</geschaeftsfall>
                  <aenderungsKennzeichen>Standard</aenderungsKennzeichen>
                  <geschaeftsfallart>Endkundenanbieterwechsel</geschaeftsfallart>
                </auftragstyp>
                <meldungstyp xsi:type="ns1:MeldungstypRUEM-PVType">
                  <meldungsattribute>
                    <vertragsnummer>8563650005</vertragsnummer>
                    <externeAuftragsnummer>EXT.13.1.13</externeAuftragsnummer>
                    <auftraggebernummer>NPS6663021</auftraggebernummer>
                    <vorabstimmungId>DEU.AVA.PV.741</vorabstimmungId>
                    <anschluss>
                      <lineId>DEU.SWL451.0085636</lineId>
                    </anschluss>
                      <abgebenderProvider><providername>AVAible S/PRI Technology</providername><zustimmungProviderwechsel>J</zustimmungProviderwechsel></abgebenderProvider>
                  </meldungsattribute>
                  <meldungspositionen>
                    <position>
                      <meldungscode>0021</meldungscode>
                      <meldungstext>Antwort des abgebenden Provider.</meldungstext>
                    </position>
                  </meldungspositionen>
                </meldungstyp>
              </meldung>
            </ns2:annehmenMeldungRequest></SOAP-ENV:Body></SOAP-ENV:Envelope>';

        $this->msg_tbk_ag = '<?xml version="1.0" encoding="UTF-8"?>
            <SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ns1="http://spri.telekom.de/oss/v4/message" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:ns2="http://spri.telekom.de/oss/v4/envelope"><SOAP-ENV:Header><wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" SOAP-ENV:mustUnderstand="1"><wsse:BinarySecurityToken xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" EncodingType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary" wsu:Id="pfx5fc09ad5-5662-ff45-78c2-065dbbb338a5" ValueType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-x509-token-profile-1.0#X509v3">MIIExjCCAy6gAwIBAgIUeBxKYJPCU7m1oRgwfZqD8/cEBLAwDQYJKoZIhvcNAQELBQAwbjELMAkGA1UEBhMCREUxGzAZBgNVBAgMElNjaGxlc3dpZy1Ib2xzdGVpbjEQMA4GA1UEBwwHTHVlYmVjazEXMBUGA1UECgwOVHJhdmVOZXR6IEdtYkgxFzAVBgNVBAMMDlRyYXZlTmV0eiBPQVNTMB4XDTI0MDkwNTEwMTI1N1oXDTM0MDkwMzEwMTI1N1owbjELMAkGA1UEBhMCREUxGzAZBgNVBAgMElNjaGxlc3dpZy1Ib2xzdGVpbjEQMA4GA1UEBwwHTHVlYmVjazEXMBUGA1UECgwOVHJhdmVOZXR6IEdtYkgxFzAVBgNVBAMMDlRyYXZlTmV0eiBPQVNTMIIBojANBgkqhkiG9w0BAQEFAAOCAY8AMIIBigKCAYEA5rOc/Og2pJT94iq4jtOG/knXNu7GAosU4f9B8S4rUTQ/63yeZVafN3jIhaIVQQNPsyZtLni8bazsL/xmQ1rP2kmboom1rQYzTKf8c1ZtPOBzvPYveP2Umn0ivSK0PlHGzLS/xAGSWiW5hMO4VDVdLHTpbAIEdHFz7pGZjtIQ3JSUCEyAjfC0CK+0H6TXOeKxSpEDHRUr1aLDEPG/xTOi7j8XuKWFOaRNiCFFvBrbJdSAzwdirHRr2i3CIUvPVwVhbodZH1gsulmWV4ZUMDr9jm6W9XADV0Bj5L0nx74cxnfTUOLlcvzidnCHqj2pW+uJO+VuzxBShq+/n0WeF0nD2aS7tX+3Qkl7obRpPAR2ew3a3x+nM8lj/yHqbQpZ5m2sKRM+f4mwqWn0O4nqSR7EfKHE/kvGoFInh6ynI9uvdiCQ+5vprlxSFLyTj/z5JkZmJuYdb1cyygACfHMm2wEvj6dRgSsu0rYkW3rTVyuNL2jSwY92N//xFZOOqybtjKupAgMBAAGjXDBaMAkGA1UdEwQCMAAwCwYDVR0PBAQDAgXgMCEGA1UdEQQaMBiCFnNydmtzb2EwMS50cmF2ZWtvbS5uZXQwHQYDVR0OBBYEFOizAtzDFkOL+vrRYreIfNbODrQqMA0GCSqGSIb3DQEBCwUAA4IBgQB28C3xrMLC9q71udqqM1jvL2DwitaNACTejkxPTPUpsmEvK6tUa3vTmd0Sny6Al2AZ3exvmb30lmPAlff/srjJMSBOZ3kPlPpQBuq9kkMkmZgUcNYAswQ2tOcTyLfwibkj09aDWQBJqchP1ru5ePBx150vYR/On+diJ9C2Cc8p0qxPOWUP1b2mGESf4jEX0yO7PYg4lMLueVX7dcq2Ea3MFm/6hH2sPRln22J/5CFV2GMsUZKYA/y5t+uLvx0FOqxtT/9drmsHQIRoekOD0ZYALyfJKvKsJ0k0KAO9u2pkB3m6bz9bMwfsp+y/KOtKDIkKnZ2cSmMoCG0ulAJyKGc7yAMWtYPtORj2stsfp8pzUZWNNZv0kdICC6mO7SFvSr0BrQaTLWNTriSMqfT2mCqAU4uy0QR/Muge5ggGUKuD7+SzYQ53OdsCLmYHIlWvv/icUojH8OfEGCfk73vyFqVLYyKpTmzI4vrmtAtNjuC6Y4tNDWefbc+YcVZgis6sQ/M=</wsse:BinarySecurityToken><ds:Signature xmlns:ds="http://www.w3.org/2000/09/xmldsig#">
              <ds:SignedInfo><ds:CanonicalizationMethod Algorithm="http://www.w3.org/2001/10/xml-exc-c14n#"/>
                <ds:SignatureMethod Algorithm="http://www.w3.org/2000/09/xmldsig#rsa-sha1"/>
              <ds:Reference URI="#pfxf27cdd6e-6e84-f9b9-3ccb-c3ef1496ed60"><ds:Transforms><ds:Transform Algorithm="http://www.w3.org/2001/10/xml-exc-c14n#"/></ds:Transforms><ds:DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1"/><ds:DigestValue>+uF9Gvl6UfLcnzm7ADv+Zu2OfMc=</ds:DigestValue></ds:Reference></ds:SignedInfo><ds:SignatureValue>rx3Y4586SEuNRZGoTPGOMGosUHTMg8bq5gBVdNryBc8nZkG1swjfNlytHBhocLf1zTbCKyst1rOE9mfvjG4Yl2hN07O6Qm9iLBiv57ZbfyPfpWHkCjfETLLAhn9Enkef0hN3hiC+ThgOHivZrxkgGD3MK7djmhNNqgG+LENbxiD0Gz6eI+mfLgbyS3fDsedxa1tN76qGi29uQMK/6Sa+gYYo3Hg9UKa0s/4UMrAR7xHSdi0hLIbx7a8GwPbKUYBmluKRO7i42gmWioqzB6qvxKdGmPmq4cN7/qRoEV8Z1QYLy42vD0sVySuV0h7F3CxkOpYyE1YN6wU+Y7Jqogv60mYrVU0V4fmQWYNYRiGoM2+nwndNintit20S8y34ptXXvBxAMOcP1rr2GbcbNIvWUgk2I4VtJYtP2LjYUuQeF5OAoSNppKugEf8TO9Lhlf0Nu1N4cWmTWqgJaZZIRO1FT6Q7fnQSU00jSGnekIWvgv2QPKZx1raJQOhzYRBaNHia</ds:SignatureValue>
            <ds:KeyInfo><wsse:SecurityTokenReference><wsse:Reference ValueType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-x509-token-profile-1.0#X509v3" URI="#pfx5fc09ad5-5662-ff45-78c2-065dbbb338a5"/></wsse:SecurityTokenReference></ds:KeyInfo></ds:Signature></wsse:Security></SOAP-ENV:Header><SOAP-ENV:Body xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" wsu:Id="pfxf27cdd6e-6e84-f9b9-3ccb-c3ef1496ed60">
            <ns2:annehmenMeldungRequest>
              <control>
                <zeitstempel>2025-03-13T15:59:16+00:00</zeitstempel><majorRelease>04</majorRelease><minorRelease>30</minorRelease>
                <signaturId><issuer>CN=AVAible Systems, OU=SPRI RS, O=AVAible Systems, L=Luebeck, ST=Schleswig-Holstein, C=DE</issuer><serial>1235766675432</serial></signaturId>
              </control>
              <meldung>
                <auftragstyp>
                  <geschaeftsfall>EST</geschaeftsfall>
                  <aenderungsKennzeichen>Standard</aenderungsKennzeichen>
                  <geschaeftsfallart>Endkundenanbieterwechsel</geschaeftsfallart>
                </auftragstyp>
                <meldungstyp xsi:type="ns1:MeldungstypTBK-AGType">
                  <meldungsattribute>
                    <vertragsnummer></vertragsnummer>
                    <externeAuftragsnummer></externeAuftragsnummer>
                    <auftraggebernummer></auftraggebernummer>
                    <termin></termin>
                  </meldungsattribute>
                  <meldungspositionen>
                    <position>
                      <meldungscode>7030</meldungscode>
                      <meldungstext>Hinderungsgrund für letzten Endkundentermin ist beseitigt.</meldungstext>
                    </position>
                  </meldungspositionen>
                </meldungstyp>
              </meldung>
            </ns2:annehmenMeldungRequest></SOAP-ENV:Body></SOAP-ENV:Envelope>';

        $this->msg_zwm_ag = '<?xml version="1.0" encoding="UTF-8"?>
            <SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ns1="http://spri.telekom.de/oss/v4/message" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:ns2="http://spri.telekom.de/oss/v4/envelope"><SOAP-ENV:Header><wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" SOAP-ENV:mustUnderstand="1"><wsse:BinarySecurityToken xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" EncodingType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary" wsu:Id="pfx5fc09ad5-5662-ff45-78c2-065dbbb338a5" ValueType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-x509-token-profile-1.0#X509v3">MIIExjCCAy6gAwIBAgIUeBxKYJPCU7m1oRgwfZqD8/cEBLAwDQYJKoZIhvcNAQELBQAwbjELMAkGA1UEBhMCREUxGzAZBgNVBAgMElNjaGxlc3dpZy1Ib2xzdGVpbjEQMA4GA1UEBwwHTHVlYmVjazEXMBUGA1UECgwOVHJhdmVOZXR6IEdtYkgxFzAVBgNVBAMMDlRyYXZlTmV0eiBPQVNTMB4XDTI0MDkwNTEwMTI1N1oXDTM0MDkwMzEwMTI1N1owbjELMAkGA1UEBhMCREUxGzAZBgNVBAgMElNjaGxlc3dpZy1Ib2xzdGVpbjEQMA4GA1UEBwwHTHVlYmVjazEXMBUGA1UECgwOVHJhdmVOZXR6IEdtYkgxFzAVBgNVBAMMDlRyYXZlTmV0eiBPQVNTMIIBojANBgkqhkiG9w0BAQEFAAOCAY8AMIIBigKCAYEA5rOc/Og2pJT94iq4jtOG/knXNu7GAosU4f9B8S4rUTQ/63yeZVafN3jIhaIVQQNPsyZtLni8bazsL/xmQ1rP2kmboom1rQYzTKf8c1ZtPOBzvPYveP2Umn0ivSK0PlHGzLS/xAGSWiW5hMO4VDVdLHTpbAIEdHFz7pGZjtIQ3JSUCEyAjfC0CK+0H6TXOeKxSpEDHRUr1aLDEPG/xTOi7j8XuKWFOaRNiCFFvBrbJdSAzwdirHRr2i3CIUvPVwVhbodZH1gsulmWV4ZUMDr9jm6W9XADV0Bj5L0nx74cxnfTUOLlcvzidnCHqj2pW+uJO+VuzxBShq+/n0WeF0nD2aS7tX+3Qkl7obRpPAR2ew3a3x+nM8lj/yHqbQpZ5m2sKRM+f4mwqWn0O4nqSR7EfKHE/kvGoFInh6ynI9uvdiCQ+5vprlxSFLyTj/z5JkZmJuYdb1cyygACfHMm2wEvj6dRgSsu0rYkW3rTVyuNL2jSwY92N//xFZOOqybtjKupAgMBAAGjXDBaMAkGA1UdEwQCMAAwCwYDVR0PBAQDAgXgMCEGA1UdEQQaMBiCFnNydmtzb2EwMS50cmF2ZWtvbS5uZXQwHQYDVR0OBBYEFOizAtzDFkOL+vrRYreIfNbODrQqMA0GCSqGSIb3DQEBCwUAA4IBgQB28C3xrMLC9q71udqqM1jvL2DwitaNACTejkxPTPUpsmEvK6tUa3vTmd0Sny6Al2AZ3exvmb30lmPAlff/srjJMSBOZ3kPlPpQBuq9kkMkmZgUcNYAswQ2tOcTyLfwibkj09aDWQBJqchP1ru5ePBx150vYR/On+diJ9C2Cc8p0qxPOWUP1b2mGESf4jEX0yO7PYg4lMLueVX7dcq2Ea3MFm/6hH2sPRln22J/5CFV2GMsUZKYA/y5t+uLvx0FOqxtT/9drmsHQIRoekOD0ZYALyfJKvKsJ0k0KAO9u2pkB3m6bz9bMwfsp+y/KOtKDIkKnZ2cSmMoCG0ulAJyKGc7yAMWtYPtORj2stsfp8pzUZWNNZv0kdICC6mO7SFvSr0BrQaTLWNTriSMqfT2mCqAU4uy0QR/Muge5ggGUKuD7+SzYQ53OdsCLmYHIlWvv/icUojH8OfEGCfk73vyFqVLYyKpTmzI4vrmtAtNjuC6Y4tNDWefbc+YcVZgis6sQ/M=</wsse:BinarySecurityToken><ds:Signature xmlns:ds="http://www.w3.org/2000/09/xmldsig#">
              <ds:SignedInfo><ds:CanonicalizationMethod Algorithm="http://www.w3.org/2001/10/xml-exc-c14n#"/>
                <ds:SignatureMethod Algorithm="http://www.w3.org/2000/09/xmldsig#rsa-sha1"/>
              <ds:Reference URI="#pfxf27cdd6e-6e84-f9b9-3ccb-c3ef1496ed60"><ds:Transforms><ds:Transform Algorithm="http://www.w3.org/2001/10/xml-exc-c14n#"/></ds:Transforms><ds:DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1"/><ds:DigestValue>+uF9Gvl6UfLcnzm7ADv+Zu2OfMc=</ds:DigestValue></ds:Reference></ds:SignedInfo><ds:SignatureValue>rx3Y4586SEuNRZGoTPGOMGosUHTMg8bq5gBVdNryBc8nZkG1swjfNlytHBhocLf1zTbCKyst1rOE9mfvjG4Yl2hN07O6Qm9iLBiv57ZbfyPfpWHkCjfETLLAhn9Enkef0hN3hiC+ThgOHivZrxkgGD3MK7djmhNNqgG+LENbxiD0Gz6eI+mfLgbyS3fDsedxa1tN76qGi29uQMK/6Sa+gYYo3Hg9UKa0s/4UMrAR7xHSdi0hLIbx7a8GwPbKUYBmluKRO7i42gmWioqzB6qvxKdGmPmq4cN7/qRoEV8Z1QYLy42vD0sVySuV0h7F3CxkOpYyE1YN6wU+Y7Jqogv60mYrVU0V4fmQWYNYRiGoM2+nwndNintit20S8y34ptXXvBxAMOcP1rr2GbcbNIvWUgk2I4VtJYtP2LjYUuQeF5OAoSNppKugEf8TO9Lhlf0Nu1N4cWmTWqgJaZZIRO1FT6Q7fnQSU00jSGnekIWvgv2QPKZx1raJQOhzYRBaNHia</ds:SignatureValue>
            <ds:KeyInfo><wsse:SecurityTokenReference><wsse:Reference ValueType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-x509-token-profile-1.0#X509v3" URI="#pfx5fc09ad5-5662-ff45-78c2-065dbbb338a5"/></wsse:SecurityTokenReference></ds:KeyInfo></ds:Signature></wsse:Security></SOAP-ENV:Header><SOAP-ENV:Body xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" wsu:Id="pfxf27cdd6e-6e84-f9b9-3ccb-c3ef1496ed60">
            <ns2:annehmenMeldungRequest>
              <control>
                <zeitstempel>2025-03-13T15:59:16+00:00</zeitstempel><majorRelease>04</majorRelease><minorRelease>30</minorRelease>
                <signaturId><issuer>CN=AVAible Systems, OU=SPRI RS, O=AVAible Systems, L=Luebeck, ST=Schleswig-Holstein, C=DE</issuer><serial>1235766675432</serial></signaturId>
              </control>
              <meldung>
                <auftragstyp>
                  <geschaeftsfall></geschaeftsfall>
                  <aenderungsKennzeichen>Standard</aenderungsKennzeichen>
                  <geschaeftsfallart></geschaeftsfallart>
                </auftragstyp>
                <meldungstyp xsi:type="ns1:MeldungstypZWM-AGType">
                  <meldungsattribute>
                    <vertragsnummer></vertragsnummer>
                    <externeAuftragsnummer></externeAuftragsnummer>
                    <auftraggebernummer></auftraggebernummer>
                    <nachricht>Kunde öffnet die Tür nur nach Klopfzeichen! -..---..</nachricht>
                    <ansprechpartner>
                      <anrede>2</anrede>
                      <vorname>Jikuto</vorname>
                      <nachname>Kuruka</nachname>
                      <telefonnummer>+49 451 666111</telefonnummer>
                      <mobilfunknummer>0451 111666</mobilfunknummer>
                      <emailadresse>jikuto.kuruka@provider.de</emailadresse>
                      <rolle>Auftragsmanagement</rolle>
                    </ansprechpartner>
                  </meldungsattribute>
                  <meldungspositionen>
                    <position>
                      <meldungscode>7040</meldungscode>
                      <meldungstext>Ergänzende Information zum Auftrag.</meldungstext>
                    </position>
                  </meldungspositionen>
                </meldungstyp>
              </meldung>
            </ns2:annehmenMeldungRequest></SOAP-ENV:Body></SOAP-ENV:Envelope>';

        $this->msg_erlm_k = '<?xml version="1.0" encoding="UTF-8"?>
            <SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ns1="http://spri.telekom.de/oss/v4/message" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:ns2="http://spri.telekom.de/oss/v4/envelope"><SOAP-ENV:Header><wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" SOAP-ENV:mustUnderstand="1"><wsse:BinarySecurityToken xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" EncodingType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary" wsu:Id="pfx5fc09ad5-5662-ff45-78c2-065dbbb338a5" ValueType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-x509-token-profile-1.0#X509v3">MIIExjCCAy6gAwIBAgIUeBxKYJPCU7m1oRgwfZqD8/cEBLAwDQYJKoZIhvcNAQELBQAwbjELMAkGA1UEBhMCREUxGzAZBgNVBAgMElNjaGxlc3dpZy1Ib2xzdGVpbjEQMA4GA1UEBwwHTHVlYmVjazEXMBUGA1UECgwOVHJhdmVOZXR6IEdtYkgxFzAVBgNVBAMMDlRyYXZlTmV0eiBPQVNTMB4XDTI0MDkwNTEwMTI1N1oXDTM0MDkwMzEwMTI1N1owbjELMAkGA1UEBhMCREUxGzAZBgNVBAgMElNjaGxlc3dpZy1Ib2xzdGVpbjEQMA4GA1UEBwwHTHVlYmVjazEXMBUGA1UECgwOVHJhdmVOZXR6IEdtYkgxFzAVBgNVBAMMDlRyYXZlTmV0eiBPQVNTMIIBojANBgkqhkiG9w0BAQEFAAOCAY8AMIIBigKCAYEA5rOc/Og2pJT94iq4jtOG/knXNu7GAosU4f9B8S4rUTQ/63yeZVafN3jIhaIVQQNPsyZtLni8bazsL/xmQ1rP2kmboom1rQYzTKf8c1ZtPOBzvPYveP2Umn0ivSK0PlHGzLS/xAGSWiW5hMO4VDVdLHTpbAIEdHFz7pGZjtIQ3JSUCEyAjfC0CK+0H6TXOeKxSpEDHRUr1aLDEPG/xTOi7j8XuKWFOaRNiCFFvBrbJdSAzwdirHRr2i3CIUvPVwVhbodZH1gsulmWV4ZUMDr9jm6W9XADV0Bj5L0nx74cxnfTUOLlcvzidnCHqj2pW+uJO+VuzxBShq+/n0WeF0nD2aS7tX+3Qkl7obRpPAR2ew3a3x+nM8lj/yHqbQpZ5m2sKRM+f4mwqWn0O4nqSR7EfKHE/kvGoFInh6ynI9uvdiCQ+5vprlxSFLyTj/z5JkZmJuYdb1cyygACfHMm2wEvj6dRgSsu0rYkW3rTVyuNL2jSwY92N//xFZOOqybtjKupAgMBAAGjXDBaMAkGA1UdEwQCMAAwCwYDVR0PBAQDAgXgMCEGA1UdEQQaMBiCFnNydmtzb2EwMS50cmF2ZWtvbS5uZXQwHQYDVR0OBBYEFOizAtzDFkOL+vrRYreIfNbODrQqMA0GCSqGSIb3DQEBCwUAA4IBgQB28C3xrMLC9q71udqqM1jvL2DwitaNACTejkxPTPUpsmEvK6tUa3vTmd0Sny6Al2AZ3exvmb30lmPAlff/srjJMSBOZ3kPlPpQBuq9kkMkmZgUcNYAswQ2tOcTyLfwibkj09aDWQBJqchP1ru5ePBx150vYR/On+diJ9C2Cc8p0qxPOWUP1b2mGESf4jEX0yO7PYg4lMLueVX7dcq2Ea3MFm/6hH2sPRln22J/5CFV2GMsUZKYA/y5t+uLvx0FOqxtT/9drmsHQIRoekOD0ZYALyfJKvKsJ0k0KAO9u2pkB3m6bz9bMwfsp+y/KOtKDIkKnZ2cSmMoCG0ulAJyKGc7yAMWtYPtORj2stsfp8pzUZWNNZv0kdICC6mO7SFvSr0BrQaTLWNTriSMqfT2mCqAU4uy0QR/Muge5ggGUKuD7+SzYQ53OdsCLmYHIlWvv/icUojH8OfEGCfk73vyFqVLYyKpTmzI4vrmtAtNjuC6Y4tNDWefbc+YcVZgis6sQ/M=</wsse:BinarySecurityToken><ds:Signature xmlns:ds="http://www.w3.org/2000/09/xmldsig#">
              <ds:SignedInfo><ds:CanonicalizationMethod Algorithm="http://www.w3.org/2001/10/xml-exc-c14n#"/>
                <ds:SignatureMethod Algorithm="http://www.w3.org/2000/09/xmldsig#rsa-sha1"/>
              <ds:Reference URI="#pfxf27cdd6e-6e84-f9b9-3ccb-c3ef1496ed60"><ds:Transforms><ds:Transform Algorithm="http://www.w3.org/2001/10/xml-exc-c14n#"/></ds:Transforms><ds:DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1"/><ds:DigestValue>+uF9Gvl6UfLcnzm7ADv+Zu2OfMc=</ds:DigestValue></ds:Reference></ds:SignedInfo><ds:SignatureValue>rx3Y4586SEuNRZGoTPGOMGosUHTMg8bq5gBVdNryBc8nZkG1swjfNlytHBhocLf1zTbCKyst1rOE9mfvjG4Yl2hN07O6Qm9iLBiv57ZbfyPfpWHkCjfETLLAhn9Enkef0hN3hiC+ThgOHivZrxkgGD3MK7djmhNNqgG+LENbxiD0Gz6eI+mfLgbyS3fDsedxa1tN76qGi29uQMK/6Sa+gYYo3Hg9UKa0s/4UMrAR7xHSdi0hLIbx7a8GwPbKUYBmluKRO7i42gmWioqzB6qvxKdGmPmq4cN7/qRoEV8Z1QYLy42vD0sVySuV0h7F3CxkOpYyE1YN6wU+Y7Jqogv60mYrVU0V4fmQWYNYRiGoM2+nwndNintit20S8y34ptXXvBxAMOcP1rr2GbcbNIvWUgk2I4VtJYtP2LjYUuQeF5OAoSNppKugEf8TO9Lhlf0Nu1N4cWmTWqgJaZZIRO1FT6Q7fnQSU00jSGnekIWvgv2QPKZx1raJQOhzYRBaNHia</ds:SignatureValue>
            <ds:KeyInfo><wsse:SecurityTokenReference><wsse:Reference ValueType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-x509-token-profile-1.0#X509v3" URI="#pfx5fc09ad5-5662-ff45-78c2-065dbbb338a5"/></wsse:SecurityTokenReference></ds:KeyInfo></ds:Signature></wsse:Security></SOAP-ENV:Header><SOAP-ENV:Body xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" wsu:Id="pfxf27cdd6e-6e84-f9b9-3ccb-c3ef1496ed60">
            <ns2:annehmenMeldungRequest>
              <control>
                <zeitstempel>2025-03-13T15:59:16+00:00</zeitstempel><majorRelease>04</majorRelease><minorRelease>30</minorRelease>
                <signaturId><issuer>CN=AVAible Systems, OU=SPRI RS, O=AVAible Systems, L=Luebeck, ST=Schleswig-Holstein, C=DE</issuer><serial>1235766675432</serial></signaturId>
              </control>
              <meldung>
                <auftragstyp>
                  <geschaeftsfall>LAE</geschaeftsfall>
                  <aenderungsKennzeichen>Standard</aenderungsKennzeichen>
                  <geschaeftsfallart>Aenderung</geschaeftsfallart>
                </auftragstyp>
                <meldungstyp xsi:type="ns1:MeldungstypERLM-KType">
                  <meldungsattribute>
                    <vertragsnummer></vertragsnummer>
                    <externeAuftragsnummer></externeAuftragsnummer>
                    <auftraggebernummer></auftraggebernummer>
                  </meldungsattribute>
                  <meldungspositionen>
                    <position>
                      <meldungscode>0015</meldungscode>
                      <meldungstext>Antwort des abgebenden Provider.</meldungstext>
                    </position>
                  </meldungspositionen>
                </meldungstyp>
              </meldung>
            </ns2:annehmenMeldungRequest></SOAP-ENV:Body></SOAP-ENV:Envelope>';

        $this->request_auftrag_diagnose_get = '<?xml version="1.0" encoding="UTF-8"?>
            <soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
              <soap:Header>
                <wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" soap:mustUnderstand="1">
                  <wsse:BinarySecurityToken EncodingType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary" ValueType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-x509-token-profile-1.0#X509v3" wsu:Id="X509-932f8a9c-f08f-4eb3-935e-3f151f82938f">MIIDmzCCAoOgAwIBAgIEcYUGOTANBgkqhkiG9w0BAQsFADB+MQswCQYDVQQGEwJERTEMMAoGA1UECBMDTlJXMQ8wDQYDVQQHEwZEVUVSRU4xHjAcBgNVBAoTFUVuZ2hvdXNlIFN5c3RlbXMgTHRkLjEQMA4GA1UECxMHU1BSSSBSUzEeMBwGA1UEAxMVRW5naG91c2UgU3lzdGVtcyBMdGQuMB4XDTIxMDYyMzE2MzkzMVoXDTMxMDYyMTE2MzkzMVowfjELMAkGA1UEBhMCREUxDDAKBgNVBAgTA05SVzEPMA0GA1UEBxMGRFVFUkVOMR4wHAYDVQQKExVFbmdob3VzZSBTeXN0ZW1zIEx0ZC4xEDAOBgNVBAsTB1NQUkkgUlMxHjAcBgNVBAMTFUVuZ2hvdXNlIFN5c3RlbXMgTHRkLjCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBAIpv2i4uPVsJAwMV+5i/JOCsJPGcU8hEaezvS4kdJcELwLCHWiD6DThKb6ou23lk64nkA7bztpsuRQ9mOl8d89RtN/azKpZ13pMVkC7hXHyTr3kBGTPyYEQJq2pPwYJwB2yq/s8eZtPnw/4N2aa+vxmdglTpLy/RFb9wS0sY76ql8PhyIqmJ/voz02AdAdJPgiVypteKHL9qNvBRsP6M58WiS4GCoS4RoaG3SSqhpGzwM3MysmRuttQSN8Leh6ndDOpx2H7CVEEA/oiXgoYm77pIOOHq2p+8r+cMnjk5Q0WFn0vfs4jeWX5UCZjx/2QGQjJa4R1Se1UZkCSoygZt8P8CAwEAAaMhMB8wHQYDVR0OBBYEFOZgtmMVgA9GI9+vM5PWesJ1UUB+MA0GCSqGSIb3DQEBCwUAA4IBAQAbR649eRec/TKCg0AG+m8WO0svI/IH3oUAIaf+TN5CT23U4JJZVoKY8DlO9zlVFaVK7jKjJAdbOqpHIUPbSMwr0TX6DqMuVgGSzs3Zham4Uflv2kslbwDBhiadKbf2dSqkVHt2oVLodzH7lFQ17Y1KWvl8Ss68gcaH6XqXOB54/iCms8zp+SwYLoykNcF0e2WR5BrnQHjQ1wdJeiVnEmFcMXNN9tdhRMt6zIUWDbnwkFQM9ZQfQf+JAj2OmtrtjLqy9fktj2TZByQ7D8fZUFPRSFktb1tA5zM7AoHcqeeDMA/HI/XOC9B7HE44j0xIzJ2OSmLh8l2fgYcxTyPWhKTd</wsse:BinarySecurityToken>
                  <ds:Signature xmlns:ds="http://www.w3.org/2000/09/xmldsig#" Id="SIG-b22b333a-9eef-413c-a434-10ae7214b386">
                    <ds:SignedInfo>
                      <ds:CanonicalizationMethod Algorithm="http://www.w3.org/2001/10/xml-exc-c14n#">
                        <ec:InclusiveNamespaces xmlns:ec="http://www.w3.org/2001/10/xml-exc-c14n#" PrefixList="soap"/>
                      </ds:CanonicalizationMethod>
                      <ds:SignatureMethod Algorithm="http://www.w3.org/2000/09/xmldsig#rsa-sha1"/>
                      <ds:Reference URI="#id-588f15b3-9e45-481c-a7c9-a6612bcf3c43">
                        <ds:Transforms>
                          <ds:Transform Algorithm="http://www.w3.org/2001/10/xml-exc-c14n#"/>
                        </ds:Transforms>
                        <ds:DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1"/>
                        <ds:DigestValue>RYP/wK0UfLV3/IP1U2GEv+YNvCI=</ds:DigestValue>
                      </ds:Reference>
                    </ds:SignedInfo>
                    <ds:SignatureValue>ZWkc8i/cHhpg3cI+GyBmtWQYj1fpdGc++2fFU45doFvMug962Yscqd+Lboimnt38eh2wkzzG73LrewLmpail3/gBe0JQ7zJh3LcfxEoZij9ZoyAh562EXwPOEx9I6TB1UH7ffLzz46aYWPKSIfUyvnE4DpGZ1K9wn3rW30XM2THRFylgXrM9bayECpsZ8VDWFLLHC2COrHoaG/33nXIHg13WeYZ+f/L1PZ7ilg2vnjtmChg+NCVujWKmkQKfcqGbPDcxzFm8JhpY6iSvwvvpsdScPEEpWnLbsLLslrJbzYmuSNccg058lLN9HquPXdi92Jf63l+VNWV1WBr8l9wFGQ==</ds:SignatureValue>
                    <ds:KeyInfo Id="KI-ff5027dc-800f-455f-ae24-6c31255cb354">
                      <wsse:SecurityTokenReference wsu:Id="STR-c1899688-27da-4031-b6bc-51b63c8e9d46">
                        <wsse:Reference URI="#X509-932f8a9c-f08f-4eb3-935e-3f151f82938f" ValueType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-x509-token-profile-1.0#X509v3"/>
                      </wsse:SecurityTokenReference>
                    </ds:KeyInfo>
                  </ds:Signature>
                </wsse:Security>
              </soap:Header>
              <soap:Body xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" wsu:Id="id-588f15b3-9e45-481c-a7c9-a6612bcf3c43">
                <ns3:annehmenAuftragRequest xmlns:ns3="http://spri.telekom.de/oss/v4/envelope" xmlns:ns2="http://spri.telekom.de/oss/v4/message" xmlns:ns4="http://spri.telekom.de/oss/v4/order" xmlns:ns5="http://spri.telekom.de/oss/v4/complex" xmlns:ns6="http://spri.telekom.de/oss/v4/fttx">
                  <control>
                    <zeitstempel>'.date(\DateTimeInterface::RFC3339_EXTENDED).'</zeitstempel>
                    <majorRelease>04</majorRelease>
                    <minorRelease>30</minorRelease>
                    <signaturId>
                      <issuer>CN=AVAible Systems, OU=SPRI RS, O=AVAible Systems, L=Luebeck, ST=Schleswig-Holstein, C=DE</issuer>
                      <serial>1235766675432</serial>
                    </signaturId>
                  </control>
                  <auftrag>
                    <externeAuftragsnummer>DIAG.GET.1.'.sprintf('%02d', $this->count_Auftrag).'</externeAuftragsnummer>
                    <auftraggeber>
                      <auftraggebernummer>AVA1203666</auftraggebernummer>
                      <leistungsnummer>AVAL'.substr(time(),5, strlen(time())).'</leistungsnummer>
                    </auftraggeber>
                    <geschaeftsfall xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="ns4:DiagnoseGetType">
                      <vertragsnummer></vertragsnummer>
                      <lineId></lineId>
                      <auftragsposition>
                        <parameter>
                          <operation>GetPortStatus</operation>
                        </parameter>
                      </auftragsposition>
                    </geschaeftsfall>
                    <geschaeftsfallArt>Diagnose</geschaeftsfallArt>
                    <aenderungskennzeichen>Standard</aenderungskennzeichen>
                  </auftrag>
                </ns3:annehmenAuftragRequest>
              </soap:Body>
            </soap:Envelope>';
        $this->request_auftrag_diagnose_set = '<?xml version="1.0" encoding="UTF-8"?>
            <soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
              <soap:Header>
                <wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" soap:mustUnderstand="1">
                  <wsse:BinarySecurityToken EncodingType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary" ValueType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-x509-token-profile-1.0#X509v3" wsu:Id="X509-932f8a9c-f08f-4eb3-935e-3f151f82938f">MIIDmzCCAoOgAwIBAgIEcYUGOTANBgkqhkiG9w0BAQsFADB+MQswCQYDVQQGEwJERTEMMAoGA1UECBMDTlJXMQ8wDQYDVQQHEwZEVUVSRU4xHjAcBgNVBAoTFUVuZ2hvdXNlIFN5c3RlbXMgTHRkLjEQMA4GA1UECxMHU1BSSSBSUzEeMBwGA1UEAxMVRW5naG91c2UgU3lzdGVtcyBMdGQuMB4XDTIxMDYyMzE2MzkzMVoXDTMxMDYyMTE2MzkzMVowfjELMAkGA1UEBhMCREUxDDAKBgNVBAgTA05SVzEPMA0GA1UEBxMGRFVFUkVOMR4wHAYDVQQKExVFbmdob3VzZSBTeXN0ZW1zIEx0ZC4xEDAOBgNVBAsTB1NQUkkgUlMxHjAcBgNVBAMTFUVuZ2hvdXNlIFN5c3RlbXMgTHRkLjCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBAIpv2i4uPVsJAwMV+5i/JOCsJPGcU8hEaezvS4kdJcELwLCHWiD6DThKb6ou23lk64nkA7bztpsuRQ9mOl8d89RtN/azKpZ13pMVkC7hXHyTr3kBGTPyYEQJq2pPwYJwB2yq/s8eZtPnw/4N2aa+vxmdglTpLy/RFb9wS0sY76ql8PhyIqmJ/voz02AdAdJPgiVypteKHL9qNvBRsP6M58WiS4GCoS4RoaG3SSqhpGzwM3MysmRuttQSN8Leh6ndDOpx2H7CVEEA/oiXgoYm77pIOOHq2p+8r+cMnjk5Q0WFn0vfs4jeWX5UCZjx/2QGQjJa4R1Se1UZkCSoygZt8P8CAwEAAaMhMB8wHQYDVR0OBBYEFOZgtmMVgA9GI9+vM5PWesJ1UUB+MA0GCSqGSIb3DQEBCwUAA4IBAQAbR649eRec/TKCg0AG+m8WO0svI/IH3oUAIaf+TN5CT23U4JJZVoKY8DlO9zlVFaVK7jKjJAdbOqpHIUPbSMwr0TX6DqMuVgGSzs3Zham4Uflv2kslbwDBhiadKbf2dSqkVHt2oVLodzH7lFQ17Y1KWvl8Ss68gcaH6XqXOB54/iCms8zp+SwYLoykNcF0e2WR5BrnQHjQ1wdJeiVnEmFcMXNN9tdhRMt6zIUWDbnwkFQM9ZQfQf+JAj2OmtrtjLqy9fktj2TZByQ7D8fZUFPRSFktb1tA5zM7AoHcqeeDMA/HI/XOC9B7HE44j0xIzJ2OSmLh8l2fgYcxTyPWhKTd</wsse:BinarySecurityToken>
                  <ds:Signature xmlns:ds="http://www.w3.org/2000/09/xmldsig#" Id="SIG-b22b333a-9eef-413c-a434-10ae7214b386">
                    <ds:SignedInfo>
                      <ds:CanonicalizationMethod Algorithm="http://www.w3.org/2001/10/xml-exc-c14n#">
                        <ec:InclusiveNamespaces xmlns:ec="http://www.w3.org/2001/10/xml-exc-c14n#" PrefixList="soap"/>
                      </ds:CanonicalizationMethod>
                      <ds:SignatureMethod Algorithm="http://www.w3.org/2000/09/xmldsig#rsa-sha1"/>
                      <ds:Reference URI="#id-588f15b3-9e45-481c-a7c9-a6612bcf3c43">
                        <ds:Transforms>
                          <ds:Transform Algorithm="http://www.w3.org/2001/10/xml-exc-c14n#"/>
                        </ds:Transforms>
                        <ds:DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1"/>
                        <ds:DigestValue>RYP/wK0UfLV3/IP1U2GEv+YNvCI=</ds:DigestValue>
                      </ds:Reference>
                    </ds:SignedInfo>
                    <ds:SignatureValue>ZWkc8i/cHhpg3cI+GyBmtWQYj1fpdGc++2fFU45doFvMug962Yscqd+Lboimnt38eh2wkzzG73LrewLmpail3/gBe0JQ7zJh3LcfxEoZij9ZoyAh562EXwPOEx9I6TB1UH7ffLzz46aYWPKSIfUyvnE4DpGZ1K9wn3rW30XM2THRFylgXrM9bayECpsZ8VDWFLLHC2COrHoaG/33nXIHg13WeYZ+f/L1PZ7ilg2vnjtmChg+NCVujWKmkQKfcqGbPDcxzFm8JhpY6iSvwvvpsdScPEEpWnLbsLLslrJbzYmuSNccg058lLN9HquPXdi92Jf63l+VNWV1WBr8l9wFGQ==</ds:SignatureValue>
                    <ds:KeyInfo Id="KI-ff5027dc-800f-455f-ae24-6c31255cb354">
                      <wsse:SecurityTokenReference wsu:Id="STR-c1899688-27da-4031-b6bc-51b63c8e9d46">
                        <wsse:Reference URI="#X509-932f8a9c-f08f-4eb3-935e-3f151f82938f" ValueType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-x509-token-profile-1.0#X509v3"/>
                      </wsse:SecurityTokenReference>
                    </ds:KeyInfo>
                  </ds:Signature>
                </wsse:Security>
              </soap:Header>
              <soap:Body xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" wsu:Id="id-588f15b3-9e45-481c-a7c9-a6612bcf3c43">
                <ns3:annehmenAuftragRequest xmlns:ns3="http://spri.telekom.de/oss/v4/envelope" xmlns:ns2="http://spri.telekom.de/oss/v4/message" xmlns:ns4="http://spri.telekom.de/oss/v4/order" xmlns:ns5="http://spri.telekom.de/oss/v4/complex" xmlns:ns6="http://spri.telekom.de/oss/v4/fttx">
                  <control>
                    <zeitstempel>'.date(\DateTimeInterface::RFC3339_EXTENDED).'</zeitstempel>
                    <majorRelease>04</majorRelease>
                    <minorRelease>30</minorRelease>
                    <signaturId>
                      <issuer>CN=AVAible Systems, OU=SPRI RS, O=AVAible Systems, L=Luebeck, ST=Schleswig-Holstein, C=DE</issuer>
                      <serial>1235766675432</serial>
                    </signaturId>
                  </control>
                  <auftrag>
                    <externeAuftragsnummer>DIAG.SET.1.'.sprintf('%02d', $this->count_Auftrag).'</externeAuftragsnummer>
                    <auftraggeber>
                      <auftraggebernummer>AVA1203666</auftraggebernummer>
                      <leistungsnummer>AVAL'.substr(time(),5, strlen(time())).'</leistungsnummer>
                    </auftraggeber>
                    <geschaeftsfall xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="ns4:DiagnoseSetType">
                      <vertragsnummer></vertragsnummer>
                      <lineId></lineId>
                      <auftragsposition>
                        <parameter>
                          <operation>registrierenONT</operation>
                          <operation>PM:AVMG7B436A29</operation>
                          <operation>HiD:G47363SH25212Q</operation>
                        </parameter>
                      </auftragsposition>
                    </geschaeftsfall>
                    <geschaeftsfallArt>Diagnose</geschaeftsfallArt>
                    <aenderungskennzeichen>Standard</aenderungskennzeichen>
                  </auftrag>
                </ns3:annehmenAuftragRequest>
              </soap:Body>
            </soap:Envelope>';
        $this->request_auftrag_diagnose_bad = '<?xml version="1.0" encoding="UTF-8"?>
            <soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
              <soap:Header>
                <wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" soap:mustUnderstand="1">
                  <wsse:BinarySecurityToken EncodingType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary" ValueType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-x509-token-profile-1.0#X509v3" wsu:Id="X509-932f8a9c-f08f-4eb3-935e-3f151f82938f">MIIDmzCCAoOgAwIBAgIEcYUGOTANBgkqhkiG9w0BAQsFADB+MQswCQYDVQQGEwJERTEMMAoGA1UECBMDTlJXMQ8wDQYDVQQHEwZEVUVSRU4xHjAcBgNVBAoTFUVuZ2hvdXNlIFN5c3RlbXMgTHRkLjEQMA4GA1UECxMHU1BSSSBSUzEeMBwGA1UEAxMVRW5naG91c2UgU3lzdGVtcyBMdGQuMB4XDTIxMDYyMzE2MzkzMVoXDTMxMDYyMTE2MzkzMVowfjELMAkGA1UEBhMCREUxDDAKBgNVBAgTA05SVzEPMA0GA1UEBxMGRFVFUkVOMR4wHAYDVQQKExVFbmdob3VzZSBTeXN0ZW1zIEx0ZC4xEDAOBgNVBAsTB1NQUkkgUlMxHjAcBgNVBAMTFUVuZ2hvdXNlIFN5c3RlbXMgTHRkLjCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBAIpv2i4uPVsJAwMV+5i/JOCsJPGcU8hEaezvS4kdJcELwLCHWiD6DThKb6ou23lk64nkA7bztpsuRQ9mOl8d89RtN/azKpZ13pMVkC7hXHyTr3kBGTPyYEQJq2pPwYJwB2yq/s8eZtPnw/4N2aa+vxmdglTpLy/RFb9wS0sY76ql8PhyIqmJ/voz02AdAdJPgiVypteKHL9qNvBRsP6M58WiS4GCoS4RoaG3SSqhpGzwM3MysmRuttQSN8Leh6ndDOpx2H7CVEEA/oiXgoYm77pIOOHq2p+8r+cMnjk5Q0WFn0vfs4jeWX5UCZjx/2QGQjJa4R1Se1UZkCSoygZt8P8CAwEAAaMhMB8wHQYDVR0OBBYEFOZgtmMVgA9GI9+vM5PWesJ1UUB+MA0GCSqGSIb3DQEBCwUAA4IBAQAbR649eRec/TKCg0AG+m8WO0svI/IH3oUAIaf+TN5CT23U4JJZVoKY8DlO9zlVFaVK7jKjJAdbOqpHIUPbSMwr0TX6DqMuVgGSzs3Zham4Uflv2kslbwDBhiadKbf2dSqkVHt2oVLodzH7lFQ17Y1KWvl8Ss68gcaH6XqXOB54/iCms8zp+SwYLoykNcF0e2WR5BrnQHjQ1wdJeiVnEmFcMXNN9tdhRMt6zIUWDbnwkFQM9ZQfQf+JAj2OmtrtjLqy9fktj2TZByQ7D8fZUFPRSFktb1tA5zM7AoHcqeeDMA/HI/XOC9B7HE44j0xIzJ2OSmLh8l2fgYcxTyPWhKTd</wsse:BinarySecurityToken>
                  <ds:Signature xmlns:ds="http://www.w3.org/2000/09/xmldsig#" Id="SIG-b22b333a-9eef-413c-a434-10ae7214b386">
                    <ds:SignedInfo>
                      <ds:CanonicalizationMethod Algorithm="http://www.w3.org/2001/10/xml-exc-c14n#">
                        <ec:InclusiveNamespaces xmlns:ec="http://www.w3.org/2001/10/xml-exc-c14n#" PrefixList="soap"/>
                      </ds:CanonicalizationMethod>
                      <ds:SignatureMethod Algorithm="http://www.w3.org/2000/09/xmldsig#rsa-sha1"/>
                      <ds:Reference URI="#id-588f15b3-9e45-481c-a7c9-a6612bcf3c43">
                        <ds:Transforms>
                          <ds:Transform Algorithm="http://www.w3.org/2001/10/xml-exc-c14n#"/>
                        </ds:Transforms>
                        <ds:DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1"/>
                        <ds:DigestValue>RYP/wK0UfLV3/IP1U2GEv+YNvCI=</ds:DigestValue>
                      </ds:Reference>
                    </ds:SignedInfo>
                    <ds:SignatureValue>ZWkc8i/cHhpg3cI+GyBmtWQYj1fpdGc++2fFU45doFvMug962Yscqd+Lboimnt38eh2wkzzG73LrewLmpail3/gBe0JQ7zJh3LcfxEoZij9ZoyAh562EXwPOEx9I6TB1UH7ffLzz46aYWPKSIfUyvnE4DpGZ1K9wn3rW30XM2THRFylgXrM9bayECpsZ8VDWFLLHC2COrHoaG/33nXIHg13WeYZ+f/L1PZ7ilg2vnjtmChg+NCVujWKmkQKfcqGbPDcxzFm8JhpY6iSvwvvpsdScPEEpWnLbsLLslrJbzYmuSNccg058lLN9HquPXdi92Jf63l+VNWV1WBr8l9wFGQ==</ds:SignatureValue>
                    <ds:KeyInfo Id="KI-ff5027dc-800f-455f-ae24-6c31255cb354">
                      <wsse:SecurityTokenReference wsu:Id="STR-c1899688-27da-4031-b6bc-51b63c8e9d46">
                        <wsse:Reference URI="#X509-932f8a9c-f08f-4eb3-935e-3f151f82938f" ValueType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-x509-token-profile-1.0#X509v3"/>
                      </wsse:SecurityTokenReference>
                    </ds:KeyInfo>
                  </ds:Signature>
                </wsse:Security>
              </soap:Header>
              <soap:Body xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" wsu:Id="id-588f15b3-9e45-481c-a7c9-a6612bcf3c43">
                <ns3:annehmenAuftragRequest xmlns:ns3="http://spri.telekom.de/oss/v4/envelope" xmlns:ns2="http://spri.telekom.de/oss/v4/message" xmlns:ns4="http://spri.telekom.de/oss/v4/order" xmlns:ns5="http://spri.telekom.de/oss/v4/complex" xmlns:ns6="http://spri.telekom.de/oss/v4/fttx">
                  <control>
                    <zeitstempel>'.date(\DateTimeInterface::RFC3339_EXTENDED).'</zeitstempel>
                    <majorRelease>04</majorRelease>
                    <minorRelease>30</minorRelease>
                    <signaturId>
                      <issuer>CN=AVAible Systems, OU=SPRI RS, O=AVAible Systems, L=Luebeck, ST=Schleswig-Holstein, C=DE</issuer>
                      <serial>1235766675432</serial>
                    </signaturId>
                  </control>
                  <auftrag>
                    <externeAuftragsnummer>EXT.14.2.'.sprintf('%02d', $this->count_Auftrag).'</externeAuftragsnummer>
                    <auftraggeber>
                      <auftraggebernummer>BAD1234666</auftraggebernummer>
                      <leistungsnummer>AVAL'.substr(time(),5, strlen(time())).'</leistungsnummer>
                    </auftraggeber>
                    <geschaeftsfall xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="ns4:DiagnoseGetType">
                      <vertragsnummer></vertragsnummer>
                      <lineId></lineId>
                      <auftragsposition>
                        <parameter>
                          <operation>ONTTStatus</operation>
                        </parameter>
                      </auftragsposition>
                    </geschaeftsfall>
                    <geschaeftsfallArt>Diagnose</geschaeftsfallArt>
                    <aenderungskennzeichen>Standard</aenderungskennzeichen>
                  </auftrag>
                </ns3:annehmenAuftragRequest>
              </soap:Body>
            </soap:Envelope>';

        $this->request_auftrag_providerwechsel_nps2 = '<?xml version="1.0" encoding="UTF-8"?>
            <soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
              <soap:Header>
                <wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" soap:mustUnderstand="1">
                  <wsse:BinarySecurityToken EncodingType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary" ValueType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-x509-token-profile-1.0#X509v3" wsu:Id="X509-e4795298-c4a8-467a-a10a-638ffb58a38a">MIIDmzCCAoOgAwIBAgIEcYUGOTANBgkqhkiG9w0BAQsFADB+MQswCQYDVQQGEwJERTEMMAoGA1UECBMDTlJXMQ8wDQYDVQQHEwZEVUVSRU4xHjAcBgNVBAoTFUVuZ2hvdXNlIFN5c3RlbXMgTHRkLjEQMA4GA1UECxMHU1BSSSBSUzEeMBwGA1UEAxMVRW5naG91c2UgU3lzdGVtcyBMdGQuMB4XDTIxMDYyMzE2MzkzMVoXDTMxMDYyMTE2MzkzMVowfjELMAkGA1UEBhMCREUxDDAKBgNVBAgTA05SVzEPMA0GA1UEBxMGRFVFUkVOMR4wHAYDVQQKExVFbmdob3VzZSBTeXN0ZW1zIEx0ZC4xEDAOBgNVBAsTB1NQUkkgUlMxHjAcBgNVBAMTFUVuZ2hvdXNlIFN5c3RlbXMgTHRkLjCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBAIpv2i4uPVsJAwMV+5i/JOCsJPGcU8hEaezvS4kdJcELwLCHWiD6DThKb6ou23lk64nkA7bztpsuRQ9mOl8d89RtN/azKpZ13pMVkC7hXHyTr3kBGTPyYEQJq2pPwYJwB2yq/s8eZtPnw/4N2aa+vxmdglTpLy/RFb9wS0sY76ql8PhyIqmJ/voz02AdAdJPgiVypteKHL9qNvBRsP6M58WiS4GCoS4RoaG3SSqhpGzwM3MysmRuttQSN8Leh6ndDOpx2H7CVEEA/oiXgoYm77pIOOHq2p+8r+cMnjk5Q0WFn0vfs4jeWX5UCZjx/2QGQjJa4R1Se1UZkCSoygZt8P8CAwEAAaMhMB8wHQYDVR0OBBYEFOZgtmMVgA9GI9+vM5PWesJ1UUB+MA0GCSqGSIb3DQEBCwUAA4IBAQAbR649eRec/TKCg0AG+m8WO0svI/IH3oUAIaf+TN5CT23U4JJZVoKY8DlO9zlVFaVK7jKjJAdbOqpHIUPbSMwr0TX6DqMuVgGSzs3Zham4Uflv2kslbwDBhiadKbf2dSqkVHt2oVLodzH7lFQ17Y1KWvl8Ss68gcaH6XqXOB54/iCms8zp+SwYLoykNcF0e2WR5BrnQHjQ1wdJeiVnEmFcMXNN9tdhRMt6zIUWDbnwkFQM9ZQfQf+JAj2OmtrtjLqy9fktj2TZByQ7D8fZUFPRSFktb1tA5zM7AoHcqeeDMA/HI/XOC9B7HE44j0xIzJ2OSmLh8l2fgYcxTyPWhKTd</wsse:BinarySecurityToken>
                  <ds:Signature xmlns:ds="http://www.w3.org/2000/09/xmldsig#" Id="SIG-e1a666ac-0d63-453e-a54e-e74d9b013cc3">
                    <ds:SignedInfo>
                      <ds:CanonicalizationMethod Algorithm="http://www.w3.org/2001/10/xml-exc-c14n#">
                        <ec:InclusiveNamespaces xmlns:ec="http://www.w3.org/2001/10/xml-exc-c14n#" PrefixList="soap"/>
                      </ds:CanonicalizationMethod>
                      <ds:SignatureMethod Algorithm="http://www.w3.org/2000/09/xmldsig#rsa-sha1"/>
                      <ds:Reference URI="#id-e0b059b4-ae41-4af8-b4ee-8a842238f53c">
                        <ds:Transforms>
                          <ds:Transform Algorithm="http://www.w3.org/2001/10/xml-exc-c14n#"/>
                        </ds:Transforms>
                        <ds:DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1"/>
                        <ds:DigestValue>FFvsa6AmFaMM79xs9DmvycAq2Iw=</ds:DigestValue>
                      </ds:Reference>
                    </ds:SignedInfo>
                    <ds:SignatureValue>IJtjurkOfMn3Z8GCABXzNjjXwjtt+A5WtzHkUxjCCP+iKrQzwtCWl10GnXUdshY0afwA07puiBKoBxD0azIL7mjTyQ0Z5o7uXWTg9jo1H/Y9DGRa2iuFhtwnBP1PMlN6zpC1XK4z4K3SNyK+uAUfVk6aTJH7+lNB6gHl3njgrcFeDext6h+XcTT5Q6QawVr5GiZYv7PThBxIbGoH8yDsxn8VRZ8vMUIiRF/+wNKKOLZRPpiyJdC/4S6uyAR68frqpdoqH/T4iLcRiM3eqzN3J4JHZaRPQqU5OFRIBS6C+LspHSTsnQp2gUHvZW5UexrulOJk8waQWAuC5gg+hofTpg==</ds:SignatureValue>
                    <ds:KeyInfo Id="KI-dc93bdc4-dddf-4b97-89a1-088deb06cdd2">
                      <wsse:SecurityTokenReference wsu:Id="STR-57f15772-5394-4e45-9c80-d380249bc663">
                        <wsse:Reference URI="#X509-e4795298-c4a8-467a-a10a-638ffb58a38a" ValueType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-x509-token-profile-1.0#X509v3"/>
                      </wsse:SecurityTokenReference>
                    </ds:KeyInfo>
                  </ds:Signature>
                </wsse:Security>
              </soap:Header>
              <soap:Body xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" wsu:Id="id-e0b059b4-ae41-4af8-b4ee-8a842238f53c">
                <ns3:annehmenAuftragRequest xmlns:ns3="http://spri.telekom.de/oss/v4/envelope" xmlns:ns2="http://spri.telekom.de/oss/v4/message" xmlns:ns4="http://spri.telekom.de/oss/v4/order" xmlns:ns5="http://spri.telekom.de/oss/v4/complex" xmlns:ns6="http://spri.telekom.de/oss/v4/fttx">
                  <control>
                    <zeitstempel>'.date(\DateTimeInterface::RFC3339_EXTENDED).'</zeitstempel>
                    <majorRelease>04</majorRelease>
                    <minorRelease>30</minorRelease>
                    <signaturId>
                      <issuer>CN=NPS Webservice, OU=SPRI RS, O=ACME Providing Systems, L=Luebeck, ST=Schleswig-Holstein, C=DE</issuer>
                      <serial>6662345775432</serial>
                    </signaturId>
                  </control>
                  <auftrag></auftrag>
                </ns3:annehmenAuftragRequest>
              </soap:Body>
            </soap:Envelope>';

        /**
         * Request Auftrag-Envolope Provider
         */

        $this->request_auftrag_ava = '<?xml version="1.0" encoding="UTF-8"?>
            <soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
              <soap:Header>
                <wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" soap:mustUnderstand="1">
                  <wsse:BinarySecurityToken EncodingType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary" ValueType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-x509-token-profile-1.0#X509v3" wsu:Id="X509-e4795298-c4a8-467a-a10a-638ffb58a38a">MIIDmzCCAoOgAwIBAgIEcYUGOTANBgkqhkiG9w0BAQsFADB+MQswCQYDVQQGEwJERTEMMAoGA1UECBMDTlJXMQ8wDQYDVQQHEwZEVUVSRU4xHjAcBgNVBAoTFUVuZ2hvdXNlIFN5c3RlbXMgTHRkLjEQMA4GA1UECxMHU1BSSSBSUzEeMBwGA1UEAxMVRW5naG91c2UgU3lzdGVtcyBMdGQuMB4XDTIxMDYyMzE2MzkzMVoXDTMxMDYyMTE2MzkzMVowfjELMAkGA1UEBhMCREUxDDAKBgNVBAgTA05SVzEPMA0GA1UEBxMGRFVFUkVOMR4wHAYDVQQKExVFbmdob3VzZSBTeXN0ZW1zIEx0ZC4xEDAOBgNVBAsTB1NQUkkgUlMxHjAcBgNVBAMTFUVuZ2hvdXNlIFN5c3RlbXMgTHRkLjCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBAIpv2i4uPVsJAwMV+5i/JOCsJPGcU8hEaezvS4kdJcELwLCHWiD6DThKb6ou23lk64nkA7bztpsuRQ9mOl8d89RtN/azKpZ13pMVkC7hXHyTr3kBGTPyYEQJq2pPwYJwB2yq/s8eZtPnw/4N2aa+vxmdglTpLy/RFb9wS0sY76ql8PhyIqmJ/voz02AdAdJPgiVypteKHL9qNvBRsP6M58WiS4GCoS4RoaG3SSqhpGzwM3MysmRuttQSN8Leh6ndDOpx2H7CVEEA/oiXgoYm77pIOOHq2p+8r+cMnjk5Q0WFn0vfs4jeWX5UCZjx/2QGQjJa4R1Se1UZkCSoygZt8P8CAwEAAaMhMB8wHQYDVR0OBBYEFOZgtmMVgA9GI9+vM5PWesJ1UUB+MA0GCSqGSIb3DQEBCwUAA4IBAQAbR649eRec/TKCg0AG+m8WO0svI/IH3oUAIaf+TN5CT23U4JJZVoKY8DlO9zlVFaVK7jKjJAdbOqpHIUPbSMwr0TX6DqMuVgGSzs3Zham4Uflv2kslbwDBhiadKbf2dSqkVHt2oVLodzH7lFQ17Y1KWvl8Ss68gcaH6XqXOB54/iCms8zp+SwYLoykNcF0e2WR5BrnQHjQ1wdJeiVnEmFcMXNN9tdhRMt6zIUWDbnwkFQM9ZQfQf+JAj2OmtrtjLqy9fktj2TZByQ7D8fZUFPRSFktb1tA5zM7AoHcqeeDMA/HI/XOC9B7HE44j0xIzJ2OSmLh8l2fgYcxTyPWhKTd</wsse:BinarySecurityToken>
                  <ds:Signature xmlns:ds="http://www.w3.org/2000/09/xmldsig#" Id="SIG-e1a666ac-0d63-453e-a54e-e74d9b013cc3">
                    <ds:SignedInfo>
                      <ds:CanonicalizationMethod Algorithm="http://www.w3.org/2001/10/xml-exc-c14n#">
                        <ec:InclusiveNamespaces xmlns:ec="http://www.w3.org/2001/10/xml-exc-c14n#" PrefixList="soap"/>
                      </ds:CanonicalizationMethod>
                      <ds:SignatureMethod Algorithm="http://www.w3.org/2000/09/xmldsig#rsa-sha1"/>
                      <ds:Reference URI="#id-e0b059b4-ae41-4af8-b4ee-8a842238f53c">
                        <ds:Transforms>
                          <ds:Transform Algorithm="http://www.w3.org/2001/10/xml-exc-c14n#"/>
                        </ds:Transforms>
                        <ds:DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1"/>
                        <ds:DigestValue>FFvsa6AmFaMM79xs9DmvycAq2Iw=</ds:DigestValue>
                      </ds:Reference>
                    </ds:SignedInfo>
                    <ds:SignatureValue>IJtjurkOfMn3Z8GCABXzNjjXwjtt+A5WtzHkUxjCCP+iKrQzwtCWl10GnXUdshY0afwA07puiBKoBxD0azIL7mjTyQ0Z5o7uXWTg9jo1H/Y9DGRa2iuFhtwnBP1PMlN6zpC1XK4z4K3SNyK+uAUfVk6aTJH7+lNB6gHl3njgrcFeDext6h+XcTT5Q6QawVr5GiZYv7PThBxIbGoH8yDsxn8VRZ8vMUIiRF/+wNKKOLZRPpiyJdC/4S6uyAR68frqpdoqH/T4iLcRiM3eqzN3J4JHZaRPQqU5OFRIBS6C+LspHSTsnQp2gUHvZW5UexrulOJk8waQWAuC5gg+hofTpg==</ds:SignatureValue>
                    <ds:KeyInfo Id="KI-dc93bdc4-dddf-4b97-89a1-088deb06cdd2">
                      <wsse:SecurityTokenReference wsu:Id="STR-57f15772-5394-4e45-9c80-d380249bc663">
                        <wsse:Reference URI="#X509-e4795298-c4a8-467a-a10a-638ffb58a38a" ValueType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-x509-token-profile-1.0#X509v3"/>
                      </wsse:SecurityTokenReference>
                    </ds:KeyInfo>
                  </ds:Signature>
                </wsse:Security>
              </soap:Header>
              <soap:Body xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" wsu:Id="id-e0b059b4-ae41-4af8-b4ee-8a842238f53c">
                <ns3:annehmenAuftragRequest xmlns:ns3="http://spri.telekom.de/oss/v4/envelope" xmlns:ns2="http://spri.telekom.de/oss/v4/message" xmlns:ns4="http://spri.telekom.de/oss/v4/order" xmlns:ns5="http://spri.telekom.de/oss/v4/complex" xmlns:ns6="http://spri.telekom.de/oss/v4/fttx">
                  <control>
                    <zeitstempel>'.date(\DateTimeInterface::RFC3339_EXTENDED).'</zeitstempel>
                    <majorRelease>04</majorRelease>
                    <minorRelease>30</minorRelease>
                    <signaturId>
                      <issuer>CN=AVAible S/PRI Simulator, OU=SPRI RS, O=AVAible OA Systems, L=Luebeck, ST=Schleswig-Holstein, C=DE</issuer>
                      <serial>1235766675432</serial>
                    </signaturId>
                  </control>
                  <auftrag></auftrag>
                </ns3:annehmenAuftragRequest>
              </soap:Body>
            </soap:Envelope>';

        $this->request_auftrag_nps = '<?xml version="1.0" encoding="UTF-8"?>
            <soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
              <soap:Header>
                <wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" soap:mustUnderstand="1">
                  <wsse:BinarySecurityToken EncodingType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary" ValueType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-x509-token-profile-1.0#X509v3" wsu:Id="X509-e4795298-c4a8-467a-a10a-638ffb58a38a">MIIDmzCCAoOgAwIBAgIEcYUGOTANBgkqhkiG9w0BAQsFADB+MQswCQYDVQQGEwJERTEMMAoGA1UECBMDTlJXMQ8wDQYDVQQHEwZEVUVSRU4xHjAcBgNVBAoTFUVuZ2hvdXNlIFN5c3RlbXMgTHRkLjEQMA4GA1UECxMHU1BSSSBSUzEeMBwGA1UEAxMVRW5naG91c2UgU3lzdGVtcyBMdGQuMB4XDTIxMDYyMzE2MzkzMVoXDTMxMDYyMTE2MzkzMVowfjELMAkGA1UEBhMCREUxDDAKBgNVBAgTA05SVzEPMA0GA1UEBxMGRFVFUkVOMR4wHAYDVQQKExVFbmdob3VzZSBTeXN0ZW1zIEx0ZC4xEDAOBgNVBAsTB1NQUkkgUlMxHjAcBgNVBAMTFUVuZ2hvdXNlIFN5c3RlbXMgTHRkLjCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBAIpv2i4uPVsJAwMV+5i/JOCsJPGcU8hEaezvS4kdJcELwLCHWiD6DThKb6ou23lk64nkA7bztpsuRQ9mOl8d89RtN/azKpZ13pMVkC7hXHyTr3kBGTPyYEQJq2pPwYJwB2yq/s8eZtPnw/4N2aa+vxmdglTpLy/RFb9wS0sY76ql8PhyIqmJ/voz02AdAdJPgiVypteKHL9qNvBRsP6M58WiS4GCoS4RoaG3SSqhpGzwM3MysmRuttQSN8Leh6ndDOpx2H7CVEEA/oiXgoYm77pIOOHq2p+8r+cMnjk5Q0WFn0vfs4jeWX5UCZjx/2QGQjJa4R1Se1UZkCSoygZt8P8CAwEAAaMhMB8wHQYDVR0OBBYEFOZgtmMVgA9GI9+vM5PWesJ1UUB+MA0GCSqGSIb3DQEBCwUAA4IBAQAbR649eRec/TKCg0AG+m8WO0svI/IH3oUAIaf+TN5CT23U4JJZVoKY8DlO9zlVFaVK7jKjJAdbOqpHIUPbSMwr0TX6DqMuVgGSzs3Zham4Uflv2kslbwDBhiadKbf2dSqkVHt2oVLodzH7lFQ17Y1KWvl8Ss68gcaH6XqXOB54/iCms8zp+SwYLoykNcF0e2WR5BrnQHjQ1wdJeiVnEmFcMXNN9tdhRMt6zIUWDbnwkFQM9ZQfQf+JAj2OmtrtjLqy9fktj2TZByQ7D8fZUFPRSFktb1tA5zM7AoHcqeeDMA/HI/XOC9B7HE44j0xIzJ2OSmLh8l2fgYcxTyPWhKTd</wsse:BinarySecurityToken>
                  <ds:Signature xmlns:ds="http://www.w3.org/2000/09/xmldsig#" Id="SIG-e1a666ac-0d63-453e-a54e-e74d9b013cc3">
                    <ds:SignedInfo>
                      <ds:CanonicalizationMethod Algorithm="http://www.w3.org/2001/10/xml-exc-c14n#">
                        <ec:InclusiveNamespaces xmlns:ec="http://www.w3.org/2001/10/xml-exc-c14n#" PrefixList="soap"/>
                      </ds:CanonicalizationMethod>
                      <ds:SignatureMethod Algorithm="http://www.w3.org/2000/09/xmldsig#rsa-sha1"/>
                      <ds:Reference URI="#id-e0b059b4-ae41-4af8-b4ee-8a842238f53c">
                        <ds:Transforms>
                          <ds:Transform Algorithm="http://www.w3.org/2001/10/xml-exc-c14n#"/>
                        </ds:Transforms>
                        <ds:DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1"/>
                        <ds:DigestValue>FFvsa6AmFaMM79xs9DmvycAq2Iw=</ds:DigestValue>
                      </ds:Reference>
                    </ds:SignedInfo>
                    <ds:SignatureValue>IJtjurkOfMn3Z8GCABXzNjjXwjtt+A5WtzHkUxjCCP+iKrQzwtCWl10GnXUdshY0afwA07puiBKoBxD0azIL7mjTyQ0Z5o7uXWTg9jo1H/Y9DGRa2iuFhtwnBP1PMlN6zpC1XK4z4K3SNyK+uAUfVk6aTJH7+lNB6gHl3njgrcFeDext6h+XcTT5Q6QawVr5GiZYv7PThBxIbGoH8yDsxn8VRZ8vMUIiRF/+wNKKOLZRPpiyJdC/4S6uyAR68frqpdoqH/T4iLcRiM3eqzN3J4JHZaRPQqU5OFRIBS6C+LspHSTsnQp2gUHvZW5UexrulOJk8waQWAuC5gg+hofTpg==</ds:SignatureValue>
                    <ds:KeyInfo Id="KI-dc93bdc4-dddf-4b97-89a1-088deb06cdd2">
                      <wsse:SecurityTokenReference wsu:Id="STR-57f15772-5394-4e45-9c80-d380249bc663">
                        <wsse:Reference URI="#X509-e4795298-c4a8-467a-a10a-638ffb58a38a" ValueType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-x509-token-profile-1.0#X509v3"/>
                      </wsse:SecurityTokenReference>
                    </ds:KeyInfo>
                  </ds:Signature>
                </wsse:Security>
              </soap:Header>
              <soap:Body xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" wsu:Id="id-e0b059b4-ae41-4af8-b4ee-8a842238f53c">
                <ns3:annehmenAuftragRequest xmlns:ns3="http://spri.telekom.de/oss/v4/envelope" xmlns:ns2="http://spri.telekom.de/oss/v4/message" xmlns:ns4="http://spri.telekom.de/oss/v4/order" xmlns:ns5="http://spri.telekom.de/oss/v4/complex" xmlns:ns6="http://spri.telekom.de/oss/v4/fttx">
                  <control>
                    <zeitstempel>'.date(\DateTimeInterface::RFC3339_EXTENDED).'</zeitstempel>
                    <majorRelease>04</majorRelease>
                    <minorRelease>30</minorRelease>
                    <signaturId>
                      <issuer>CN=NPS Webservice, OU=SPRI RS, O=ACME Providing Systems, L=Luebeck, ST=Schleswig-Holstein, C=DE</issuer>
                      <serial>6662345775432</serial>
                    </signaturId>
                  </control>
                  <auftrag></auftrag>
                </ns3:annehmenAuftragRequest>
              </soap:Body>
            </soap:Envelope>';

        $this->request_auftrag_yolo = '<?xml version="1.0" encoding="UTF-8"?>
            <soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
              <soap:Header>
                <wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" soap:mustUnderstand="1">
                  <wsse:BinarySecurityToken EncodingType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary" ValueType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-x509-token-profile-1.0#X509v3" wsu:Id="X509-e4795298-c4a8-467a-a10a-638ffb58a38a">MIIDmzCCAoOgAwIBAgIEcYUGOTANBgkqhkiG9w0BAQsFADB+MQswCQYDVQQGEwJERTEMMAoGA1UECBMDTlJXMQ8wDQYDVQQHEwZEVUVSRU4xHjAcBgNVBAoTFUVuZ2hvdXNlIFN5c3RlbXMgTHRkLjEQMA4GA1UECxMHU1BSSSBSUzEeMBwGA1UEAxMVRW5naG91c2UgU3lzdGVtcyBMdGQuMB4XDTIxMDYyMzE2MzkzMVoXDTMxMDYyMTE2MzkzMVowfjELMAkGA1UEBhMCREUxDDAKBgNVBAgTA05SVzEPMA0GA1UEBxMGRFVFUkVOMR4wHAYDVQQKExVFbmdob3VzZSBTeXN0ZW1zIEx0ZC4xEDAOBgNVBAsTB1NQUkkgUlMxHjAcBgNVBAMTFUVuZ2hvdXNlIFN5c3RlbXMgTHRkLjCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBAIpv2i4uPVsJAwMV+5i/JOCsJPGcU8hEaezvS4kdJcELwLCHWiD6DThKb6ou23lk64nkA7bztpsuRQ9mOl8d89RtN/azKpZ13pMVkC7hXHyTr3kBGTPyYEQJq2pPwYJwB2yq/s8eZtPnw/4N2aa+vxmdglTpLy/RFb9wS0sY76ql8PhyIqmJ/voz02AdAdJPgiVypteKHL9qNvBRsP6M58WiS4GCoS4RoaG3SSqhpGzwM3MysmRuttQSN8Leh6ndDOpx2H7CVEEA/oiXgoYm77pIOOHq2p+8r+cMnjk5Q0WFn0vfs4jeWX5UCZjx/2QGQjJa4R1Se1UZkCSoygZt8P8CAwEAAaMhMB8wHQYDVR0OBBYEFOZgtmMVgA9GI9+vM5PWesJ1UUB+MA0GCSqGSIb3DQEBCwUAA4IBAQAbR649eRec/TKCg0AG+m8WO0svI/IH3oUAIaf+TN5CT23U4JJZVoKY8DlO9zlVFaVK7jKjJAdbOqpHIUPbSMwr0TX6DqMuVgGSzs3Zham4Uflv2kslbwDBhiadKbf2dSqkVHt2oVLodzH7lFQ17Y1KWvl8Ss68gcaH6XqXOB54/iCms8zp+SwYLoykNcF0e2WR5BrnQHjQ1wdJeiVnEmFcMXNN9tdhRMt6zIUWDbnwkFQM9ZQfQf+JAj2OmtrtjLqy9fktj2TZByQ7D8fZUFPRSFktb1tA5zM7AoHcqeeDMA/HI/XOC9B7HE44j0xIzJ2OSmLh8l2fgYcxTyPWhKTd</wsse:BinarySecurityToken>
                  <ds:Signature xmlns:ds="http://www.w3.org/2000/09/xmldsig#" Id="SIG-e1a666ac-0d63-453e-a54e-e74d9b013cc3">
                    <ds:SignedInfo>
                      <ds:CanonicalizationMethod Algorithm="http://www.w3.org/2001/10/xml-exc-c14n#">
                        <ec:InclusiveNamespaces xmlns:ec="http://www.w3.org/2001/10/xml-exc-c14n#" PrefixList="soap"/>
                      </ds:CanonicalizationMethod>
                      <ds:SignatureMethod Algorithm="http://www.w3.org/2000/09/xmldsig#rsa-sha1"/>
                      <ds:Reference URI="#id-e0b059b4-ae41-4af8-b4ee-8a842238f53c">
                        <ds:Transforms>
                          <ds:Transform Algorithm="http://www.w3.org/2001/10/xml-exc-c14n#"/>
                        </ds:Transforms>
                        <ds:DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1"/>
                        <ds:DigestValue>FFvsa6AmFaMM79xs9DmvycAq2Iw=</ds:DigestValue>
                      </ds:Reference>
                    </ds:SignedInfo>
                    <ds:SignatureValue>IJtjurkOfMn3Z8GCABXzNjjXwjtt+A5WtzHkUxjCCP+iKrQzwtCWl10GnXUdshY0afwA07puiBKoBxD0azIL7mjTyQ0Z5o7uXWTg9jo1H/Y9DGRa2iuFhtwnBP1PMlN6zpC1XK4z4K3SNyK+uAUfVk6aTJH7+lNB6gHl3njgrcFeDext6h+XcTT5Q6QawVr5GiZYv7PThBxIbGoH8yDsxn8VRZ8vMUIiRF/+wNKKOLZRPpiyJdC/4S6uyAR68frqpdoqH/T4iLcRiM3eqzN3J4JHZaRPQqU5OFRIBS6C+LspHSTsnQp2gUHvZW5UexrulOJk8waQWAuC5gg+hofTpg==</ds:SignatureValue>
                    <ds:KeyInfo Id="KI-dc93bdc4-dddf-4b97-89a1-088deb06cdd2">
                      <wsse:SecurityTokenReference wsu:Id="STR-57f15772-5394-4e45-9c80-d380249bc663">
                        <wsse:Reference URI="#X509-e4795298-c4a8-467a-a10a-638ffb58a38a" ValueType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-x509-token-profile-1.0#X509v3"/>
                      </wsse:SecurityTokenReference>
                    </ds:KeyInfo>
                  </ds:Signature>
                </wsse:Security>
              </soap:Header>
              <soap:Body xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" wsu:Id="id-e0b059b4-ae41-4af8-b4ee-8a842238f53c">
                <ns3:annehmenAuftragRequest xmlns:ns3="http://spri.telekom.de/oss/v4/envelope" xmlns:ns2="http://spri.telekom.de/oss/v4/message" xmlns:ns4="http://spri.telekom.de/oss/v4/order" xmlns:ns5="http://spri.telekom.de/oss/v4/complex" xmlns:ns6="http://spri.telekom.de/oss/v4/fttx">
                  <control>
                    <zeitstempel>'.date(\DateTimeInterface::RFC3339_EXTENDED).'</zeitstempel>
                    <majorRelease>04</majorRelease>
                    <minorRelease>30</minorRelease>
                    <signaturId>
                      <issuer>CN=YOLO S/PRI Simulator, OU=SPRI RS, O=AVAible OA Systems, L=Luebeck, ST=Schleswig-Holstein, C=DE</issuer>
                      <serial>2345766675432</serial>
                    </signaturId>
                  </control>
                  <auftrag></auftrag>
                </ns3:annehmenAuftragRequest>
              </soap:Body>
            </soap:Envelope>';

        /**
         * Request Auftrag(-styp) NEU,KUE-AG
         */
        $this->request_bereitstellung_neu ='
        <auftrag>
          <externeAuftragsnummer></externeAuftragsnummer>
          <auftraggeber>
            <auftraggebernummer></auftraggebernummer>
            <leistungsnummer>'.substr(time(),2, strlen(time())).'</leistungsnummer>
          </auftraggeber>
          <geschaeftsfall xmlns:ns4="http://spri.telekom.de/oss/v4/order" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="ns4:BereitstellungType">
            <ansprechpartner>
              <auftragsmanagement>
                <anrede>2</anrede>
                <vorname>Betty M.</vorname>
                <nachname>Bocanegra</nachname>
                <telefonnummer>04515559222</telefonnummer>
                <emailadresse>auftrag@provider.net</emailadresse>
              </auftragsmanagement>
            </ansprechpartner>
            <termine>
              <auftraggeberWunschtermin></auftraggeberWunschtermin>
            </termine>
            <auftragsposition>
              <produkt xmlns:ns5="http://spri.telekom.de/oss/v4/fttx" xsi:type="ns5:ProduktFTTXType">
                <bezeichner>FTTH L2 XGSPON 300 150</bezeichner>
              </produkt>
              <geschaeftsfallProdukt xmlns:ns5="http://spri.telekom.de/oss/v4/fttx" xsi:type="ns5:FTTXBereitstellungType">
                <standortA>
                  <person>
                    <anrede>1</anrede>
                    <vorname>Gabriele</vorname>
                    <nachname>Nussbaum</nachname>
                    <telefonnummer>01590 1890067</telefonnummer>
                  </person>
                  <strasse>
                    <strassenname>Sterntalerweg</strassenname>
                    <hausnummer>1</hausnummer>
                  </strasse>
                  <postleitzahl>23560</postleitzahl>
                  <ort>
                    <ortsname>Lübeck</ortsname>
                  </ort>
                </standortA>
                <vormieter>
                <person>
                  <vorname>Paula</vorname>
                  <nachname>Mietnomadin</nachname>
                </person>
                <homeId>
                  <homeIdNummer></homeIdNummer>
                </homeId>
              </vormieter>
              <technologie>FTTH</technologie>
              </geschaeftsfallProdukt>
            </auftragsposition>
          </geschaeftsfall>
          <geschaeftsfallArt>Bereitstellung</geschaeftsfallArt>
          <aenderungskennzeichen></aenderungskennzeichen>
        </auftrag>';

        $this->request_bereitstellung_konnektivitaet =
            '<auftrag>
            <externeAuftragsnummer></externeAuftragsnummer>
            <auftraggeber>
              <auftraggebernummer></auftraggebernummer>
              <leistungsnummer>'.substr(time(),2, strlen(time())).'</leistungsnummer>
            </auftraggeber>
          <geschaeftsfall xsi:type="ns4:BereitstellungType" xmlns:ns4="http://spri.telekom.de/oss/v4/order" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
               <ansprechpartner>
                  <auftragsmanagement>
                     <anrede>1</anrede>
                     <vorname>Sven</vorname>
                     <nachname>Rauschning</nachname>
                     <telefonnummer>04619099223</telefonnummer>
                     <emailadresse>ooc1und1@1und1.net</emailadresse>
                  </auftragsmanagement>
               </ansprechpartner>
               <termine>
                  <auftraggeberWunschtermin></auftraggeberWunschtermin>
               </termine>
               <auftragsposition>
                  <produkt xsi:type="ns5:ProduktFTTXType" xmlns:ns5="http://spri.telekom.de/oss/v4/fttx">
                     <bezeichner>FTTH L2 XGSPON 300 150</bezeichner>
                  </produkt>
                  <geschaeftsfallProdukt xsi:type="ns5:FTTXBereitstellungType" xmlns:ns5="http://spri.telekom.de/oss/v4/fttx">
                     <standortA>
                        <person>
                           <anrede>1</anrede>
                           <vorname>Ka-Ath</vorname>
                           <nachname>Kruge</nachname>
                           <telefonnummer>01525 3660070</telefonnummer>
                        </person>
                        <strasse>
                           <strassenname>Nordmeerstraße</strassenname>
                           <hausnummer>58</hausnummer>
                        </strasse>
                        <postleitzahl>23570</postleitzahl>
                        <ort>
                           <ortsname>Lübeck</ortsname>
                        </ort>
                     </standortA>
                     <montageleistung>
                        <ansprechpartner>
                           <anrede>1</anrede>
                           <vorname>Marmaduc</vorname>
                           <nachname>Chubb-Baggins</nachname>
                           <telefonnummer>01525 3660070</telefonnummer>
                           <emailadresse>m.chubb.baggins@live.de</emailadresse>
                        </ansprechpartner>
                     </montageleistung>
                  </geschaeftsfallProdukt>
                  <position>
                     <produkt xsi:type="ns5:ProduktFTTXType" xmlns:ns5="http://spri.telekom.de/oss/v4/fttx">
                        <bezeichner>Konnektivität</bezeichner>
                     </produkt>
                  </position>
                  <position>
                     <produkt xsi:type="ns5:ProduktFTTXType" xmlns:ns5="http://spri.telekom.de/oss/v4/fttx">
                        <bezeichner>EtVerhaeltnis#Mieter#</bezeichner>
                     </produkt>
                  </position>
                  <position>
                     <produkt xsi:type="ns5:ProduktFTTXType" xmlns:ns5="http://spri.telekom.de/oss/v4/fttx">
                        <bezeichner>EtFestnetz#01525 3660070#</bezeichner>
                     </produkt>
                  </position>
                  <position>
                     <produkt xsi:type="ns5:ProduktFTTXType" xmlns:ns5="http://spri.telekom.de/oss/v4/fttx">
                        <bezeichner>EtMail#m.chubb.baggins@live.de#</bezeichner>
                     </produkt>
                  </position>
                  <position>
                     <produkt xsi:type="ns5:ProduktFTTXType" xmlns:ns5="http://spri.telekom.de/oss/v4/fttx">
                        <bezeichner>EtArt#Eigentuemer#</bezeichner>
                     </produkt>
                  </position>
               </auftragsposition>
            </geschaeftsfall>
            <geschaeftsfallArt>Bereitstellung</geschaeftsfallArt>
            <aenderungskennzeichen></aenderungskennzeichen>
        </auftrag>';

        $this->request_kuendigung_ag =
        '<auftrag>
        <externeAuftragsnummer></externeAuftragsnummer>
            <auftraggeber>
              <auftraggebernummer></auftraggebernummer>
              <leistungsnummer>'.substr(time(),2, strlen(time())).'</leistungsnummer>
            </auftraggeber>
        <geschaeftsfall xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="ns4:KuendigungType">
          <vertragsnummer></vertragsnummer>
          <lineId></lineId>
          <ansprechpartner>
            <auftragsmanagement>
              <anrede>2</anrede>
              <vorname>Brigitte</vorname>
              <nachname>Z_9_1_01_LE</nachname>
              <telefonnummer>+49 89 36089186</telefonnummer>
              <mobilfunknummer>+49 170 2222222</mobilfunknummer>
              <faxnummer>089 36089188</faxnummer>
              <emailadresse>auftrag@mail.de</emailadresse>
            </auftragsmanagement>
            <ansprechpartner>
              <anrede>1</anrede>
              <vorname>Peter</vorname>
              <nachname>Klein</nachname>
              <telefonnummer>089 36089186</telefonnummer>
              <mobilfunknummer>0170 2222222</mobilfunknummer>
              <faxnummer>+49 89 36089188</faxnummer>
              <emailadresse>technik@mail.de</emailadresse>
              <rolle>Technik</rolle>
            </ansprechpartner>
          </ansprechpartner>
          <termine>
            <auftraggeberWunschtermin></auftraggeberWunschtermin>
          </termine>
        </geschaeftsfall>
        <geschaeftsfallArt>Kuendigung</geschaeftsfallArt>
        <aenderungskennzeichen></aenderungskennzeichen>
      </auftrag>';

        $this->request_entstoerung =
        '<auftrag>
        <externeAuftragsnummer></externeAuftragsnummer>
            <auftraggeber>
              <auftraggebernummer></auftraggebernummer>
              <leistungsnummer>'.substr(time(),2, strlen(time())).'</leistungsnummer>
            </auftraggeber>
        <geschaeftsfall xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="ns4:EntstoerungType">
          <vertragsnummer></vertragsnummer>
          <lineId></lineId>
          <ansprechpartner>
            <auftragsmanagement>
              <anrede>2</anrede>
              <vorname>Esta</vorname>
              <nachname>Auftragsmanagement</nachname>
              <telefonnummer>0451666999</telefonnummer>
              <emailadresse>entstoerung@mail.de</emailadresse>
            </auftragsmanagement>
          </ansprechpartner>
          <kontaktEndkunde>
            <anrede>9</anrede>
            <vorname>E.</vorname>
            <nachname>zu Entstörender</nachname>
            <mobilfunknummer>017915118418</mobilfunknummer>
            <emailadresse>endkunde@entstoeren.net</emailadresse>
            <kontaktaufnahmeErwuenscht>true</kontaktaufnahmeErwuenscht>
          </kontaktEndkunde>
          <termine>
            <auftraggeberWunschtermin></auftraggeberWunschtermin>
          </termine>
          <stoerungsNrAuftraggeber>EST'.substr(time(),2, strlen(time())).'</stoerungsNrAuftraggeber>
          <vorpruefungErfolgt>true</vorpruefungErfolgt>
          <stoerungsbeschreibung>Funktionierte der Anschluss bereits zu einem früheren Zeitpunkt? Y Funktioniert die Internetverbindung? N Wenn vorhanden physikalische Parameter: Down-/Upload Ist Leuchtanzeige für Synchronität dauernd grün? N Angeschlossene Kunden-CPE: Fritzbox  Freitext:Die Box ist nicht mehr online. Verkabelung wurde überprüft...  Mal sehen wie weit die Entstörung durchläuft  Störungsart: Komplettausfall Beschreibung der Störung: Keine Synchronisation</stoerungsbeschreibung>
          <expressentstoerung>false</expressentstoerung>
        </geschaeftsfall>
        <geschaeftsfallArt>Entstoerung</geschaeftsfallArt>
        <aenderungskennzeichen>Standard</aenderungskennzeichen>
      </auftrag>';

        $bestellung_dritter = '<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Header>
    <wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" soap:mustUnderstand="1">
      <wsse:BinarySecurityToken EncodingType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary" ValueType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-x509-token-profile-1.0#X509v3" wsu:Id="X509-d8ba59ff-57f6-48ab-9d94-9222aad76ad4">MIIDmzCCAoOgAwIBAgIEcYUGOTANBgkqhkiG9w0BAQsFADB+MQswCQYDVQQGEwJERTEMMAoGA1UECBMDTlJXMQ8wDQYDVQQHEwZEVUVSRU4xHjAcBgNVBAoTFUVuZ2hvdXNlIFN5c3RlbXMgTHRkLjEQMA4GA1UECxMHU1BSSSBSUzEeMBwGA1UEAxMVRW5naG91c2UgU3lzdGVtcyBMdGQuMB4XDTIxMDYyMzE2MzkzMVoXDTMxMDYyMTE2MzkzMVowfjELMAkGA1UEBhMCREUxDDAKBgNVBAgTA05SVzEPMA0GA1UEBxMGRFVFUkVOMR4wHAYDVQQKExVFbmdob3VzZSBTeXN0ZW1zIEx0ZC4xEDAOBgNVBAsTB1NQUkkgUlMxHjAcBgNVBAMTFUVuZ2hvdXNlIFN5c3RlbXMgTHRkLjCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBAIpv2i4uPVsJAwMV+5i/JOCsJPGcU8hEaezvS4kdJcELwLCHWiD6DThKb6ou23lk64nkA7bztpsuRQ9mOl8d89RtN/azKpZ13pMVkC7hXHyTr3kBGTPyYEQJq2pPwYJwB2yq/s8eZtPnw/4N2aa+vxmdglTpLy/RFb9wS0sY76ql8PhyIqmJ/voz02AdAdJPgiVypteKHL9qNvBRsP6M58WiS4GCoS4RoaG3SSqhpGzwM3MysmRuttQSN8Leh6ndDOpx2H7CVEEA/oiXgoYm77pIOOHq2p+8r+cMnjk5Q0WFn0vfs4jeWX5UCZjx/2QGQjJa4R1Se1UZkCSoygZt8P8CAwEAAaMhMB8wHQYDVR0OBBYEFOZgtmMVgA9GI9+vM5PWesJ1UUB+MA0GCSqGSIb3DQEBCwUAA4IBAQAbR649eRec/TKCg0AG+m8WO0svI/IH3oUAIaf+TN5CT23U4JJZVoKY8DlO9zlVFaVK7jKjJAdbOqpHIUPbSMwr0TX6DqMuVgGSzs3Zham4Uflv2kslbwDBhiadKbf2dSqkVHt2oVLodzH7lFQ17Y1KWvl8Ss68gcaH6XqXOB54/iCms8zp+SwYLoykNcF0e2WR5BrnQHjQ1wdJeiVnEmFcMXNN9tdhRMt6zIUWDbnwkFQM9ZQfQf+JAj2OmtrtjLqy9fktj2TZByQ7D8fZUFPRSFktb1tA5zM7AoHcqeeDMA/HI/XOC9B7HE44j0xIzJ2OSmLh8l2fgYcxTyPWhKTd</wsse:BinarySecurityToken>
      <ds:Signature xmlns:ds="http://www.w3.org/2000/09/xmldsig#" Id="SIG-9b807603-967f-45a6-8e6b-9ba81d8bf8b6">
        <ds:SignedInfo>
          <ds:CanonicalizationMethod Algorithm="http://www.w3.org/2001/10/xml-exc-c14n#">
            <ec:InclusiveNamespaces xmlns:ec="http://www.w3.org/2001/10/xml-exc-c14n#" PrefixList="soap">
            </ec:InclusiveNamespaces>
          </ds:CanonicalizationMethod>
          <ds:SignatureMethod Algorithm="http://www.w3.org/2000/09/xmldsig#rsa-sha1">
          </ds:SignatureMethod>
          <ds:Reference URI="#id-43980291-8cfe-4467-8b45-d91fcedc822c">
            <ds:Transforms>
              <ds:Transform Algorithm="http://www.w3.org/2001/10/xml-exc-c14n#">
              </ds:Transform>
            </ds:Transforms>
            <ds:DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1">
            </ds:DigestMethod>
            <ds:DigestValue>Di9adWlL922JasvPYwF6kR5XZwk=</ds:DigestValue>
          </ds:Reference>
        </ds:SignedInfo>
        <ds:SignatureValue>ONLxsInD4N2QjX1zmBOLj2YmI3mNBE9MSNfbeXo/5DY2UOobNWVm6jByWKGe00PRBK4CtszmdvUYs3xlJMRJTriAVRo7IYh5fpPPK5tnY56DglUPU1WSw+7v3n3a78uaslmv/wbG0LE2J5RnNm5cuIEHTi84b003tonnA81oDYoEBQ4+ImKiwCJdc2jRtLFXk8F2c+jDEWCNqj17UgCYIBBlMNx8suRBhhOGru12wbz7Pdu3wqNz378n70EzXG//D7Ot6W/ajowfewnyml/UNOHz4BDdZfGWUBqKG2yDvHUXYTXIorw/FRHcKnpXwll9msNRWGDlvHyWU00G3jShfQ==</ds:SignatureValue>
        <ds:KeyInfo Id="KI-1cdd7a75-3873-4503-b07f-5f81f23e4add">
          <wsse:SecurityTokenReference xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" wsu:Id="STR-1b455051-fd74-478a-818e-6e3ed0fa2b53">
            <wsse:Reference URI="#X509-d8ba59ff-57f6-48ab-9d94-9222aad76ad4" ValueType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-x509-token-profile-1.0#X509v3">
            </wsse:Reference>
          </wsse:SecurityTokenReference>
        </ds:KeyInfo>
      </ds:Signature>
    </wsse:Security>
  </soap:Header>
  <soap:Body xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" wsu:Id="id-43980291-8cfe-4467-8b45-d91fcedc822c">
    <ns3:annehmenAuftragRequest xmlns:ns2="http://spri.telekom.de/oss/v4/message" xmlns:ns3="http://spri.telekom.de/oss/v4/envelope" xmlns:ns4="http://spri.telekom.de/oss/v4/order" xmlns:ns5="http://spri.telekom.de/oss/v4/complex" xmlns:ns6="http://spri.telekom.de/oss/v4/fttx">
      <control>
        <zeitstempel>2026-06-19T14:19:36.999+02:00</zeitstempel>
        <majorRelease>04</majorRelease>
        <minorRelease>30</minorRelease>
        <signaturId>
          <issuer>CN=Enghouse Systems Ltd., OU=SPRI RS, O=Enghouse Systems Ltd., L=DUEREN, ST=NRW, C=DE</issuer>
          <serial>71850639</serial>
        </signaturId>
      </control>
      <auftrag>
        <externeAuftragsnummer>EKPAUF.0000259</externeAuftragsnummer>
        <auftraggeber>
          <auftraggebernummer>BE10002261</auftraggebernummer>
          <leistungsnummer>BLE1002261</leistungsnummer>
        </auftraggeber>
        <besteller>
          <auftraggebernummer>LE10001061</auftraggebernummer>
          <leistungsnummer>LLE1001061</leistungsnummer>
        </besteller>
        <geschaeftsfall xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="ns4:BereitstellungType">
          <ansprechpartner>
            <auftragsmanagement>
              <anrede>2</anrede>
              <vorname>Brigitte</vorname>
              <nachname>S_8_1_18_LE</nachname>
              <telefonnummer>+49 89 36089186</telefonnummer>
              <mobilfunknummer>+49 170 2222222</mobilfunknummer>
              <faxnummer>089 36089188</faxnummer>
              <emailadresse>auftrag@mail.de</emailadresse>
            </auftragsmanagement>
            <ansprechpartner>
              <anrede>1</anrede>
              <vorname>Peter</vorname>
              <nachname>Klein</nachname>
              <telefonnummer>089 36089186</telefonnummer>
              <mobilfunknummer>0170 2222222</mobilfunknummer>
              <faxnummer>+49 89 36089188</faxnummer>
              <emailadresse>technik@mail.de</emailadresse>
              <rolle>Technik</rolle>
            </ansprechpartner>
          </ansprechpartner>
          <termine>
            <auftraggeberWunschtermin>
              <datum>2026-06-26</datum>
              <zeitfenster>1</zeitfenster>
            </auftraggeberWunschtermin>
          </termine>
          <auftragsposition>
            <produkt xsi:type="ns6:ProduktFTTXType">
              <bezeichner>FTTH 50</bezeichner>
            </produkt>
            <geschaeftsfallProdukt xsi:type="ns6:FTTXBereitstellungType">
              <standortA>
                <person>
                  <anrede>1</anrede>
                  <vorname>Thomas</vorname>
                  <nachname>Hennlein</nachname>
                  <telefonnummer>02417 357159</telefonnummer>
                  <mobilfunknummer>+49 162 87654321</mobilfunknummer>
                </person>
                <strasse>
                  <strassenname>Hohe Str.</strassenname>
                  <hausnummer>24</hausnummer>
                  <hausnummernZusatz>A</hausnummernZusatz>
                </strasse>
                <land>DE</land>
                <postleitzahl>52249</postleitzahl>
                <ort>
                  <ortsname>Eschweiler</ortsname>
                  <ortsteil>Nothberg</ortsteil>
                </ort>
                <lageTAE_ONT>EFH,UG,Hausübergabepunkt</lageTAE_ONT>
              </standortA>
              <montageleistung>
                <ansprechpartner>
                  <anrede>1</anrede>
                  <vorname>Thomas</vorname>
                  <nachname>Hennlein</nachname>
                  <telefonnummer>+49 2417 357159</telefonnummer>
                  <mobilfunknummer>+49 162 87654321</mobilfunknummer>
                  <emailadresse>th_hennlein@mail.de</emailadresse>
                </ansprechpartner>
              </montageleistung>
            </geschaeftsfallProdukt>
            <position>
              <produkt xsi:type="ns6:ProduktFTTXType">
                <bezeichner>Expressentstörung</bezeichner>
              </produkt>
            </position>
          </auftragsposition>
        </geschaeftsfall>
        <geschaeftsfallArt>Bereitstellung</geschaeftsfallArt>
        <aenderungskennzeichen>Standard</aenderungskennzeichen>
      </auftrag>
    </ns3:annehmenAuftragRequest>
  </soap:Body>
</soap:Envelope>';
    }


}
