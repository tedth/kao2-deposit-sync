<?php
namespace App\Libraries;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;
use Carbon\Carbon;
use Config;
class ScbDeposit {
    protected $endpoint = "http://localhost/";
    protected $apikey = "";
    protected $client;
    protected $headers = [];
    public function __construct()
    {
        $this->headers = [
            'Content-Type' => 'application/json'
        ];
        if(Config::get('services.scb.deposit.endpoint')){
            $this->endpoint = Config::get('services.scb.deposit.endpoint');
        }
        if(Config::get('services.scb.deposit.headers')){
            $this->headers = array_merge($this->headers,Config::get('services.scb.deposit.headers'));
        }
        $this->client = new Client([
            'base_uri' => $this->endpoint(),
            'headers' => $this->headers,
            'verify' => false
        ]);
    }
    public function endpoint($requestPath = '')
    {
        return $this->endpoint . $requestPath;
    }
    public function apikey()
    {
        return $this->apikey;
    }
    public function getBalance($accountNo)
    {
        $headers = array_merge($this->headers,[
            'accountNumber' => $accountNo
        ]);
        $request = $this->client->get('product-fulfillment/deposits/'.$accountNo, [
            'headers' => $headers,
            'verify' => false
        ]);
        return json_decode($request->getBody()->getContents());
    }
}
