<?php
namespace App\Service;

class CurrencyConverterService{
    private $apiKey;
    private $urlAPI;
    private $toCurrency;
    private $fromCurrency;

    public function __construct(){
        $this->apiKey = $_ENV['CURRENCY_CONVERTER_API_KEY'];
        $this->urlAPI = $_ENV['CURRENCY_CONVERTER_URL_API'];
    }

    public function setToCurrency($toCurrency){
        $this->toCurrency = $toCurrency;
    }
    public function getToCurrency(){
        return $this->toCurrency;
    }

    public function setFromCurrency($fromCurrency){
        $this->fromCurrency = $fromCurrency;
    }
    public function getFromCurrency(){
        return $this->fromCurrencies;
    }

    private function isArrayFromCurrency(){
        return is_array($this->fromCurrency);
    }

    private function generateURLGetRequest($toCurrency, $fromCurrency){
        $dataArray = [
            'q'         => $toCurrency.'_'.$fromCurrency,
            'compact'   => 'ultra',
            'apiKey'    => $this->apiKey
        ];
        $urlData = http_build_query($dataArray);
        return $this->urlAPI.'?'.$urlData;
    }

    private function doGetRequest($url){
        $options = [
            CURLOPT_SSL_VERIFYPEER  => false,
            CURLOPT_FOLLOWLOCATION  => true,
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_FRESH_CONNECT   => true,
            CURLOPT_FORBID_REUSE    => true,
            CURLOPT_HEADER          => false,
            CURLOPT_TIMEOUT         => 80,
            CURLOPT_URL             => $url
        ];
        $ch = curl_init();
        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);
        $curlErrno = curl_errno($ch);
        $curlError = curl_error($ch);
        if($curlErrno > 0){
            curl_close($ch);
            throw new \Exception('cURL Error ('.$curlErrno.'): '.$curlError);
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if($httpCode != 200){
            curl_close($ch);
            throw new \Exception('Can\'t stablish service connection.');
        }

        curl_close($ch);
        $ch = null;

        $response = json_decode($response, true);
        if(!$response){
            throw new \Exception('JSON error conversion.');
        }
        return $response;
    }

    public function convertCurrency(){
        try{
            $response = [];
            if($this->isArrayFromCurrency()){
                foreach($this->fromCurrency as $fromCurrency){    
                    $url = $this->generateURLGetRequest($this->toCurrency, $fromCurrency);
                    $result = $this->doGetRequest($url);
                    $response = array_merge($response, $result);
                }
            }else{
                $url = $this->generateURLGetRequest($this->toCurrency, $this->fromCurrency);
                $result = $this->doGetRequest($url);
                $response = array_merge($response, $result);
            }
        }catch(\Exception $ex){
            $response = false;
        }
        return $response;
    }
}
