<?php
use Gufy\DigitsPhp\Digits;
use GuzzleHttp\Client;
use GuzzleHttp\Subscriber\Mock;
use GuzzleHttp\Message\Response;
use GuzzleHttp\Stream\Stream;

class DigitsPhpTest extends PHPUnit_Framework_TestCase{
  public function testCredentials(){
    $service = "https://api.digits.com/sdk/1/verify.json";
    $credentials = "longrandomkeyfromuser";
    $digits = new Digits($service, $credentials);
    $this->assertEquals($service, $digits->getServiceProvider());
    $this->assertEquals($credentials, $digits->getCredentials());
  }

  /**
  * @expectedException \Gufy\DigitsPhp\Exceptions\InvalidServiceProviderException
  * @expectedMessage Invalid service provider
  * @expectedCode \Gufy\DigitsPhp\Exceptions\InvalidServiceProviderException::ERROR_CODE
  */
  public function testServiceException(){
    $digits = new Digits();
  }

  /**
  * @expectedException \Gufy\DigitsPhp\Exceptions\InvalidCredentialsException
  * @expectedMessage Invalid credentials
  * @expectedCode \Gufy\DigitsPhp\Exceptions\InvalidCredentialsException::ERROR_CODE
  */
  public function testCredentialsException(){

    $service = "https://api.digits.com/sdk/1/verify.json";
    $digits = new Digits($service);
  }

  public function testVerify(){
    $service = "https://api.digits.com/sdk/1/verify.json";
    $credentials = "Random Credentials";
    $stream = Stream::factory('{
    "phone_number": "+62382984234",
    "email_address": {
        "address": "mgufronefendi@gmail.com",
        "is_verified": false
    },
    "access_token": {
        "token": "4634955913-yRMLrX6asZpMSEl6w7sBqJcz0wEtugsB35UAxAY",
        "secret": "ww6AqG59ttkcG3RhhyWqfvPgUOjEOM2p6krCspRFH0gJK"
    },
    "id_str": "4634955913",
    "verification_type": "sms",
    "id": 1293871289,
    "created_at": "Wed Dec 23 03:23:59 +0000 2015"
  }');

    $mock = new Mock([
      new Response(200, ['Content-Type'=>'application/json'], $stream),
    ]);
    $digits = new Digits($service, $credentials);
    $digits->setBeforeExecute(function($client) use($mock){
      $client->getEmitter()->attach($mock);
    });

    $response = $digits->verify();
    $this->assertInstanceOf("\\Gufy\\DigitsPhp\\DigitsResponse", $response);
    $this->assertEquals("+62382984234", $response->getPhoneNumber());
    $this->assertEquals("mgufronefendi@gmail.com", $response->getEmail());
    $this->assertEquals(false, $response->isEmailVerified());
    $this->assertEquals(true, $response->isSuccess());

  }

  public function testVerify(){
    $service = "https://api.digits.com/sdk/1/verify.json";
    $credentials = "Random Credentials";
    $stream = Stream::factory('{
    "phone_number": "+62382984234",
    "email_address": {
        "address": "mgufronefendi@gmail.com",
        "is_verified": false
    },
    "access_token": {
        "token": "4634955913-yRMLrX6asZpMSEl6w7sBqJcz0wEtugsB35UAxAY",
        "secret": "ww6AqG59ttkcG3RhhyWqfvPgUOjEOM2p6krCspRFH0gJK"
    },
    "id_str": "4634955913",
    "verification_type": "sms",
    "id": 1293871289,
    "created_at": "Wed Dec 23 03:23:59 +0000 2015"
  }');

    $mock = new Mock([
      new Response(200, ['Content-Type'=>'application/json'], $stream),
    ]);
    $digits = new Digits($service, $credentials);
    $digits->setBeforeExecute(function($client) use($mock){
      $client->getEmitter()->attach($mock);
    });

    $response = $digits->verify();
    $this->assertInstanceOf("\\Gufy\\DigitsPhp\\DigitsResponse", $response);
    $this->assertEquals("+62382984234", $response->getPhoneNumber());
    $this->assertEquals("mgufronefendi@gmail.com", $response->getEmail());
    $this->assertEquals(false, $response->isEmailVerified());
    $this->assertEquals(true, $response->isSuccess());

  }
}
