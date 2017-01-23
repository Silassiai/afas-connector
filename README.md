# Profit Rest Services (JSON)
A simple Afas Rest Connector Class

You need a token, afas connector id and a participant number

```php
<?php

require_once 'lib/connector/Connection.php';

use connector\RestGet as RestGet;

$acg = new RestGet([
    'token' => YOUR_AFAS_TOKEN,
    'connector_id' => YOUR_AFAS_CONNECTORID,
    'participant' => YOUR_PARTICIPANT_NUMBER
]);

// Filter Documentation: https://static-kb.afas.nl/datafiles/help/2_9_7/SE/NL/index.htm#App_Cnr_Rest_GET.htm
// $acg->setFilters('filterfieldids='. urlencode('Naam') .'&filtervalues='.urlencode('Silas de Rooy').'&operatortypes=Type');

$acg->setOptions([
    RestGet::SKIP => 0,
    RestGet::TAKE => 10,
    RestGet::OPERATORTYPES => 1
]);

$result = $acg->getResults();

// Show final Request Url
// This can be used for debugging purposes
// echo $acg->getRequestUrl(); exit;
// var_dump($acg->getHeader()); exit;


if($result->status){
    echo $result->data;
} else {
    var_dump($result->error_msg);
}
```
