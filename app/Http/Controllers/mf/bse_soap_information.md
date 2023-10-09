Mon Aug 28 17:29:35 IST 2023: DEBUG: http-outgoing >> 
GET /StarMFFileUploadService/StarMFFileUploadService.svc?singleWsdl HTTP/1.1
Host: bsestarmfdemo.bseindia.com
Connection: Keep-Alive
User-Agent: Apache-HttpClient/4.5.5 (Java/16.0.2)
Accept-Encoding: gzip,deflate


Mon Aug 28 17:29:36 IST 2023: DEBUG: http-incoming << 
HTTP/1.1 200 OK
Cache-Control: no-cache, no-store, must-revalidate
Content-Type: text/xml; charset=UTF-8
Server: BSE LTD.
X-XSS-Protection: 1; mode=block
Strict-Transport-Security: max-age=31536000; includeSubDomains; preload
X-Content-Type-Options: nosniff
Content-Security-Policy: default-src 'self'; img-src 'self'; object-src 'none'; script-src 'self'; connect-src * data: blob: filesystem:; style-src 'self' data: chrome-extension-resource: ; img-src 'self' data: chrome-extension-resource:; frame-src 'self' data: chrome-extension-resource:; font-src 'self' data: chrome-extension-resource:; media-src * data: blob: filesystem:;
Referrer-Policy: no-referrer | same-origin | origin | strict-origin | no-origin-when-downgrading
X-Permitted-Cross-Domain-Policies: none
Expect-CT: max-age=604800, report-uri="https://foo.example/report"
Access-Control-Allow-Origin: *
Date: Mon, 28 Aug 2023 11:59:35 GMT
Content-Length: 15295

<?xml version="1.0" encoding="utf-8"?><wsdl:definitions name="StarMFFileUploadService" targetNamespace="http://tempuri.org/" xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/" xmlns:wsam="http://www.w3.org/2007/05/addressing/metadata" xmlns:wsx="http://schemas.xmlsoap.org/ws/2004/09/mex" xmlns:wsap="http://schemas.xmlsoap.org/ws/2004/08/addressing/policy" xmlns:msc="http://schemas.microsoft.com/ws/2005/12/wsdl/contract" xmlns:wsp="http://schemas.xmlsoap.org/ws/2004/09/policy" xmlns:xsd="http://www.w3.org
/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/wsdl/soap/" xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" xmlns:soap12="http://schemas.xmlsoap.org/wsdl/soap12/" xmlns:soapenc="http://schemas.xmlsoap.org/soap/encoding/" xmlns:tns="http://tempuri.org/" xmlns:wsa10="http://www.w3.org/2005/08/addressing" xmlns:wsaw="http://www.w3.org/2006/05/addressing/wsdl" xmlns:wsa="http://schemas.xmlsoap.org/ws/2004/08/addressing"><wsp:Policy wsu:Id="WSHttpBinding_IStarMFFileUploadService_policy"><wsp:ExactlyOne><wsp:All><wsaw:UsingAddressing/></wsp:All></wsp:ExactlyOne></wsp:Policy><wsp:Policy wsu:Id="WSHttpBinding_IStarMFFileUploadService1_policy"><wsp:ExactlyOne><wsp:All><sp:TransportBinding xmlns:sp="http://schemas.xmlsoap.org/ws/2005/07/securitypolicy"><wsp:Policy><sp:TransportToken><wsp:Policy><sp:HttpsToken RequireClientCertificate="false"/></wsp:Policy></sp:TransportToken><sp:AlgorithmSuite><wsp:Policy><sp:Basic256/></wsp:Policy></sp:AlgorithmSuite><sp:Layout><wsp:Policy><sp:Strict/></wsp:Policy></sp:Layout></wsp:Policy></sp:TransportBinding><wsaw:UsingAddressing/></wsp:All></wsp:ExactlyOne></wsp:Policy><wsdl:types><xs:schema elementFormDefault="qualified" targetNamespace="http://tempuri.org/" xmlns:xs="http://www.w3.org/2001/XMLSchema"><xs:import namespace="http://schemas.datacontract.org/2004/07/StarMFFileUploadService"/><xs:element name="GetPassword"><xs:complexType><xs:sequence><xs:element minOccurs="0" name="Param" nillable="true" type="q1:PasswordRequest" xmlns:q1="http://schemas.datacontract.org/2004/07/StarMFFileUploadService"/></xs:sequence></xs:complexType></xs:element><xs:element name="GetPasswordResponse"><xs:complexType><xs:sequence><xs:element minOccurs="0" name="GetPasswordResult" nillable="true" type="q2:Response" xmlns:q2="http://schemas.datacontract.org/2004/07/StarMFFileUploadService"/></xs:sequence></xs:complexType></xs:element><xs:element name="UploadFile"><xs:complexType><xs:sequence><xs:element minOccurs="0" name="data" nillable="true" type="q3:FileData" xmlns:q3="http://schemas.datacontract.org/2004/07/StarMFFileUploadService"/></xs:sequence></xs:complexType></xs:element><xs:element name="UploadFileResponse"><xs:complexType><xs:sequence><xs:element minOccurs="0" name="UploadFileResult" nillable="true" type="q4:Response" xmlns:q4="http://schemas.datacontract.org/2004/07/StarMFFileUploadService"/></xs:sequence></xs:complexType></xs:element><xs:element name="UploadMandateScanFile"><xs:complexType><xs:sequence><xs:element minOccurs="0" name="Data" nillable="true" type="q5:MandateScanFileData" xmlns:q5="http://schemas.datacontract.org/2004/07/StarMFFileUploadService"/></xs:sequence></xs:complexType></xs:element><xs:element name="UploadMandateScanFileResponse"><xs:complexType><xs:sequence><xs:element minOccurs="0" name="UploadMandateScanFileResult" nillable="true" type="q6:Respon
se" xmlns:q6="http://schemas.datacontract.org/2004/07/StarMFFileUploadService"/></xs:sequence></xs:complexType></xs:element><xs:element name="JsonData"><xs:complexType><xs:sequence><xs:element minOccurs="0" name="Data" nillable="true" type="xs:string"/></xs:sequence></xs:complexType></xs:element><xs:element name="JsonDataResponse"><xs:complexType><xs:sequence/></xs:complexType></xs:element></xs:schema><xs:schema attributeFormDefault="qualified" elementFormDefault="qualified" targetNamespace="http://schemas.microsoft.com/2003/10/Serialization/" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:tns="http://schemas.microsoft.com/2003/10/Serialization/"><xs:element name="anyType" nillable="true" type="xs:anyType"/><xs:element name="anyURI" nillable="true" type="xs:anyURI"/><xs:element name="base64Binary" nillable="true" type="xs:base64Binary"/><xs:element name="boolean" nillable="true" type="xs:boolean"/><xs:element name="byte" nillable="true" type="xs:byte"/><xs:element name="dateTime" nillable="true" type="xs:dateTime"/><xs:element name="decimal" nillable="true" type="xs:decimal"/><xs:element name="double" nillable="true" type="xs:double"/><xs:element name="float" nillable="true" type="xs:float"/><xs:element name="int" nillable="true" type="xs:int"/><xs:element name="long" nillable="true" type="xs:long"/><xs:element name="QName" nillable="true" type="xs:QName"/><xs:element name="short" nillable="true" type="xs:short"/><xs:element name="string" nillable="true" type="xs:string"/><xs:element name="unsignedByte" nillable="true" type="xs:unsignedByte"/><xs:element name="unsignedInt" nillable="true" type="xs:unsignedInt"/><xs:element name="unsignedLong" nillable="true" type="xs:unsignedLong"/><xs:element name="unsignedShort" nillable="true" type="xs:unsignedShort"/><xs:element name="char" nillable="true" type="tns:char"/><xs:simpleType name="char"><xs:restriction base="xs:int"/></xs:simpleType><xs:element name="duration" nillable="true" type="tns:duration"/><xs:simpleType name="duration"><xs:restriction base="xs:duration"><xs:pattern value="\-?P(\d*D)?(T(\d*H)?(\d*M)?(\d*(\.\d*)?S)?)?"/><xs:minInclusive value="-P10675199DT2H48M5.4775808S"/><xs:maxInclusive value="P10675199DT2H48M5.4775807S"/></xs:restriction></xs:simpleType><xs:element name="guid" nillable="true" type="tns:guid"/><xs:simpleType name="guid"><xs:restriction base="xs:string"><xs:pattern value="[\da-fA-F]{8}-[\da-fA-F]{4}-[\da-fA-F]{4}-[\da-fA-F]{4}-[\da-fA-F]{12}"/></xs:restriction></xs:simpleType><xs:attribute name="FactoryType" type="xs:QName"/><xs:attribute name="Id" type="xs:ID"/><xs:attribute name="Ref" type="xs:IDREF"/></xs:schema><xs:schema elementFormDefault="qualified" targetNamespace="http://schemas.datacontract.org/2004/07/StarMFFileUploadService" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:tns="http://schemas.datacontract.org/2004/07/StarMFFileUploadService"><xs:complexType name="PasswordRequest"><xs:sequence><xs:element minOccurs="0" name="MemberId" nillable="true" type="xs:string"/><xs:element minOccurs="0" name="Password" nillable="true" type="xs:string"/><xs:element minOccurs="0" name="UserId" nillable="true" type="xs:string"/></xs:sequence></xs:complexType><xs:element name="PasswordRequest" nillable="true" type="tns:PasswordRequest"/><xs:complexType name="Response"><xs:sequence><xs:element minOccurs="0" name="Filler" nillable="true" type="xs:string"/><xs:element minOccurs="0" name="ResponseString" nillable="true" type="xs:string"/><xs:element minOccurs="0" name="Status" nillable="true" type="xs:string"/></xs:sequence></xs:complexType><xs:element name="Response" nillable="true" type="tns:Response"/><xs:complexType name="FileData"><xs:sequence><xs:element minOccurs="0" name="ClientCode" nillable="true" type="xs:string"/><xs:element minOccurs="0" name="DocumentType" nillable="true" type="xs:string"/><x
s:element minOccurs="0" name="EncryptedPassword" nillable="true" type="xs:string"/><xs:element minOccurs="0" name="FileName" nillable="true" type="xs:string"/><xs:element minOccurs="0" name="Filler1" nillable="true" type="xs:string"/><xs:element minOccurs="0" name="Filler2" nillable="true" type="xs:string"/><xs:element minOccurs="0" name="Flag" nillable="true" type="xs:string"/><xs:element minOccurs="0" name="MemberCode" nillable="true" type="xs:string"/><xs:element minOccurs="0" name="UserId" nillable="true" type="xs:string"/><xs:element minOccurs="0" name="pFileBytes" nillable="true" type="xs:base64Binary"/></xs:sequence></xs:complexType><xs:element name="FileData" nillable="true" type="tns:FileData"/><xs:complexType name="MandateScanFileData"><xs:sequence><xs:element minOccurs="0" name="ClientCode" nillable="true" type="xs:string"/><xs:element minOccurs="0" name="EncryptedPassword" nillable="true" type="xs:string"/><xs:element minOccurs="0" name="Filler1" nillable="true" type="xs:string"/><xs:element minOccurs="0" name="Filler2" nillable="true" type="xs:string"/><xs:element minOccurs="0" name="Flag" nillable="true" type="xs:string"/><xs:element minOccurs="0" name="ImageName" nillable="true" type="xs:string"/><xs:element minOccurs="0" name="ImageType" nillable="true" type="xs:string"/><xs:element minOccurs="0" name="MandateId" nillable="true" type="xs:string"/><xs:element minOccurs="0" name="MandateType" nillable="true" ty
pe="xs:string"/><xs:element minOccurs="0" name="MemberCode" nillable="true" type="xs:string"/><xs:element minOccurs="0" name="pFileBytes" nillable="true" type="xs:base64Binary"/></xs:sequence></xs:complexType><xs:element name="MandateScanFileData" nillable="true" type="tns:MandateScanFileData"/></xs:schema></wsdl:types><wsdl:message name="IStarMFFileUploadService_GetPassword_InputMessage"><wsdl:part name="parameters" element="tns:GetPassword"/></wsdl:message><wsdl:message name="IStarMFFileUploadService_GetPassword_OutputMessage"><wsdl:part name="parameters" element="tns:GetPasswordResponse"/></wsdl:message><wsdl:message name="IStarMFFileUploadService_UploadFile_InputMessage"><wsdl:part name="parameters" element="tns:UploadFile"/></wsdl:message><wsdl:message name="IStarMFFileUploadService_UploadFile_OutputMessage"><wsdl:part name="parameters" element="tns:UploadFileResponse"/></wsdl:message><wsdl:message name="IStarMFFileUploadService_UploadMandateScanFile_InputMessage"><wsdl:part name="parameters" element="tns:UploadMandateScanFile"/></wsdl:message><wsdl:message name="IStarMFFileUploadService_UploadMandateScanFile_OutputMessage"><wsdl:part name="parameters" element="tns:UploadMandateScanFileResponse"/></wsdl:message><wsdl:message name="IStarMFFileUploadService_JsonData_InputMessage"><wsdl:part name="parameters" element="tns:JsonData"/></wsdl:message><wsdl:message name="IStarMFFileUploadService_JsonData_OutputMessage"><wsdl:p
art name="parameters" element="tns:JsonDataResponse"/></wsdl:message><wsdl:portType name="IStarMFFileUploadService"><wsdl:operation name="GetPassword"><wsdl:input wsaw:Action="http://tempuri.org/IStarMFFileUploadService/GetPassword" message="tns:IStarMFFileUploadService_GetPassword_InputMessage"/><wsdl:output wsaw:Action="http://tempuri.org/IStarMFFileUploadService/GetPasswordResponse" message="tns:IStarMFFileUploadService_GetPassword_OutputMessage"/></wsdl:operation><wsdl:operation name="UploadFile"><wsdl:input wsaw:Action="http://tempuri.org/IStarMFFileUploadService/UploadFile" message="tns:IStarMFFileUploadService_UploadFile_InputMessage"/><wsdl:output wsaw:Action="http://tempuri.org/IStarMFFileUploadService/UploadFileResponse" message="tns:IStarMFFileUploadService_UploadFile_OutputMessage"/></wsdl:operation><wsdl:operation name="UploadMandateScanFile"><wsdl:input wsaw:Action="http://tempuri.org/IStarMFFileUploadService/UploadMandateScanFile" message="tns:IStarMFFileUploadService_UploadMandateScanFile_InputMessage"/><wsdl:output wsaw:Action="http://tempuri.org/IStarMFFileUploadService/UploadMandateScanFileResponse" message="tns:IStarMFFileUploadService_UploadMandateScanFile_OutputMessage"/></wsdl:operation><wsdl:operation name="JsonData"><wsdl:input wsaw:Action="http://tempuri.org/IStarMFFileUploadService/JsonData" message="tns:IStarMFFileUploadService_JsonData_InputMessage"/><wsdl:output wsaw:Action="http://tempuri.org/I
StarMFFileUploadService/JsonDataResponse" message="tns:IStarMFFileUploadService_JsonData_OutputMessage"/></wsdl:operation></wsdl:portType><wsdl:binding name="WSHttpBinding_IStarMFFileUploadService" type="tns:IStarMFFileUploadService"><wsp:PolicyReference URI="#WSHttpBinding_IStarMFFileUploadService_policy"/><soap12:binding transport="http://schemas.xmlsoap.org/soap/http"/><wsdl:operation name="GetPassword"><soap12:operation soapAction="http://tempuri.org/IStarMFFileUploadService/GetPassword" style="document"/><wsdl:input><soap12:body use="literal"/></wsdl:input><wsdl:output><soap12:body use="literal"/></wsdl:output></wsdl:operation><wsdl:operation name="UploadFile"><soap12:operation soapAction="http://tempuri.org/IStarMFFileUploadService/UploadFile" style="document"/><wsdl:input><soap12:body use="literal"/></wsdl:input><wsdl:output><soap12:body use="literal"/></wsdl:output></wsdl:operation><wsdl:operation name="UploadMandateScanFile"><soap12:operation soapAction="http://tempuri.org/IStarMFFileUploadService/UploadMandateScanFile" style="document"/><wsdl:input><soap12:body use="literal"/></wsdl:input><wsdl:output><soap12:body use="literal"/></wsdl:output></wsdl:operation><wsdl:operation name="JsonData"><soap12:operation soapAction="http://tempuri.org/IStarMFFileUploadService/JsonData" style="document"/><wsdl:input><soap12:body use="literal"/></wsdl:input><wsdl:output><soap12:body use="literal"/></wsdl:output></wsdl:operation><
/wsdl:binding><wsdl:binding name="WSHttpBinding_IStarMFFileUploadService1" type="tns:IStarMFFileUploadService"><wsp:PolicyReference URI="#WSHttpBinding_IStarMFFileUploadService1_policy"/><soap12:binding transport="http://schemas.xmlsoap.org/soap/http"/><wsdl:operation name="GetPassword"><soap12:operation soapAction="http://tempuri.org/IStarMFFileUploadService/GetPassword" style="document"/><wsdl:input><soap12:body use="literal"/></wsdl:input><wsdl:output><soap12:body use="literal"/></wsdl:output></wsdl:operation><wsdl:operation name="UploadFile"><soap12:operation soapAction="http://tempuri.org/IStarMFFileUploadService/UploadFile" style="document"/><wsdl:input><soap12:body use="literal"/></wsdl:input><wsdl:output><soap12:body use="literal"/></wsdl:output></wsdl:operation><wsdl:operation name="UploadMandateScanFile"><soap12:operation soapAction="http://tempuri.org/IStarMFFileUploadService/UploadMandateScanFile" style="document"/><wsdl:input><soap12:body use="literal"/></wsdl:input><wsdl:output><soap12:body use="literal"/></wsdl:output></wsdl:operation><wsdl:operation name="JsonData"><soap12:operation soapAction="http://tempuri.org/IStarMFFileUploadService/JsonData" style="document"/><wsdl:input><soap12:body use="literal"/></wsdl:input><wsdl:output><soap12:body use="literal"/></wsdl:output></wsdl:operation></wsdl:binding><wsdl:service name="StarMFFileUploadService"><wsdl:port name="WSHttpBinding_IStarMFFileUploadService" bindin
g="tns:WSHttpBinding_IStarMFFileUploadService"><soap12:address location="http://bsestarmfdemo.bseindia.com/StarMFFileUploadService/StarMFFileUploadService.svc/Basic"/><wsa10:EndpointReference><wsa10:Address>http://bsestarmfdemo.bseindia.com/StarMFFileUploadService/StarMFFileUploadService.svc/Basic</wsa10:Address></wsa10:EndpointReference></wsdl:port><wsdl:port name="WSHttpBinding_IStarMFFileUploadService1" binding="tns:WSHttpBinding_IStarMFFileUploadService1"><soap12:address location="https://bsestarmfdemo.bseindia.com/StarMFFileUploadService/StarMFFileUploadService.svc/Secure"/><wsa10:EndpointReference><wsa10:Address>https://bsestarmfdemo.bseindia.com/StarMFFileUploadService/StarMFFileUploadService.svc/Secure</wsa10:Address></wsa10:EndpointReference></wsdl:port></wsdl:service></wsdl:definitions>

Mon Aug 28 18:01:33 IST 2023: DEBUG: http-outgoing >> 
POST /StarMFFileUploadService/StarMFFileUploadService.svc/Secure HTTP/1.1
Accept-Encoding: gzip,deflate
Content-Type: application/soap+xml;charset=UTF-8;action="http://tempuri.org/IStarMFFileUploadService/GetPassword"
Content-Length: 831
Host: bsestarmfdemo.bseindia.com
Connection: Keep-Alive
User-Agent: Apache-HttpClient/4.5.5 (Java/16.0.2)

<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope" xmlns:tem="http://tempuri.org/" xmlns:star="http://schemas.datacontract.org/2004/07/StarMFFileUploadService">
   <soap:Header xmlns:wsa="http://www.w3.org/2005/08/addressing"><wsa:Action>http://tempuri.org/IStarMFFileUploadService/GetPassword</wsa:Action><wsa:To>https://bsestarmfdemo.bseindia.com/StarMFFileUploadService/StarMFFileUploadService.svc/Secure</wsa:To></soap:Header>
   <soap:Body>
      <tem:GetPassword>
         <!--Optional:-->
         <tem:Param>
            <!--Optional:-->
            <star:MemberId>?</star:MemberId>
            <!--Optional:-->
            <star:Password>?</star:Password>
            <!--Optional:-->
            <star:UserId>?</star:UserId>
         </tem:Param>
      </tem:GetPassword>
   </soap:Body>
</soap:Envelope>

Mon Aug 28 18:01:33 IST 2023: DEBUG: http-incoming << 
HTTP/1.1 200 OK
Cache-Control: no-cache, no-store, must-revalidate
Content-Type: application/soap+xml; charset=utf-8
Server: BSE LTD.
X-XSS-Protection: 1; mode=block
Strict-Transport-Security: max-age=31536000; includeSubDomains; preload
X-Content-Type-Options: nosniff
Content-Security-Policy: default-src 'self'; img-src 'self'; object-src 'none'; script-src 'self'; connect-src * data: blob: filesystem:; style-src 'self' data: chrome-extension-resource: ; img-src 'self' data: chrome-extension-resource:; frame-src 'self' data: chrome-extension-resource:; font-src 'self' data: chrome-extension-resource:; media-src * data: blob: filesystem:;
Referrer-Policy: no-referrer | same-origin | origin | strict-origin | no-origin-when-downgrading
X-Permitted-Cross-Domain-Policies: none
Expect-CT: max-age=604800, report-uri="https://foo.example/report"
Access-Control-Allow-Origin: *
Date: Mon, 28 Aug 2023 12:31:32 GMT
Content-Length: 609

<s:Envelope xmlns:s="http://www.w3.org/2003/05/soap-envelope" xmlns:a="http://www.w3.org/2005/08/addressing"><s:Header><a:Action s:mustUnderstand="1">http://tempuri.org/IStarMFFileUploadService/GetPasswordResponse</a:Action></s:Header><s:Body><GetPasswordResponse xmlns="http://tempuri.org/"><GetPasswordResult xmlns:b="http://schemas.datacontract.org/2004/07/StarMFFileUploadService" xmlns:i="http://www.w3.org/2001/XMLSchema-instance"><b:Filler i:nil="true"/><b:ResponseString>FAILED: USER NO
T EXISTS</b:ResponseString><b:Status>101</b:Status></GetPasswordResult></GetPasswordResponse></s:Body></s:Envelope>
[read] I/O error: Read timed out

Mon Aug 28 18:01:54 IST 2023: DEBUG: http-outgoing >> 
POST /StarMFFileUploadService/StarMFFileUploadService.svc/Secure HTTP/1.1
Accept-Encoding: gzip,deflate
Content-Type: application/soap+xml;charset=UTF-8;action="http://tempuri.org/IStarMFFileUploadService/GetPassword"
Content-Length: 846
Host: bsestarmfdemo.bseindia.com
Connection: Keep-Alive
User-Agent: Apache-HttpClient/4.5.5 (Java/16.0.2)

<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope" xmlns:tem="http://tempuri.org/" xmlns:star="http://schemas.datacontract.org/2004/07/StarMFFileUploadService">
   <soap:Header xmlns:wsa="http://www.w3.org/2005/08/addressing"><wsa:Action>http://tempuri.org/IStarMFFileUploadService/GetPassword</wsa:Action><wsa:To>https://bsestarmfdemo.bseindia.com/StarMFFileUploadService/StarMFFileUploadService.svc/Secure</wsa:To></soap:Header>
   <soap:Body>
      <tem:GetPassword>
         <tem:Param>
            <star:MemberId>15133</star:MemberId>
            <star:Password>12345^</star:Password>
            <star:UserId>1513301</star:UserId>
         </tem:Param>
      </tem:GetPassword>
   </soap:Body>
</soap:Envelope>


<s:Envelope xmlns:s="http://www.w3.org/2003/05/soap-envelope" xmlns:a="http://www.w3.org/2005/08/addressing">
   <s:Header>
      <a:Action s:mustUnderstand="1">http://tempuri.org/IStarMFFileUploadService/GetPasswordResponse</a:Action>
   </s:Header>
   <s:Body>
      <GetPasswordResponse xmlns="http://tempuri.org/">
         <GetPasswordResult xmlns:b="http://schemas.datacontract.org/2004/07/StarMFFileUploadService" xmlns:i="http://www.w3.org/2001/XMLSchema-instance">
            <b:Filler i:nil="true"/><b:ResponseString>3uE5qo+zQC4kx2jQNLat6Jex6ehTctnkPIo3hSvrv8RF8tZjfLLkLw==</b:ResponseString>
            <b:Status>100</b:Status>
         </GetPasswordResult>
      </GetPasswordResponse>
   </s:Body>
</s:Envelope>

<s:Envelope xmlns:s="http://www.w3.org/2003/05/soap-envelope" xmlns:a="http://www.w3.org/2005/08/addressing">
    <s:Header>
        <a:Action s:mustUnderstand="1">http://tempuri.org/IStarMFFileUploadService/UploadFileResponse</a:Action>
    </s:Header>
    <s:Body>
        <UploadFileResponse xmlns="http://tempuri.org/">
            <UploadFileResult xmlns:b="http://schemas.datacontract.org/2004/07/StarMFFileUploadService" xmlns:i="http://www.w3.org/2001/XMLSchema-instance">
                <b:Filler i:nil="true"/>
                <b:ResponseString>FAILED: IMAGE IS ALREADY AVAILABLE AND IMAGE STATUS IS PENDING</b:ResponseString>
                <b:Status>101</b:Status>
            </UploadFileResult>
        </UploadFileResponse>
    </s:Body>
</s:Envelope>