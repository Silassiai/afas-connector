<?php
/**
 * Class Connection
 * @package connector
 */
namespace connector;
use stdClass as stdClass;

require_once 'Connector.php';
require_once 'RestGet.php';
//require_once 'SoapGet.php';

class Connection{
    protected $token;
    protected $connector_id;
    protected $participant;
    protected $result;
    protected $target_url;
    protected $options;
    protected $header;
    protected $filters;

    /**
     * Connection constructor.
     * @param array $credentials
     */
    public function __construct(array $credentials)
    {
        $this->token = $credentials['token'];
        $this->connector_id = $credentials['connector_id'];
        $this->participant = $credentials['participant'];
        $this->result = new stdClass();
    }

    public function getParticipant()
    {
        return $this->participant;
    }

    /**
     * Show final Request Url
     * This can be used for debugging purposes
     */
    public function getRequestUrl()
    {
        return $this->target_url . $this->filters;
    }


    public function connectorId($connector_id)
    {
        $this->connector_id = $connector_id;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function getHeader()
    {
        return $this->header;
    }
}