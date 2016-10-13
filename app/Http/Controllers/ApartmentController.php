<?php

namespace App\Http\Controllers;

use App\Apartment;
use App\City;
use App\District;
use App\MetroStation;
use App\ReceivedApartment;
use App\Region;
use App\Street;
use Goutte\Client;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;

class ApartmentController extends Controller
{
    public function index()
    {
        $user = Sentinel::getUser();

        $apartments = Apartment::whereUserId($user->id)->get();

        if (count($apartments) == 0) {
            return redirect('create');
        }

        return View::make('apartments.index',[
                'apartments' => $apartments,
            ]
        );
    }

    public function create()
    {
        $regions = Region::all()->pluck('name', 'id');

        return View::make('apartments.create', [
            'regions' => $regions,
        ]);
    }

    public function store(Request $request)
    {
        $user = Sentinel::getUser();

        $this->validate($request, [
            'title' => 'required',
            'type' => 'required',
            'realty_id' => 'required',
            'customer' => 'required',
            'owner' => 'required',
            'agreement_id' => 'required',
            'realty_goal' => 'required',
            'region' => 'required',
            'city' => 'required',
            'house_number' => 'required',
            'apartment_number' => 'required',
            'square' => 'required',
            'floor' => 'required',
            'total_floor' => 'required',
            'rooms' => 'required',
        ]);

        $street = Street::firstOrCreate(['name' => preg_replace('/ +/', ' ', trim($request->street)), 'city_id' => $request->city]);

        $apartment = Apartment::register(
            trim($request->title),
            $request->type,
            trim($request->realty_id),
            trim($request->customer),
            trim($request->owner),
            trim($request->agreement_id),
            trim($request->realty_goal),
            $request->region,
            $request->city,
            trim($request->house_number),
            trim($request->apartment_number),
            trim($request->square),
            trim($request->floor),
            trim($request->total_floor),
            trim($request->rooms),
            $user->id
        );

        $apartment->district_id = $request->district;
        $apartment->street_id = $street->id;

        $apartment->save();

        return redirect('/');
    }

    public function parse($id)
    {
        $apartment = Apartment::find($id);
        $apartment->receivedApartments()->detach();

//        $this->parseOlx($apartment);
        $this->parseLun($apartment);

        return;
    }

    private function parseOlx(Apartment $apartment)
    {
        $options_alias = [
            'Количество комнат' => 'rooms',
            'Общая площадь' => 'total_square',
            'Жилая площадь' => 'living_square',
            'Площадь кухни' => 'kitchen_square',
            'Этаж' => 'floor',
            'Этажность дома' => 'total_floor',
        ];

        $url = $this->getOlxUrl($apartment);
        $total_page = 1;
        $current_page = 1;
        $page_limit = 2;

        while ($current_page <= $total_page && $current_page <= $page_limit) {
            $client = new Client();
            $crawler = $client->request('GET', $url . '&page=' . $current_page++);

            if ($crawler->filter('.pager')->count() > 0) {
                $total_page = trim($crawler->filter('.pager .item')->last()->text());
            }

            if ($crawler->filter('#offers_table .link.detailsLink')->count() > 0) {
                $crawler->filter('#offers_table .link.detailsLink')->each(function ($apartment_link) use ($client, $apartment, $options_alias) {
                    $link = $apartment_link->link();
                    $apartment_page = $client->click($link);
                    $apartment_options = [
                        'rooms' => '',
                        'total_square' => '',
                        'living_square' => '',
                        'kitchen_square' => '',
                        'floor' => '',
                        'total_floor' => '',
                    ];

                    preg_match('/[0-9]+:[0-9]+/', trim($apartment_page->filter('.offerheadinner p small.small')->text()), $matches);
                    $time = $matches[0];

                    preg_match('/[0-9]+ [а-я]+ [0-9]+/ui', trim($apartment_page->filter('.offerheadinner p small.small')->text()), $matches);
                    $month = [
                        'января' => '01',
                        'февраля' => '02',
                        'марта' => '03',
                        'апреля' => '04',
                        'мая' => '05',
                        'июня' => '06',
                        'июля' => '07',
                        'августа' => '08',
                        'сентября' => '09',
                        'октября' => '10',
                        'ноября' => '11',
                        'декабря' => '12',
                        ' ' => '.',
                    ];
                    $date = strtotime(str_replace(array_keys($month), array_values($month), trim($matches[0])) . ' ' . $time . ':00');

                    if ($apartment_page->filter('.details.full table.item')->count() > 0) {
                        $apartment_page->filter('.details.full table.item')->each(function ($option) use ($options_alias, &$apartment_options) {
                            $opt_title = trim($option->filter('th')->text());
                            $opt_val = trim($option->filter('strong')->text());

                            if (isset($options_alias[$opt_title])) {
                                $apartment_options[$options_alias[$opt_title]] = preg_replace("/ [^0-9]+[0-9]+/", '', trim($opt_val));
                            }
                        });
                    }

                    $apartment_data = [
                        'advert_id' => trim($apartment_page->filter('.offerheadinner p small.small .rel.inlblk')->text()),
                        'site' => 'olx',
                        'link' => trim($apartment_link->attr('href')),
                        'city_id' => $apartment->city_id,
                        'district_id' => $apartment->district_id,
                        'street_id' => $apartment->street_id,
                        'title' => trim($apartment_link->text()),
                        'date' => date('Y-m-d H:i:s', $date),
                        'type' => $apartment->type,
                        'rooms' => $apartment_options['rooms'],
                        'total_square' => $apartment_options['total_square'],
                        'living_square' => $apartment_options['living_square'],
                        'kitchen_square' => $apartment_options['kitchen_square'],
                        'floor' => $apartment_options['floor'],
                        'total_floor' => $apartment_options['total_floor'],
                        'price' => trim(preg_replace('/[^0-9]/', '', $apartment_page->filter('strong.xxxx-large')->text())),
                        'description' => trim($apartment_page->filter('#textContent .large')->text()),
                    ];

                    if (!$received_apartment = ReceivedApartment::where('site', '=', 'olx')->where('advert_id', '=', $apartment_data['advert_id'])->first()) {
                        $received_apartment = new ReceivedApartment();
                    }

                    $received_apartment->advert_id = $apartment_data['advert_id'];
                    $received_apartment->site = $apartment_data['site'];
                    $received_apartment->link = $apartment_data['link'];
                    $received_apartment->city_id = $apartment_data['city_id'];
                    $received_apartment->district_id = $apartment_data['district_id'];
                    $received_apartment->street_id = $apartment_data['street_id'];
                    $received_apartment->title = $apartment_data['title'];
                    $received_apartment->date = $apartment_data['date'];
                    $received_apartment->type = $apartment_data['type'];
                    $received_apartment->rooms = $apartment_data['rooms'];
                    $received_apartment->total_square = $apartment_data['total_square'];
                    $received_apartment->living_square = $apartment_data['living_square'];
                    $received_apartment->kitchen_square = $apartment_data['kitchen_square'];
                    $received_apartment->floor = $apartment_data['floor'];
                    $received_apartment->total_floor = $apartment_data['total_floor'];
                    $received_apartment->price = $apartment_data['price'];
                    $received_apartment->description = $apartment_data['description'];

                    $received_apartment->save();

                    $apartment->receivedApartments()->detach($received_apartment);
                    $apartment->receivedApartments()->attach($received_apartment);
                });
            }
        }

        return;
    }

    /**
     * [base_url]/[type_url]/[region/city]?/q-[search_query]?/[options]
     */
    private function getOlxUrl(Apartment $apartment)
    {
        $base_url = 'https://www.olx.ua/nedvizhimost/';
        $type_url = [
            'apartment' => 'prodazha-kvartir',
            'house' => 'prodazha-domov',
            'parcel' => 'prodazha-zemli',
            'garage' => 'prodazha-garazhey-stoyanok',
        ];
        $filters = [
            'rooms_from' => 'search[filter_float_number_of_rooms%3Afrom]',
            'rooms_to' => 'search[filter_float_number_of_rooms%3Ato]',
            'square_from' => 'search[filter_float_total_living_area%3Afrom]',
            'square_to' => 'search[filter_float_total_living_area%3Ato]',
            'floor_from' => 'search[filter_float_floor%3Afrom]',
            'floor_to' => 'search[filter_float_floor%3Ato]',
            'district_id' => 'search[district_id]',
        ];

        $options = [];

        if ($district = $apartment->district) {
            if (!empty($district->olx_id)) {
                $options[] = $filters['district_id'] . '=' . $district->olx_id;
            }
        }
        if (!empty($apartment->rooms)) {
            $options[] = $filters['rooms_from'] . '=' . $apartment->rooms;
            $options[] = $filters['rooms_to'] . '=' . $apartment->rooms;
        }
        if (!empty($apartment->floor)) {
            $options[] = $filters['floor_from'] . '=' . ($apartment->floor - 1);
            $options[] = $filters['floor_to'] . '=' . ($apartment->floor + 1);
        }
        if (!empty($apartment->square)) {
            $options[] = $filters['square_from'] . '=' . ($apartment->square - 5);
            $options[] = $filters['square_to'] . '=' . ($apartment->square + 5);
        }

        $url = $base_url . $type_url[$apartment->type] . '/' . $apartment->city->alias_olx . '/q-' . $apartment->street->name . '/?' . implode('&', $options);

        return $url;
    }

    public function parseLun(Apartment $apartment)
    {
        $url = $this->getLunUrl($apartment);

        $total_page = 1;
        $current_page = 1;
        $page_limit = 2;

        while ($current_page <= $total_page && $current_page <= $page_limit) {
            $client = new Client();
            $crawler = $client->request('GET', $url . '&page=' . $current_page++);

            if ($crawler->filter('.pagination')->count() > 0) {
                $total_page = trim($crawler->filter('.pagination .pagination__page')->last()->attr('data-value'));
            }

            if ($crawler->filter('#obj-left .obj')->count() > 0) {
                $crawler->filter('#obj-left .obj:not(.building-obj)')->each(function ($apartment_block) use ($client, $apartment) {
                    $apartment_data = [
                        'advert_id' => trim($apartment_block->filter('.obj-title a')->first()->attr('data-page-id')),
                        'site' => 'lun',
                        'link' => 'www.lun.ua' . trim($apartment_block->filter('.obj-title a')->first()->attr('href')),
                        'city_id' => $apartment->city_id,
                        'district_id' => $apartment->district_id,
                        'street_id' => $apartment->street_id,
                        'title' => trim($apartment_block->filter('.obj-title a')->first()->text()),
                        'date' => trim($apartment_block->filter('.last-time')->text()),
                        'type' => $apartment->type,
                        'rooms' => $apartment->rooms,
                        'total_square' => '',
                        'living_square' => '',
                        'kitchen_square' => '',
                        'floor' => '',
                        'total_floor' => '',
                        'price' => '',
                        'description' => '',
                    ];

                    if (preg_match('/[0-9.—]+[\s\/]+[0-9.—]+[\s\/]+[0-9.—]+/', trim($apartment_block->filter('.obj-params')->text()), $square)) {
                        $square = preg_split('/[\s\/]+/', $square[0]);
                        $apartment_data['total_square'] = trim($square[0]);
                        $apartment_data['living_square'] = trim($square[1]);
                        $apartment_data['kitchen_square'] = trim($square[2]);
                    }
                    dd($apartment_data);
                });
            }
die;
            if ($crawler->filter('.table-view .table-view_emulate__row')->count() > 0) {
                $crawler->filter('#offers_table .link.detailsLink')->each(function ($apartment_link) use ($client, $apartment, $options_alias) {

                });
            }
        }
die;
        return;
    }

    /**
     * [base_url]-[type]-[city]/[options]
     * @param Apartment $apartment
     */
    public function getLunUrl(Apartment $apartment)
    {
        $base_url = 'http://www.lun.ua/продажа';
        $type_url = [
            'apartment' => 'квартир',
            'house' => 'домов',
        ];
        $filters = [
            'rooms' => 'roomCount',
            'square_from' => 'areaTotalMin',
            'square_to' => 'areaTotalMax',
            'district' => 'district',
            'street' => 'street',
        ];

        $options = [];

        $street = $apartment->street;
        if (!empty($street) && !empty($street->lun_id)) {
            $options[] = $filters['street'] . '=' . $street->lun_id;
        } elseif ($district = $apartment->district) {
            $options[] = $filters['district'] . '=' . $district->lun_id;
        }
        if (!empty($apartment->rooms)) {
            $options[] = $filters['rooms'] . '=' . $apartment->rooms;
        }
        /*if (!empty($apartment->square)) {
            $options[] = $filters['square_from'] . '=' . ($apartment->square - 5);
            $options[] = $filters['square_to'] . '=' . ($apartment->square + 5);
        }*/

        $url = $base_url . '-' . $type_url[$apartment->type] . '-' . $apartment->city->alias_lun . '/?' . implode('&', $options);

        return $url;
    }

    public function setDistricts()
    {
        set_time_limit(0);
        $olx_url = 'https://www.olx.ua/nedvizhimost/prodazha-kvartir/';
        $lun_url = 'http://www.lun.ua/продажа-квартир-';

        $client = new Client();

        $cities = City::all();
        foreach ($cities as $city) {
            $crawler = $client->request('GET', $lun_url . $city->alias_lun);

            if ($crawler->filter('.dcheck')->count() > 0) {
                $crawler->filter('.dcheck')->each(function ($node) use ($client, $city) {
                    District::create([
                        'city_id' => $city->id,
                        'name' => trim($node->parents()->text()),
                        'lun_id' => $node->attr('value'),
                    ]);
                });
            }

            if ($crawler->filter('.scheck')->count() > 0) {
                $crawler->filter('.scheck')->each(function ($node) use ($client, $city) {
                    MetroStation::create([
                        'city_id' => $city->id,
                        'name' => trim($node->parents()->text()),
                        'lun_id' => $node->attr('value'),
                    ]);
                });
            }

            if ($crawler->filter('a.subscription-form-btn')->count() > 0) {
                $lun_city_id = json_decode($crawler->filter('a.subscription-form-btn')->first()->attr('data-subscription-params'), true);
                $city->lun_id = $lun_city_id['city'];
            }

            $crawler = $client->request('GET', $olx_url . $city->alias_olx);
            if ($crawler->filter('.locationlinks.margintop10 .link span')->count() > 0) {
                $crawler->filter('.locationlinks.margintop10 .link span')->each(function ($node) use ($client, $city) {
                    $district_link = $node->parents()->attr('href');
                    $district_id = explode('=', $district_link);
                    $district_id = end($district_id);
                    $name = trim($node->text());

                    if ($district = District::where('name', 'LIKE', '%' . $name . '%')->where('city_id', '=', $city->id)->first()) {
                        $district->olx_id = $district_id;
                        $district->save();
                    } else {
                        District::create([
                            'city_id' => $city->id,
                            'name' => $name,
                            'olx_id' => $district_id,
                        ]);
                    }
                });
            }

            $city->save();
        }
    }

    public function setStreets()
    {
        set_time_limit(0);
        $client = new Client();

        $cities = City::where('lun_id', '<>', 0)->get();
        foreach ($cities as $city) {
            Log::debug('Start city: ' . $city->name . '(' . $city->id . ')');
            foreach (range(chr(0xC0), chr(0xDF)) as $b) {
                $b = iconv('CP1251', 'UTF-8', $b);

                $url = 'http://www.lun.ua/ajax/street?geoEntity=city&geoId='.$city->lun_id.'&contractType=1&realtyType=1&q='.$b.'&limit=1000';
                $crawler = $client->request('GET', $url);

                if ($crawler->filter('head title')->count() > 0) {
                    continue(2);
                }

                $streets = explode(PHP_EOL, $crawler->text());
                if (is_array($streets)) {
                    foreach ($streets as $street_str) {
                        $street_arr = explode('|', $street_str);
                        if (count($street_arr) < 3) {
                            continue;
                        }
                        $name = preg_replace('/ +/', ' ', trim($street_arr[2]));
                        $lun_id = $street_arr[1];

                        if (!$check = Street::where('lun_id', '=', $lun_id)->first()) {
                            Street::create([
                                'city_id' => $city->id,
                                'name' => $name,
                                'lun_id' => $lun_id,
                            ]);
                        }
                    }
                }
            }
        }
    }
}
