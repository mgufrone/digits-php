<?php namespace Gufy\DigitsPhp;

class DigitsResponse{
  private $phoneNumber;
  private $email;
  private $isEmailVerified;
  private $token;
  private $tokenSecret;
  private $id;
  private $idString;
  private $verificationType;
  private $createdAt;
  private $isSuccess;
  private $errorMessage;
  public function __construct($response){
    if(!empty($response["errors"])){
      $this->isSuccess = false;
      $this->errorMessage = $response["errors"];
      return $this;
    }
    $this->isSuccess = true;
    $this->id = $response["id"];
    $this->idString = $response["id_str"];
    $this->phoneNumber = $response["phone_number"];
    $this->email = $response["email_address"]["address"];
    $this->isEmailVerified = $response["email_address"]["is_verified"];
    $this->token = $response["access_token"]["token"];
    $this->tokenSecret = $response["access_token"]["secret"];
    $this->verificationType = $response["verification_type"];
    $this->createdAt = $response["created_at"];

  }

  public function isSuccess(){
    return $this->isSuccess;
  }
  public function getPhoneNumber(){
    return $this->phoneNumber;
  }
  public function getEmail(){
    return $this->email;
  }
  public function isEmailVerified(){
    return $this->isEmailVerified;
  }
  public function getErrorMessage(){
    return $this->errorMessage;
  }

}
