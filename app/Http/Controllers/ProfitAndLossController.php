<?php


namespace App\Http\Controllers;

use App\ProfitAndLoss\ProfitAndLossFacade;
use Illuminate\Support\Facades\Log;

/**
 * The responsibility of this class is to facilitate the return of profit and loss data for stock market symbols between yesterday and today.
 * Class ProfitAndLossController
 * @package App\Http\Controllers
 */
class ProfitAndLossController extends Controller
{
    /** @var ProfitAndLossFacade $profitAndLossFacade */
    private $profitAndLossFacade;

    /**
     * ProfitAndLossController constructor.
     * @param ProfitAndLossFacade $profitAndLossFacade
     */
    public function __construct(ProfitAndLossFacade $profitAndLossFacade){
        $this->profitAndLossFacade = $profitAndLossFacade;
    }

    /**
     * @param array|string[] $symbols
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProfitAndLossSinceYesterday(array $symbols = ['MSFT','AAPL']): \Illuminate\Http\JsonResponse
    {

        //TODO:: SANITISE THOSE INPUTS!!
        try{
            $data = $this->profitAndLossFacade->retrievePersistAndReturnProfitAndLoss();
            $responseCode = 200;
        } catch(\Exception $exception){
            $data = [];
            //Assuming Server error for now
            $responseCode = 500;

            //Global static :( TODO:: is there a better way?
            Log::error($exception->getMessage());
        }
        return response()->json($data, $responseCode);
    }


}
