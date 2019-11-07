<?php namespace Econtract\Compare\ServiceProviders;


use Ixudra\Curl\CurlService;
use Aanbieders;

class BaseServiceProvider {

    protected $apiClient = null;

    protected $curlService = null;

    protected $defaults = array();

    protected $guards = array();


    protected function getApiClient()
    {
        $isStaging = false;
        if( $_SERVER[ 'API_staging' ] === 'true' ) {
            $isStaging = true;
        }

        $this->apiClient = new Aanbieders(
            array(
                'key'           => $_SERVER[ 'API_key' ],
                'secret'        => $_SERVER[ 'API_secret' ],
                'host'          => $_SERVER[ 'API_host' ],
                'staging'       => $isStaging
            )
        );

        return $this->apiClient;
    }

    protected function getCurlService()
    {
        if( is_null($this->curlService) ) {
            $this->curlService = new CurlService();
        }

        return $this->curlService;
    }

    protected function addDefaultAttributes($attributes)
    {
        return array_merge( $this->defaults, $attributes );
    }

    protected function filterImmutableAttributes($attributes)
    {
        foreach( $this->guards as $guard ) {
            if( array_key_exists( $guard, $attributes ) ) {
                unset( $attributes[ $guard ] );
            }
        }

        return $attributes;
    }

}
