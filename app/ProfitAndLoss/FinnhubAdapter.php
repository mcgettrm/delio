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
     * FinnhubAdapter constructor.
     * @param string $token
     * @throws Exception
     */
    public function __construct(private string $token)
    {
        if(strlen($this->token) === 0){
            throw new Exception('No Finnhub key configured on the server.');
        }
    }

    /**
     * @param array $symbols
     * @param DateTime $date
     * @return array
     * @throws Exception
     */
    public function getStockDataForSymbols(array $symbols): array
    {
        $stockData = [];
        foreach($symbols as $symbol){
            $symbolData = $this->getPricesForSymbol($symbol);
            $cleanData = [];
            $cleanData['symbol'] = $symbol;
            $cleanData['current_value'] = $symbolData['c'];
            $cleanData['previous_day_close_value'] = $symbolData['pc'];
            $cleanData['effective_date'] = date('Y-m-d H:i:s', $symbolData['t']);
            $stockData[$symbol] = $cleanData;
        }

        //TODO::Return a DTO

        //For each symbol, should return the current price and yesterday's closing price.
        return $stockData;

    }

    /**
     * This is where the magic happens, get the current price and yesterday's closing price for a given symbol.
     * @param string $symbol
     * @return array
     * @throws Exception
     */
    public function getPricesForSymbol(string $symbol): array
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

        return $this->parseJson($response);
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
     * @param string $json
     * @return array
     */
    private function parseJson(string $json): array
    {
        $json = json_decode($json, true);
        return $json;
    }
}
