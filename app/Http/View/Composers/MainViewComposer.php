<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

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

        // see if zipcode data has been updated for today
        $today = date('Y-m-d');
        $current_data = DB::select('select * from locations where zipcode = ? and updated = ?', [$ipinfo->postal, $today]);

        // if zip has been updated today, use data from db
        // if zip has not been updated today, get it from api and insert into db
        if(count($current_data) > 0)
        {   
            $current_data[0]->details = DB::select('select * from events where zipcode = ? and updated = ?', [$ipinfo->postal, $today]);
            $data_for_view = $current_data[0];

        } else {

            // clean up old data
            DB::delete('delete from locations where zipcode = ?', [$ipinfo->postal]);
            DB::delete('delete from events where zipcode = ?', [$ipinfo->postal]);

            // get new data
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL,"api.openweathermap.org/data/2.5/forecast?zip=" . $ipinfo->postal . "&APPID=" . env('OW_KEY'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $weather_output = json_decode(curl_exec($ch));
            curl_close ($ch);

            $params = [
                    'id' => null,
                    'zipcode' => $ipinfo->postal,
                    'updated' => $today
               ];

            // insert current data for zipcode
            DB::insert('insert into locations (id, zipcode, updated) values (:id, :zipcode, :updated)', $params);

            foreach ($weather_output->list as $item) {
               
               $params = [
                    'dt' => $item->dt,
                    'dt_txt' => $item->dt_txt,
                    'main' => $item->weather[0]->main,
                    'desc' => $item->weather[0]->description,
                    'icon' => $item->weather[0]->icon,
                    'temp' => ($item->main->temp - 273.15) * 1.8000 + 32.00, //convert to F
                    'windspeed' => $item->wind->speed,
                    'winddirection' => $item->wind->deg,
                    'humidity' => $item->main->humidity,
                    'zipcode' => $ipinfo->postal,
                    'updated' => $today,
               ];

               DB::insert('insert into events (dt, dt_txt, main, desc, icon, temp, windspeed, winddirection, humidity, zipcode, updated) 
                           values (:dt, :dt_txt, :main, :desc, :icon, :temp, :windspeed, :winddirection, :humidity, :zipcode, :updated)',
                           $params);
            }

            $data_for_view = DB::select('select * from locations where zipcode = ? and updated = ?', [$ipinfo->postal, $today])[0];
            $data_for_view->details = DB::select('select * from events where zipcode = ? and updated = ?', [$ipinfo->postal, $today]);
            
        }

        // groups events into days
        $data_for_view->days = [];

        $current_date = date("m-d-Y", strtotime($data_for_view->details[0]->dt_txt));

        foreach ($data_for_view->details as $event) {

            $this_date = date("m-d-Y", strtotime($event->dt_txt));

            if($this_date == $current_date)
            {   
                $data_for_view->days[ $current_date ][] = $event;

            } else {
                
                $current_date = $this_date;
                $data_for_view->days[ $current_date ][] = $event;
            }

        }

        // add daily min max to each event
        foreach ($data_for_view->days as $day) {

            $min = 500;
            $max = 0;

            foreach($day as $event){
                if($event->temp < $min){
                    $min = $event->temp;
                }
                if($event->temp > $max){
                    $max = $event->temp;
                }
            }

            foreach($day as $event){
                $event->min_temp = $min;
                $event->max_temp = $max;
            }
        }

        dd($data_for_view);

        $view->with('ipinfo', $ipinfo);
        $view->with('weather_data', $data_for_view);


    }
}



