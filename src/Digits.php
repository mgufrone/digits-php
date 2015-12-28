<?php namespace Gufy\DigitsPhp;

use Gufy\DigitsPhp\Exceptions\InvalidServiceProviderException;
use Gufy\DigitsPhp\Exceptions\InvalidCredentialsException;
use \Closure;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ParseException;

/**
* @author Mochamad Gufron <mgufronefendi@gmail.com>
* @package DigitsPhp
*/
class Digits{
  private $serviceProvider;
  private $credentials;
  private $callbacks = [];

  /**
  * Initialize Digits Verification Class
  * @param string $service Service provider from digits.com, it usually comes in X-Auth-Service-Provider
  * @param string $credentials OAuth2 data from digits.com, it usually comes in X-Verify-Credentials-Authorization
  */
  public function __construct($service = "", $credentials = ""){
    if(empty($service))
      throw new InvalidServiceProviderException("Invalid Service Provider");

    if(empty($credentials))
      throw new InvalidCredentialsException("Invalid credentials");

    $this->serviceProvider = $service;
    $this->credentials = $credentials;
  }

  /**
  * @return string Return service provider from X-Auth-Service-Provider
  */
  public function getServiceProvider(){
    return $this->serviceProvider;
  }

  /**
  * @return string Return credentials from X-Verify-Credentials-Authorization
  */
  public function getCredentials(){
    return $this->credentials;
  }

  private function beforeExecute(&$client){
    foreach($this->callbacks as $callback){
      $callback($client);
    }
  }

  public function verify(){
    $client = new Client;
    $this->beforeExecute($client);
    try{
      $response = $client->post($this->getServiceProvider(),[
          'headers'=>[
              'Authorization'=>$this->getCredentials(),
          ]
      ]);
      return new DigitsResponse($response->json());
    }
    catch(ClientException $e){
      return new DigitsResponse($e->getResponse()->json());
    }
    catch(ParseException $e){
      print($e->getResponse()->getBody());
    }
  }

  public function setBeforeExecute(Closure $closure){
    $this->callbacks[] = $closure;
    return $this;
  }
}
