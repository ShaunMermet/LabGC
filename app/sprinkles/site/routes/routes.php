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

$app->group('/modals/drones', function () {
    //$this->get('/confirm-delete', 'UserFrosting\Sprinkle\Admin\Controller\UserController:getModalConfirmDelete');

    $this->get('/create', 'UserFrosting\Sprinkle\Site\Controller\DroneController:getModalCreate');

    //$this->get('/edit', 'UserFrosting\Sprinkle\Admin\Controller\UserController:getModalEdit');

    //$this->get('/password', 'UserFrosting\Sprinkle\Admin\Controller\UserController:getModalEditPassword');

    //$this->get('/roles', 'UserFrosting\Sprinkle\Admin\Controller\UserController:getModalEditRoles');
})->add('authGuard');
$app->group('/api/groups', function () {
    
    $this->get('/my', 'UserFrosting\Sprinkle\Site\Controller\Overrides\GroupController:getMyList');

})->add('authGuard');
$app->group('/api/users', function () {
    //$this->delete('/u/{user_name}', 'UserFrosting\Sprinkle\Admin\Controller\UserController:delete');

    //$this->get('', 'UserFrosting\Sprinkle\Admin\Controller\UserController:getList');

    //$this->get('/u/{user_name}', 'UserFrosting\Sprinkle\Admin\Controller\UserController:getInfo');

    //$this->get('/u/{user_name}/activities', 'UserFrosting\Sprinkle\Admin\Controller\UserController:getActivities');

    //$this->get('/u/{user_name}/roles', 'UserFrosting\Sprinkle\Admin\Controller\UserController:getRoles');

    //$this->get('/u/{user_name}/permissions', 'UserFrosting\Sprinkle\Admin\Controller\UserController:getPermissions');

   // $this->post('', 'UserFrosting\Sprinkle\Admin\Controller\UserController:create');

    //$this->post('/u/{user_name}/password-reset', 'UserFrosting\Sprinkle\Admin\Controller\UserController:createPasswordReset');

    $this->put('/u/{user_name}', 'UserFrosting\Sprinkle\Site\Controller\Overrides\UserController:updateInfo');

    //$this->put('/u/{user_name}/{field}', 'UserFrosting\Sprinkle\Admin\Controller\UserController:updateField');
})->add('authGuard');
