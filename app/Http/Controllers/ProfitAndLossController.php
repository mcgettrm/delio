<?php


namespace App\Http\Controllers;

use App\Facades\ProfitAndLossFacade;

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
        //Are the inputs in a format my application can understand?

        return response()->json(['Working', 'json', 'response'], 200);
    }


}
