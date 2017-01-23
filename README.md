# Profit Rest Services (JSON)
A simple Afas Rest Connector Class

What you need
-------------
* Afas Token
* Afas connector id
* Afas participant number

```php
<?php

require_once 'lib/connector/Connection.php';

use connector\RestGet as RestGet;

$acg = new RestGet([
    'token' => YOUR_AFAS_TOKEN,
    'connector_id' => YOUR_AFAS_CONNECTORID,
    'participant' => YOUR_PARTICIPANT_NUMBER
]);

// setFilters is optional, you can add filters with setOptions as wel
// Filter Documentation: https://static-kb.afas.nl/datafiles/help/2_9_7/SE/NL/index.htm#App_Cnr_Rest_GET.htm
// $acg->setFilters('filterfieldids='. urlencode('Naam') .'&filtervalues='.urlencode('Silas de Rooy').'&operatortypes=Type');

$acg->setOptions([
    RestGet::SKIP => 0,
    RestGet::TAKE => 10,
    // RestGet::ORDERBY_FIELD => '-Number',
    // RestGet::FILTERFIELD_IDS => 'Number',
    // RestGet::FILTER_VALUES => '37317',
    // RestGet::OPERATOR_TYPES => 4
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
