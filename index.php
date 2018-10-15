<?php

use Contentful\Delivery\Client;
use function GuzzleHttp\json_encode;

require_once __DIR__ . '/vendor/autoload.php';

$spaceID = 'ey5b5nmpiokh';
$accessToken = '6b55f9ebe7352c93f46a38da51216c7c987511ced19a82bc65eacfc02ea3d247';

$client = new \Contentful\Delivery\Client(
    $accessToken,
    $spaceID,
    $environmentId = 'rwds-master',
    $defaultLocale = null,
    $options = [
        'baseUri' => $baseUri = null,
        'guzzle' => $guzzle = null,
        'logger' => $logger = null,
        'cache' => $cache = null,
        'autoWarmup' => $autoWarmup = false,
    ]
);

// $contentType = $client->getContentType();

$entries = $client->getEntries();
foreach ($entries as $entry) {
    $dataJSON = json_encode($entry);
    $reverJSONToArray[] = json_decode($dataJSON);
}
// print_r($contentType);
foreach ($reverJSONToArray as $content_type) {
    $conType[] = $content_type->sys->contentType->sys->id;
}
$contentTypes = array_unique($conType);
$datass = array();
foreach ($contentTypes as $contentType) {
    foreach ($reverJSONToArray as $datas) {
        if ($datas->sys->contentType->sys->id == $contentType) {
            // $datass[$contentType] = $datas->fields;
            $image = $contentType . "Images";
            if (isset($datas->fields->$image)) {
                $finalImage = array();
                foreach ($datas->fields->$image as $images) {
                    // print_r($images);
                    // echo "<pre>";
                    // print_r(json_decode(json_encode($client->getAsset($images->sys->id))));

                    $imageDetail = json_decode(json_encode($client->getAsset($images->sys->id)));
                    $finalImage[] = array(
                        'imageTitle' => $imageDetail->fields->title ? : "",
                        'imageLink' => $imageDetail->fields->file->url ? : ""
                    );
                }
            }

            $title = $contentType . 'Title';
            $imgs = $contentType . 'Images';
            $name = $contentType . 'Name';
            $map = $contentType . 'Map';
            $capacity = $contentType . 'Capacity';
            $body = $contentType . 'Body';

            $datass[$contentType] = array(
                $contentType . 'Title' => property_exists($datas->fields, $title) ? $datas->fields->$title : "",
                $contentType . 'Images' => isset($finalImage) ? $finalImage : "",
                $contentType . 'Name' => property_exists($datas->fields, $name) ? $datas->fields->$name : "",
                $contentType . 'Map' => property_exists($datas->fields, $map) ? $datas->fields->$map : "",
                $contentType . 'Capacity' => property_exists($datas->fields, $capacity) ? $datas->fields->$capacity : "",
                $contentType . 'Body' => property_exists($datas->fields, $body) ? $datas->fields->$body : "",
            );

        }
    }
}
// echo "<pre>";
// print_r($datass);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Page Title</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>
    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-2" style="heigth:100vh;">
                        <div class="card-header" id="headingOne">
                        <?php foreach ($contentTypes as $vcType) { ?>
                        <h5 class="mb-0">
                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#<?php echo $vcType ?>Content" aria-expanded="true" aria-controls="<?php echo $vcType ?>Content">
                            <?php echo strtoupper($vcType); ?>
                            </button>
                        </h5>
                       <?php } ?>
                        </div>
                </div>
                <div class="col-sm-10" style="heigth:100vh;">
                <div class="accordion" id="accordionExample">
                    <div class="card">
                        <?php foreach($datass as $kData => $vData){ ?>
                            <div id="<?php echo $kData ?>Content" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                                <div class="card-body">
                                    <h1><?php echo strtoupper($vData[$kData.'Title']); ?></h1>
                                    <div class="row">
                                        <div class="col-sm-5">
                                        <?php if (is_array($vData[$kData.'Images'])) { ?>
                                            <div id="carouselExampleIndicators<?php echo $kData ?>" class="carousel slide" data-ride="carousel">
                                              <ol class="carousel-indicators">
                                                <?php $i=0; foreach( $vData[$kData.'Images'] as $vImages ) {  ?>
                                                    <li data-target="#carouselExampleIndicators<?php echo $kData ?>" data-slide-to="<?php echo $i; ?>" <?php if($i == 0){ echo "class='active'"; } ?>></li>
                                                <?php $i++; } ?>
                                              </ol>
                                              <div class="carousel-inner">
                                                <?php $j=0; foreach ($vData[$kData.'Images'] as $vImages) { ?>
                                                    <div class="carousel-item <?php if($j == 0){ echo "active"; } ?>">
                                                      <img class="d-block w-100" src="<?php echo $vImages['imageLink']; ?>" alt="<?php echo $vImages['imageTitle']; ?>">
                                                    </div>
                                                <?php $j++; } ?>
                                              </div>
                                              <a class="carousel-control-prev" href="#carouselExampleIndicators<?php echo $kData ?>" role="button" data-slide="prev">
                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                <span class="sr-only">Previous</span>
                                              </a>
                                              <a class="carousel-control-next" href="#carouselExampleIndicators<?php echo $kData ?>" role="button" data-slide="next">
                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                <span class="sr-only">Next</span>
                                              </a>
                                            </div>
                                        <?php  } ?>
                                        </div>
                                        <div class="col-sm-7">
                                            <?php echo $vData[$kData.'Body']; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                       <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>



