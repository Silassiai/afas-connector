<?php
/**
 * Interface Connector
 * @package connector
 */
namespace connector;
interface Connector{
    public function setOptions(array $options);
    public function setFilters($filters);
    public function getResponseHeaders($response);
    public function getResults();
}