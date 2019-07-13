<?php
class PayPal {
   private $isSandbox = True;
   private $sandboxAccount = "mathsoares1-facilitator@gmail.com";
   private $sandboxClientId = "AVbHebZar9BKVO3hDKvM2tbVZZssOGtUz-xOqkDtGwlqWy0KAJorM-4bFLh6CI9ZNsPUDYeSf40PZQIW";
   private $sandboxSecret = "EPE9L-vGc8olLb_hg4EqSSCS-kURURO1YlbSYRG435ZXQhWT57nRoDCT20gFZQ6MkRzJYF-4NQAV49nk";
   private $sandboxUrl = "https://api.sandbox.paypal.com";

   private $liveAccount = "";
   private $liveClientId = "";
   private $liveSecret = "";
   private $liveUrl = "https://api.paypal.com";

   private $account = "";
   private $clientId = "";
   private $secret = "";
   private $url = "";

   private $accessToken = "";

   private $endPointAccessToken = "/v1/oauth2/token";
   private $endPointCreateOrder = "/v1/checkout/orders";

   public $jsonPayment;

   public function __construct() {
      $this->init();
   }

   public function init() {
      if ($this->isSandbox == True) {
         $this->account = $this->sandboxAccount;
         $this->clientId = $this->sandboxClientId;
         $this->secret = $this->sandboxSecret;
         $this->url = $this->sandboxUrl;
      }
   }

   public function submeter($jsonPayment) {
      $authorization = "Bearer " . $this->accessToken;
      $url = $this->url.$this->endPointCreateOrder;

      $ch = curl_init($url);
      curl_setopt_array($ch, array(
         CURLOPT_POST => TRUE,
         CURLOPT_RETURNTRANSFER => TRUE,
         CURLOPT_HTTPHEADER => array(
            "Authorization: " . $authorization,
            "Content-Type: application/json",
            "Accept: application/json"
         ),
         CURLOPT_POSTFIELDS => $jsonPayment
      ));

      $result = curl_exec($ch);
      $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      curl_close($ch);

      if ($httpCode == "401") {
         $this->atribuirAccessToken();
         $this->submeter($jsonPayment);
      }

      $obj = json_decode($result,true);
      $link = $obj['links'][1]['href'];
      $openWindow = "<script>window.open('".$link."', '_self');</script>";
      echo $openWindow;
   }

   public function atribuirAccessToken() {
      $authorization = "Basic " . base64_encode($this->clientId . ":" . $this->secret);
      $url = $this->url.$this->endPointAccessToken;

      $ch = curl_init($url);
      curl_setopt_array($ch, array(
         CURLOPT_POST => TRUE,
         CURLOPT_RETURNTRANSFER => TRUE,
         CURLOPT_HTTPHEADER => array(
            'Authorization: '.$authorization,
            'Content-Type: application/x-www-form-urlencoded'
        ),
        CURLOPT_POSTFIELDS => "grant_type=client_credentials"
      ));

      $result = curl_exec($ch);
      curl_close($ch);

      $obj = json_decode($result,true);
      $this->accessToken = $obj['access_token'];
   }

}
?>
