<?php

/**
 * This is a wrapper for the TaxiFareFinder API
 * https://www.taxifarefinder.com/api.php
 * 
 * @author Tom Oehlrich <mail@tomoehlrich.com>
 * 
 * @link https://github.com/tomoehlrich/taxifarefinder-php
 * 
 * @license MIT
 */

namespace TomOehlrich\TaxiFareFinder;

use GuzzleHttp\Client;

use TomOehlrich\TaxiFareFinder\Exceptions\TffException;

class TaxiFareFinder
{

    /** @var \GuzzleHttp\Client */
    private $client;


    /** @var string */
    private $baseUri = 'https://api.taxifarefinder.com/';


    /** @var string */
    private $apiKey;


     /**
     * Constructs the TaxiFareFinder object
     *
     * @param string $apiKey The TaxiFareFinder API key. Required. 
     * The TaxiFareFinder API key can be requested under
     * https://www.taxifarefinder.com/contactus.php
     */
    public function __construct($apiKey)
    {
        if (!is_string($apiKey) || empty($apiKey)) {
            throw new \Exception('An API key is mandatory');
        }

        $this->client = new Client([
            'base_uri' => $this->baseUri,
            'verify' => false
        ]);

        $this->apiKey = $apiKey;
    }


    /**
     * Retrieves information about the nearest supported city.
     *
     * @param float     $lat            Latitude of the location the nearest supported city
     *                                  should be found at.
     * @param float     $lng            Longitude of the location the nearest supported city
     *                                  should be found at.
     * @param integer   $maxDistance    An optional value in meters that limits the search radius.
     *
     * @return mixed
     * 
     * @throws \Exception If mandatory parameters are missing or the API does not return status code 200.
     * @throws TomOehlrich\TaxiFareFinder\Exceptions\TffException If the API returns an error.
     */
    public function getNearestCity($lat, $lng, $maxDistance = 80467)
    {

        if(!$this->isValidLatitude($lat)) {
            throw new \Exception('Invalid latitude');
        }

        if(!$this->isValidLongitude($lng)) {
            throw new \Exception('Invalid longitude');
        }

        if(!is_int($maxDistance)) {
            throw new \Exception('The maximum distance must be given in meters');
        }

        $parameters = [
            'key' => $this->apiKey,
            'location' => $lat . ',' . $lng,
            'max_distance' => $maxDistance
        ];

        $response = $this->client->get('entity', [
            'query' => $parameters
        ]);

        if ($response->getStatusCode() !== 200) {
            throw new \Exception('Failed to load data from the TaxiFareFinder API');
        }

        $decodedResponse = json_decode($response->getBody());
        
        if ($decodedResponse->status <> 'OK') {
            throw new TffException($decodedResponse->status);
        }

        return [
            'name' => $decodedResponse->name ?? '',
            'full_name' => $decodedResponse->full_name ?? '',
            'handle' => $decodedResponse->handle ?? '',
            'locale' => $decodedResponse->locale ?? '',
            'distance' => $decodedResponse->distance ?? 0
        ];
    }

    

    /**
     * Retrieves the fare information.
     *
     * @param float     $originLat      Latitude of the starting point.
     * @param float     $originLng      Longitude of the starting point.
     * @param float     $destinationLat Latitude of the destination point.
     * @param float     $destinationLng Longitude of the destination point.
     * @param string    $city           An optional city entity to speed uo the API response.
     * @param string    $traffic        An optional indiciator of the traffic situation.
     *                                  Possible values: light|medium|heavy. 
     *                                  Defaults to medium 
     * @param int $maxDistance          An optional value in meters that limits the search radius.
     *
     * @return mixed
     * 
     * @throws \Exception If mandatory parameters are missing or the
     * API does not return status code 200.
     * @throws TomOehlrich\TaxiFareFinder\Exceptions\TffException If the API returns an error.
     */
    public function getTaxiFare($originLat, $originLng, $destinationLat, $destinationLng, $traffic = 'medium', $maxDistance = 80467, $city = '')
    {

        if(!$this->isValidLatitude($originLat)) {
            throw new \Exception('Invalid origin latitude');
        }

        if(!$this->isValidLongitude($originLng)) {
            throw new \Exception('Invalid origin longitude');
        }

        if(!$this->isValidLatitude($destinationLat)) {
            throw new \Exception('Invalid destination latitude');
        }

        if(!$this->isValidLongitude($destinationLng)) {
            throw new \Exception('Invalid destination longitude');
        }

        if(!in_array($traffic, ['light', 'medium', 'heavy'])) {
            throw new \Exception('Traffic is optional but, if used, must be light|medium|heavy');
        }

        if(!is_int($maxDistance)) {
            throw new \Exception('The maximum distance must be given in meters');
        }

        $parameters = [
            'key' => $this->apiKey,
            'origin' => $originLat . ',' . $originLng,
            'destination' => $destinationLat . ',' . $destinationLng,
            'traffic' => $traffic,
            'max_distance' => $maxDistance
        ];

        if(!empty($city)) {
            $parameters['entity_handle'] = $city;
        }

        $response = $this->client->get('fare', [
            'query' => $parameters
        ]);

        if ($response->getStatusCode() !== 200) {
            throw new \Exception('Failed to load data from the TaxiFareFinder API');
        }

        $decodedResponse = json_decode($response->getBody());

        if ($decodedResponse->status <> 'OK') {
            throw new TffException($decodedResponse->status);
        }

        return [                
            'total_fare' => $decodedResponse->total_fare ?? 0,
            'initial_fare' => $decodedResponse->initial_fare ?? 0,
            'metered_fare' => $decodedResponse->metered_fare ?? 0,
            'tip_amount' => $decodedResponse->tip_amount ?? 0,
            'tip_percentage' => $decodedResponse->tip_percentage ?? 0,
            'locale' => $decodedResponse->locale ?? '',
            'currency' => !empty($decodedResponse->currency) ? json_decode(json_encode($decodedResponse->currency), true) : [],
            'rate_area' => $decodedResponse->rate_area ?? '',
            'flat_rates' => !empty($decodedResponse->flat_rates) ? json_decode(json_encode($decodedResponse->flat_rates), true) : [],
            'extra_charges' => !empty($decodedResponse->extra_charges) ? json_decode(json_encode($decodedResponse->extra_charges), true) : [],
            'distance' => $decodedResponse->distance ?? 0,
            'duration' => $decodedResponse->duration ?? 0
        ];
    }


    /**
     * Retrieves a list of taxi companies in the given city (entity).
     *
     * @param string    $city   A city/entity that the taxi companies should be found in.
     *
     * @return mixed
     * 
     * @throws \Exception If mandatory parameters are missing or the
     * API does not return status code 200.
     * @throws TomOehlrich\TaxiFareFinder\Exceptions\TffException If the API returns an error.
     */
    public function getTaxiCompanies($city)
    {

        if(empty($city)) {
            throw new \Exception('The city is mandatory');
        }

        $parameters = [
            'key' => $this->apiKey,
            'entity_handle' => $city
        ];

        $response = $this->client->get('businesses', [
            'query' => $parameters
        ]);

        if ($response->getStatusCode() !== 200) {
            throw new \Exception('Failed to load data from the TaxiFareFinder API');
        }

        $decodedResponse = json_decode($response->getBody());

        if ($decodedResponse->status <> 'OK') {
            throw new TffException($decodedResponse->status);
        }

        return [
            'businesses' => !empty($decodedResponse->businesses) ? json_decode(json_encode($decodedResponse->businesses), true) : []
        ];
    }


    /**
     * Validates a given latitude
     * 
     * @param $latitude
     * 
     * @return boolean
     */
    private function isValidLatitude($lat)
    {
        return preg_match('/^(\+|-)?(?:90(?:(?:\.0{1,8})?)|(?:[0-9]|[1-8][0-9])(?:(?:\.[0-9]{1,8})?))$/', $lat);
    }

    /**
     * Validates a given longitude
     * 
     * @param $longitude
     * 
     * @return boolean
     */
    private function isValidLongitude($lng) 
    {
        return preg_match('/^(\+|-)?(?:180(?:(?:\.0{1,8})?)|(?:[0-9]|[1-9][0-9]|1[0-7][0-9])(?:(?:\.[0-9]{1,8})?))$/', $lng);
    }
}
