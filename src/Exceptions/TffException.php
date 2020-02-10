<?php

namespace TomOehlrich\TaxiFareFinder\Exceptions;

class TffException extends \Exception
{


    public function __construct($errorCode = '')
    {
        $message = $this->getFullMessage($errorCode) . ' (' . $errorCode . ')';

        parent::__construct($message);
    }


    public function getFullMessage($errorCode)
    {

        if ($errorCode == 'NO_MATCHING_ENTITY') {
            return 'No entity found within given constraints';
        } else if ($errorCode == 'UNKNOWN_ENTITY') {
            return 'No entity with given entity handle found';
        } else if ($errorCode == 'UNSUPPORTED_ENTITY') {
            return 'The entity handle provided is not a supported entity for the TFF API';
        } else if ($errorCode == 'MISSING_PARAMETER') {
            return 'A required parameter is missing';
        } else if ($errorCode == 'UNKNOWN_PARAMETER') {
            return 'A provided parameter is not valid for this API call';
        } else if ($errorCode == 'WRONG_PARAMETER_TYPE') {
            return 'A given parameter value is of the wrong type';
        } else if ($errorCode == 'INVALID_KEY') {
            return 'API key provided is invalid';
        } else if ($errorCode == 'REQUEST_LIMIT_REACHED') {
            return 'Daily request limit for this API key has been reached';
        } else if ($errorCode == 'REQUEST_TIMED_OUT') {
            return 'The request to the server took too long, try again';
        } else if ($errorCode == 'QUERY_RATE_LIMIT_EXCEEDED') {
            return 'Query rate has been exceeded, throttle queries at 10 or fewer per second';
        } else if ($errorCode == 'NO_ROUTE_FOUND') {
            return 'No route could be found between origin and destination';
        } else if ($errorCode == 'NO_NEARBY_ENTITY') {
            return 'There is no entity near the origin location';
        } else if ($errorCode == 'ERROR') {
            return 'Unspecified error occurred';
        }

        return 'Unspecified error occurred';
    }


}
