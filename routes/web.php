<?php

use Illuminate\Support\Facades\Route;
use App\export_Ngq;


// Music::truncate(); //clear existing data
// \DB::disableQueryLog(); //helps speed up queries by disabling log
ini_set('memory_limit', '512M'); //boost memory limit
ini_set('max_execution_time', '90'); //try to prevent time-out

Route::get('/', function () {
    return view('welcome');
});

//Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/ToMySql', function () {

    $file = "export_Ngq.xml";
    $reader = new XMLReader();
    // or  die("Failed to open xml file!");
    if (!$reader->open($file)) {
        die("Failed to open xml file!");
    }
    $doc = new DOMDocument;
    while ($reader->read() && $reader->name !== 'category');

    while ($reader->name === 'category') {
        $category1 = simplexml_import_dom($doc->importNode($reader->expand(), true));
        echo "</br> Id" . $category1['id'] . "parentId" . $category1['parentId'];
        $reader->next('category');
    } //end while

    $reader->close();
    return "Hello from Conversion";
});
