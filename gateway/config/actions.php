<?php

use toubeelib\application\actions\DeleteRdvId;
use toubeelib\application\actions\GetPatient;
use toubeelib\application\actions\GetPraticien;
use toubeelib\application\actions\GetRdvId;
use toubeelib\application\actions\PatchRdv;
use toubeelib\application\actions\PostCreateRdv;
use toubeelib\application\actions\PostSignIn;
use toubeelib\application\actions\SearchPraticien;
use toubeelib\core\services\praticien\ServicePraticienInterface;
use toubeelib\application\actions\GetDisposPraticien;
use toubeelib\application\actions\GetDisposPraticienDate;
use toubeelib\core\services\rdv\ServiceRDVInterface;


return [
    $guzzle = new GuzzleHttp\Client([
        // Base URI pour des requêtes relatives
        'base_uri' => 'localhost:6080',
    ]),

    GetAllPraticienAction::class => DI\autowire($guzzle),

    
    

];
