<?php


namespace App\Http\Controllers;

/**
 * The responsibility of this class is to facilitate the return of profit and loss data for stock market symbols between yesterday and today.
 * Class ProfitAndLossController
 * @package App\Http\Controllers
 */
class ProfitAndLossController extends Controller
{
    public function getProfitAndLossSinceYesterday(array $symbols = ['MSFT','AAPL']){
        return "Working controller";
    }
}
