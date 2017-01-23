<?php
/**
 * Class RestGet
 * @package connector
 * This is a simple Afas Rest Connector class
 * I strongly recommend you to apply a filter when performing a GetConnector.
 * This increases the performance and you will receive only the data you need.
 * The setFilters method can add order rules as well
 *
 * Version 1.0
 *
 * Date: 18-01-2017
 * @author Silas de Rooy <silasderooy@gmail.com>
 *
 * Afas documentation: https://kb.afas.nl/index.php/details/kb/product:se//?0100000012636010129720160202&_ga=1.76117526.2059751307.1483368342#connectoren
 */
namespace connector;
Class RestGet extends Connection implements Connector
{
    /**
     * use this for options
     */
    const SKIP = 'skip';
    const TAKE = 'take';
    const OPERATOR_TYPES = 'operatortypes';
    const FILTERFIELD_IDS = 'filterfieldids';
    const FILTER_VALUES = 'filtervalues';
    const ORDERBY_FIELD = 'Orderbyfieldids';

    /**
     * RestGet constructor.
     * @param array $credentials
     */
    public function __construct(array $credentials)
    {
        parent::__construct($credentials);
        $this->target_url = "https://$this->participant.afasonlineconnector.nl/ProfitRestServices/connectors/" . $this->connector_id;
        $this->header = [
//            'Content-Type: text/xml; charset="utf-8"',
            'Accept: text/html',
            'Cache-Control: no-cache',
            'Pragma: no-cache',
            'Authorization: AfasToken ' . base64_encode('<token><version>1</version><data>' . $credentials['token'] . '</data></token>') . "\r\n"
        ];
    }

    /**
     * Use this for pagination
     * @param array $options
     */
    public function setOptions(array $options)
    {
        $count = 0;
        $p = '?';
        foreach ($options as $option => $value) {
            if ($count > 0) {
                $p = '&';
            }
            $this->options .= "$p$option=$value";
            $count++;
        }
        $this->target_url = $this->target_url . $this->options;
    }

    /**
     * You can add
     * example: Sort ASC../ProfitRestServices/connectors/Orderbyfieldids=Field1,Field2
     * example: Sort DESC..../ProfitRestServices/connectors/Orderbyfieldids=-Field1,-Field2
     * https://static-kb.afas.nl/datafiles/help/2_9_7/SE/NL/index.htm#App_Cnr_Rest_GET.htm
     * @param $filters
     */
    public function setFilters($filters)
    {
        $this->filters = $filters;
    }

    /**
     * Creates an readable response result as array
     * @param $response
     * @return string
     */
    public function getResponseHeaders($response)
    {

        $header_text = $response;
        $headers = '';

        foreach (explode("\r\n", $header_text) as $label => $msg) {
            if ($label === 0) {
                $headers['http_code'] = $msg;
            }
            else {
                list ($key, $value) = explode(': ', $msg);

                $headers[$key] = $value;
            }
        }
        return $headers;
    }

    /**
     * Returns status = true on successful result->data = (json)
     * Returns status = false on curl error(string), unexpected headers status code(array), empty result(array)
     * @return json|array
     */
    public function getResults()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->target_url . $this->filters);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->header);

        $content = curl_exec($ch);
        $curl_errno = curl_errno($ch);
        $curl_error = curl_error($ch);
        curl_close($ch);

        list($header, $content) = explode("\r\n\r\n", $content);

        $headers = $this->getResponseHeaders($header);

        if ($curl_errno > 0 || empty($content) || strpos($headers['http_code'], '200') === false) {
            echo '<pre>';
            $this->result->status = false;
            $this->result->error_msg = $curl_errno > 0 ? "cURL Error ($curl_errno): $curl_error\n" : $headers;

            return $this->result;

        }

        $this->result->status = true;
        $this->result->data = $content;
        return $this->result;
    }
    
    /**
     * get keys from result data
     * @param $obj
     * @return array of keys from result
     */
    public static function objectKeys($obj){
        $rows = json_decode($obj->data)->rows;
        return array_keys(get_object_vars(current($rows)));
    }
}
