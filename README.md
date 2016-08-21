# wp-plugin-CRUD
a wordpress plugin for the CRUD


## OLTRANZ API
The request are in XML format as follows:

#### Request 
```xml
<COMMAND>    
    <MSISDN>250788312609</MSISDN>
    <SESSIONID>14666725080935241</SESSIONID>
    <NEWREQUEST>1</NEWREQUEST>
    <AGENTID>2</AGENTID>
    <INPUT>Kigali</INPUT>
    <SPID>342424</SPID>
    <FROMMULTIUSSD>false</FROMMULTIUSSD>
    <resume>false</resume>
</COMMAND>
```
//Ignore agentId, SPID, FrommultiUSSD and resume for now 


#### Response
```xml
<COMMAND>
    <MSISDN>250788312609</MSISDN>
    <SESSIONID>14666725080935241</SESSIONID>
    <FREEFLOW>C</FREEFLOW>
    <MESSAGE> ... Here goes the message </MESSAGE>
    <NEWREQUEST>0</NEWREQUEST>
    <MENUS>
        <MENU>1. KINYARWANDA^</MENU>
        <MENU>2. ENGLISH^</MENU>
        <MENU>3. FRANCAIS^</MENU>
    </MENUS>
</COMMAND>
```
FREEFLOW can be C : continue (gives to user input capacity   or B: Block (last message)

Your USSD application end point url (where to forward all USSD requests irrespective of the source Telco) will be needed