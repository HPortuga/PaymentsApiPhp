<?php

require_once("paypal.php");

class ControlePagamento {
   private $paypal;

   public function __construct(){
      $this->paypal = new PayPal();
   }

   public function init() {
      if (isset($_GET['funcao'])) {
         $f = $_GET['funcao'];
     } else {
         $f = "";
     }

     switch ($f) {
         case "submeterPagamento":
            $this->submeterPagamento();
         break;
         default:
            $this->home();
         break;
     }
   }

   public function submeterPagamento() {
      if (isset($_POST["submeter"])) {
         $metodoDePagamento = $_POST["metodoPagamento"];
         $jsonPayment = $_POST["jsonPayment"];

         if ($metodoDePagamento == "paypal") {
            $this->paypal->submeter($jsonPayment);
         }
         else if ($metodoDePagamento == "pagseguro") {

         }
      }
   }

   public function home() {
      require "pagamento.html";
   }
}

?>