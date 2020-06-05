<?php

use Illuminate\Support\Facades\Route;
// use Illuminate\Support\Facades\DB;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/actors', 'ActorController@index');

Route::get('/group_by', function () {

  // Example group_by from tutorial
  // select
  // customer.customer_id, customer.first_name, customer.last_name, count(rental.rental_id) rentals_checked_out
  // from customer
  // left join rental
  // on customer.customer_id = rental.customer_id
  // group by customer.customer_id

    // dd('hello');
    $step1 = DB::table('customer')
    ->selectRaw('customer.customer_id, customer.first_name, customer.last_name')
    ->get();

    $step2 = DB::table('customer')
    ->selectRaw('customer.customer_id, customer.first_name, customer.last_name, rental.rental_id')
    ->leftJoin('rental', 'customer.customer_id', '=', 'rental.customer_id')
    ->where('customer.customer_id', '=', 1)
    // ->toSql();
    ->get();
    // ->groupBy('post_id', 'category_id');

    $step3Query = DB::table('customer')
    ->selectRaw('customer.customer_id, customer.first_name, customer.last_name, count(rental.rental_id) rentals_checked_out')
    ->leftJoin('rental', 'customer.customer_id', '=', 'rental.customer_id')
    ->groupBy('customer.customer_id', 'customer.first_name', 'customer.last_name');

    $step3Sql = $step3Query->toSql();
    $step3 = $step3Query->get();

    // dd($step3Sql, $step3);

    // Example subquery from MySql tutorial
    //STEP 1
    // select
    // customer.customer_id,
    // customer.first_name,
    // customer.last_name,
    // count(rental.rental_id) rentals_checked_out
    // from customer
    // left join rental
    // on customer.customer_id = rental.customer_id
    // left join address
    // on address.address_id = store.address_id
    // group by customer.customer_id

    //STEP 2 - replace store.address_id with a subquery
    // select
    // customer.customer_id,
    // customer.first_name,
    // customer.last_name,
    // count(rental.rental_id) rentals_checked_out
    // from customer
    // left join rental
    // on customer.customer_id = rental.customer_id
    // left join address
    // on address.address_id = (
    // select address_id from store where store.store_id = customer.store_id
    // ) as sub
    // group by customer.customer_id

    // tutorial subquery!
    // select address_id from store where store.store_id = customer.store_id
    // ) as sub
    // attempt #1
    $sub = DB::table('store')
    ->select('address_id')
    ->where('store.store_id', '=', 'customer.store_id');

    // $example2 = DB::table('customer')
    // ->selectRaw('customer.customer_id, customer.first_name, customer.last_name, count(rental.rental_id) rentals_checked_out')
    // ->leftJoin('rental', 'customer.customer_id', '=', 'rental.customer_id')
    // ->leftJoin('address', 'address.address_id', '=', 'rental.customer_id')
    // ->groupBy('customer.customer_id', 'customer.first_name', 'customer.last_name');

    // $exampl2Sql = $example2->toSql();
    // $step3 = $step3Query->get();
    // dd($example2Sql);

    // dd("({$sub->toSql()})");

    $example2Query = DB::table( DB::raw("({$sub->toSql()})") )
        ->mergeBindings($sub)
        ->selectRaw('customer.customer_id, customer.first_name, customer.last_name, count(rental.rental_id) rentals_checked_out')
        ->leftJoin('rental', 'customer.customer_id', '=', 'rental.customer_id')
        ->leftJoin('address', 'address.address_id', '=', 'rental.customer_id')
        ->groupBy('customer.customer_id', 'customer.first_name', 'customer.last_name');

    $example2Sql = $example2Query->toSql();
    $example2 = $example2Query->get();

    dd($exampl2Sql, $example2);

//Example from Medium blog
// final
// SELECT COUNT(post_id) AS low_perfomance_posts, category_id FROM
//     (SELECT post_id, category_id, status, report_date
//     AVG(performance_rating) AS avg_performance FROM post_analytics
//     WHERE status = 'active' and report_date >= DATE_SUB(NOW(),
//     INTERVAL 3 DAY) and user_id = 2 GROUP BY post_id, category_id) AS sub
// WHERE avg_performance < 20
// GROUP BY category_id

// subquery
// SELECT post_id, category_id, status, report_date
//     AVG(performance_rating) AS avg_performance
// FROM post_analytics
// WHERE status = 'active'
// AND report_date >= DATE_SUB(NOW(), INTERVAL 3 DAY)
// AND user_id = 2
// GROUP BY post_id, category_id

// $timeFrame = now()->subDay(3));

// $sub = DB::table('post_analytics')
//     ->selectRaw('post_id, category_id, status, AVG(performance_rating
//               AS avg_performance, )')
//     ->where('status', '=', 'enabled')
//     ->where('report_date', '>=', $timeFrame)
//     ->where('user_id', 2)
//     ->groupBy('post_id', 'category_id');

// $lowScoringPosts = DB::table( DB::raw("({$sub->toSql()})") )
//     ->mergeBindings($sub)
//     ->selectRaw('COUNT(post_id) AS low_scoring_posts, category_id')
//     ->where('avg_performance', '>=', 20)
//     ->groupBy('category_id')
//     ->get();

    // return view('actors.index', compact('actors'));
});




// Route::get('/actors', function () {
//
//     // dd('hello');
//     // $actors = DB::table('actor')->get();
//     $actors = DB::table('actor')->paginate(10);
//     // dd($actors);
//
//     return view('actors.index', compact('actors'));
// });
