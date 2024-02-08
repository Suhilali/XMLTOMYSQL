<?php

use Illuminate\Support\Facades\Route;
use App\Models\MainCategory;
use App\Models\Offer;
use App\Models\SubCategory1;
use App\Models\SubSubCategory;
use PHPUnit\TextUI\CliArguments\Mapper;
use Ramsey\Uuid\Type\Integer;

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
    SubCategory1::truncate();
    SubSubCategory::truncate();
    Offer::truncate();
    $file = "C:\\xampp\\htdocs\\ExportShop\\routes\\export_Ngq.xml";
    $reader = new XMLReader();
    // or  die("Failed to open xml file!");
    if (!$reader->open($file)) {
        die("Failed to open xml file!");
    }
    $doc = new DOMDocument;
    while ($reader->read() && $reader->name !== 'category');

    $index = 0;
    $M_Category = array();



    $SubCategory = [];


    while ($reader->name === 'category') {
        //$reader->name === 'category' or $reader->name === 'offer') {
        $category = simplexml_import_dom($doc->importNode($reader->expand(), true));

        //echo $category . "</br>";
        //$reader->read('category');
        $reader->next();
        if ($category['parentId'] == null) {

            $M_Category[$index] = "" . $category['id'];
            $Main_Category = new MainCategory();
            $Main_Category->name =  $category;
            $Main_Category->id = $category['id'];
            $index++;
            echo "Main_category id" . $Main_Category->id . $Main_Category->name . "</br>";
            $Main_Category->save();
        } else {

            $p = in_array($category['parentId'], $M_Category);
            //echo $category['parentId'] . "</br>";
            echo "index" . $index . "</br>";
            echo count($M_Category);
            //print("M_category"  . $M_Category[0] . "</br>");
            if ($p) {
                $Sub_Category = new SubCategory1();
                $Sub_Category->name =  $category;
                $Sub_Category->id = $category['id'];
                $Sub_Category->MainCategory_id = $category['parentId'];
                $Sub_Category->save();
                echo "sub_category" . $category['id'] . $Sub_Category->name . "</br>";
            } else {
                $Sub_Sub_Category = new SubSubCategory();
                $Sub_Sub_Category->name =  $category;
                $Sub_Sub_Category->id = $category['id'];
                $Sub_Sub_Category->SubCategory1_id = $category['parentId'];
                $Sub_Sub_Category->save();
                echo " sub_sub_category" . $category['id'] . "</br>";
            }
        }

        //$mainCategory = new MainCategory();
        // if ($category1['parentId'] == null) {
        //     $mainCategory->id = $category1['id'];
        //     $mainCategory->name = $category1;
        //     $idMainCategoryArray[$index] = $category1['id'];
        //     //$mainCategory->save();
        //     echo "</br> Id" . $mainCategory->id . "parentId" . $mainCategory->name;
        // }
        //$reader->next('category');
    } //end while


    //while ($reader->read() && $reader->name !== 'offer');
    while ($reader->read() && $reader->name !== 'offer') {
        echo 'hai fuckers';
    };




    while ($reader->name === 'offer') {

        $offer = simplexml_import_dom($doc->importNode($reader->expand(), true));
        $reader->next();

        $arrayOfOffer[] = [
            'identifier' => $offer['id'],
            'available' => true,
            'name'    => $offer->name,
            'url'     => $offer->url,
            'price'   => 0 + $offer->price,
            'oldprice' => 0 + $offer->oldprice,
            'currency_id'     => 0 + $offer->currency_id,
            'SubSubCategory_id'     =>  0 + $offer->SubSubCategory_id,
            'picture'     => $offer->picture,
            'vendor'  => $offer->vendor,

        ];
        // $Offer_ = new Offer();
        // $Offer_->identifier =  $offer['id'];
        // $Offer_->available = true; //$offer['available'];
        // $Offer_->name = $offer->name;
        // $Offer_->url = $offer->url;
        // $Offer_->price = 0 + $offer->price;
        // $Offer_->oldprice = 0 + $offer->oldprice;
        // $Offer_->currency_id = 0 + $offer->currency_id;
        // $Offer_->SubSubCategory_id = 0 + $offer->SubSubCategory_id;
        // $Offer_->picture = $offer->picture;
        // $Offer_->vendor = $offer->vendor;
        // $Offer_->save();
        //echo "i read" . $reader->name . "</br>";


    }




    $reader->close();
    if (!empty($arrayOfOffer)) {
        $arrayOfOfferChunked = array_chunk($arrayOfOffer, 50); //Chunk large array, in this example, chunked array will contains 30 items

        //loop the array and insert it use insert() function
        foreach ($arrayOfOfferChunked as $arrayOfOfferToSave) {
            Offer::insert($arrayOfOfferToSave);
            echo " Insert new bundle" . "</br>";
        }
    }
});
