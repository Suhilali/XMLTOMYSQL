<?php

use Illuminate\Support\Facades\Route;
use App\Models\MainCategory;


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

    MainCategory::truncate(); //clear existing data
    $file = "C:\\xampp\\htdocs\\ExportShop\\routes\\export_Ngq.xml";
    $reader = new XMLReader();
    // or  die("Failed to open xml file!");
    if (!$reader->open($file)) {
        die("Failed to open xml file!");
    }
    $doc = new DOMDocument;
    while ($reader->read() && $reader->name !== 'category');

    while ($reader->name === 'category') {
        $category1 = simplexml_import_dom($doc->importNode($reader->expand(), true));

        $mainCategory = new MainCategory();
        if ($category1['parentId'] == null) {
            $mainCategory->id = $category1['id'];
            $mainCategory->name = $category1;
            //$mainCategory->save();
            echo "</br> Id" . $mainCategory->id . "parentId" . $mainCategory->name;
        }
        // if ( $category1["parentId"] == "" ) {
        //     

        // }


        $reader->next('category');
    } //end while

    $reader->close();
    return "Hello from Conversion11111";
});
