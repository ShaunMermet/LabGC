<?php
/**
 * UserFrosting (http://www.userfrosting.com)
 *
 * @link      https://github.com/userfrosting/UserFrosting
 * @license   https://github.com/userfrosting/UserFrosting/blob/master/licenses/UserFrosting.md (MIT License)
 */

//global $app;
//$config = $app->getContainer()->get('config');

//$app->get('/', 'UserFrosting\Sprinkle\Site\Controller\Overrides\CoreController:pageIndex')
//    ->add('checkEnvironment')
//    ->setName('index');

//$app->get('/about','UserFrosting\Sprinkle\Core\Controller\CoreController:pageAbout')->add('checkEnvironment');

//$app->get('/alerts', 'UserFrosting\Sprinkle\Core\Controller\CoreController:jsonAlerts');

//$app->get('/legal', 'UserFrosting\Sprinkle\Core\Controller\CoreController:pageLegal');

//$app->get('/privacy', 'UserFrosting\Sprinkle\Core\Controller\CoreController:pagePrivacy');

//$app->get('/' . $config['assets.raw.path'] . '/{url:.+}', 'UserFrosting\Sprinkle\Core\Controller\CoreController:getAsset');
$app->group('/hud', function () {
	$this->get('', 'UserFrosting\Sprinkle\Site\Controller\SiteController:pageHud');

})->add('authGuard');
$app->group('/operation/drone/all', function () {
	$this->get('', 'UserFrosting\Sprinkle\Site\Controller\SiteController:pageOperation');

})->add('authGuard');
$app->group('/operation/{operation_id}', function () {
	$this->get('', 'UserFrosting\Sprinkle\Site\Controller\SiteController:pageHud');

})->add('authGuard');
$app->group('/VR', function () {
	$this->get('', 'UserFrosting\Sprinkle\Site\Controller\SiteController:pageVR');

})->add('authGuard');

$app->group('/operation/drone/{drone_id}', function () {
	$this->get('', 'UserFrosting\Sprinkle\Site\Controller\SiteController:pageOperation');

})->add('authGuard');

$app->group('/api/drones', function () {
    
    $this->get('', 'UserFrosting\Sprinkle\Site\Controller\SiteController:getDroneSprunje');

})->add('authGuard');
$app->group('/api/droneOperations/{drone_id}', function () {
    
    $this->get('', 'UserFrosting\Sprinkle\Site\Controller\SiteController:getOperationsByDrone');

})->add('authGuard');
$app->group('/', function () {
	$this->get('', 'UserFrosting\Sprinkle\Site\Controller\Overrides\CoreController:pageIndex');

})->add('authGuard');
$app->group('/about', function () {
	$this->get('', 'UserFrosting\Sprinkle\Site\Controller\SiteController:pagePresentation');

});//->add('authGuard');
$app->group('/home', function () {
	$this->get('', 'UserFrosting\Sprinkle\Site\Controller\Overrides\CoreController:pageIndex');

})->add('authGuard');
$app->group('/drones', function () {
	$this->get('', 'UserFrosting\Sprinkle\Site\Controller\Overrides\CoreController:pageIndex');

})->add('authGuard');
$app->group('/data', function () {
	$this->get('', 'UserFrosting\Sprinkle\Site\Controller\SiteController:pageWIP');

})->add('authGuard');