<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
// use App\Repositories\UserRepository;

use DB;

class MainViewComposer
{
    /**
     * The user repository implementation.
     *
     * @var UserRepository
     */
    // protected $users;

    /**
     * Create a new profile composer.
     *
     * @param  UserRepository  $users
     * @return void
     */
    public function __construct()
    {
        // Dependencies automatically resolved by service container...
        // $this->users = $users;
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {


        // get users zipcode

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,"ipinfo.io/json?token=" . env('IPINFO_KEY'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $ipinfo = json_decode(curl_exec($ch));
        curl_close ($ch);

        // see if zipcode is in db and if it has been updated today
        // if zipcode is in db with a current date, use records from db


        // $data = DB::connection('sqlite')->select('*');



        // if zipcode is not in db or date is not current, update the database

        // then use records to build data for the view


        // $data = DB::connection('sqlite')->select('*');
        // dd($data);


        if (isset($ipinfo))
        {
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL,"api.openweathermap.org/data/2.5/forecast?zip=" . $ipinfo->postal . "&APPID=" . env('OW_KEY'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $weather_output = json_decode(curl_exec($ch));
            curl_close ($ch);

            dd($weather_output);

            // $view->with('ipinfo', $ipinfo);
            // $view->with('weather', $weather_output);

        } else {
            echo('failed');
        }

        // $view->ipinfo = curl ipinfo.io/json/token=$ENV(IPINFO_KEY);
        // $view->with('count', $this->users->count());
    }
}