<?php


namespace App\ProfitAndLoss;


use DateTime;
use Exception;

/**
 * The responsibility of this class is to wrap around the Finnhub API as a mockable adapter
 *
 *  finnhub.io/api/v1/quote?symbol=AAPL&token=
 * Class FinnhubAdapter
 * @package App\ProfitAndLoss
 */
class FinnhubAdapter
{
    /**
     * Base URL of the API endpoint
     * @var string $finnhubBaseUrl
     */
    private string $finnhubBaseUrl = "finnhub.io/api";

    /**
     * The identifier withinthe URL that indicates the API version to use
     * @var string $apiVersionPathString
     */
    private string $apiVersionPathString = "/v1";

    /**
     * Token loaded from .env files that provides access to Finnhub
     * @var string
     */
    private string $token = "";

    /**
     * FinnhubAdapter constructor.
     */
    public function __construct(){
        //TODO:: This should be injected.
        $this->token = env('FINNHUB_API_KEY');
        if(strlen($this->token) === 0){
            throw new Exception('No Finnhub key configured on the server.');
        }
    }

    /**
     * Generates the API base URL from the base URL and the API version
     * @return string
     */
    private function getAPIBase(): string
    {
        return 'https://'.$this->finnhubBaseUrl . $this->apiVersionPathString;
    }

    /**
     * @param array $symbols
     * @param DateTime $date
     * @return array
     * @throws Exception
     */
    public function getPricesForSymbols(array $symbols): array
    {
        $prices = [];
        foreach($symbols as $symbol){
            $prices[] = $this->getPricesForSymbol($symbol);
        }

        //TODO::Return a DTO

        //For each symbol, should return the current price and yesterday's closing price.
        return $prices;

    }

    /**
     * This is where the magic happens, get the current price and yesterday's closing price for a given symbol.
     * @param string $symbol
     * @return string
     * @throws Exception
     */
    public function getPricesForSymbol(string $symbol): string
    {

        $url = $this->getAPIBase()."/quote?symbol={$symbol}&token={$this->token}";
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);

        $headers = array(
            "Accept: application/json",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_VERBOSE, 1);

        $response= curl_exec($curl);
        $errorNumber = curl_errno($curl);
        if($response === false){

            $errorMessage = htmlspecialchars(curl_error($curl));

            curl_close($curl);

            throw new Exception("Curl threw an error: " . $errorNumber . " error message: " . $errorMessage);
        }

        curl_close($curl);

        return $response;
    }
}
