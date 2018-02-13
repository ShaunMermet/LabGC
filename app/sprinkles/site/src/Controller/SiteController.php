<?php

namespace UserFrosting\Sprinkle\Site\Controller;

use UserFrosting\Sprinkle\Core\Controller\SimpleController;
use UserFrosting\Sprinkle\Account\Authenticate\Authenticator;
use UserFrosting\Support\Exception\ForbiddenException;
use UserFrosting\Sprinkle\Site\Model\Drone;

/**
 * Controller class for site-related requests.
 *
 * @author 
 */
class SiteController extends SimpleController
{
    /**
     * Renders the default hud page for labGC.
     *
     * By default, this is the page that non-authenticated users will first see when they navigate to your website's root.
     * Request type: GET
     */
    public function pageHud($request, $response, $args)
    {
        /** @var UserFrosting\Sprinkle\Account\Authenticate\Authenticator $authenticator */
        $authenticator = $this->ci->authenticator;
        if (!$authenticator->check()) {
            $loginPage = $this->ci->router->pathFor('login');
            return $response->withRedirect($loginPage, 400);
        }
        return $this->ci->view->render($response, 'pages/hud.html.twig');
    }

    /**
     * Renders the default VR page for labGC.
     *
     * By default, this is the page that non-authenticated users will first see when they navigate to your website's root.
     * Request type: GET
     */
    public function pageVR($request, $response, $args)
    {
        /** @var UserFrosting\Sprinkle\Account\Authenticate\Authenticator $authenticator */
        $authenticator = $this->ci->authenticator;
        if (!$authenticator->check()) {
            $loginPage = $this->ci->router->pathFor('login');
            return $response->withRedirect($loginPage, 400);
        }
        return $this->ci->view->render($response, 'pages/vr.html.twig');
    }



    /**
     * Returns a sprunje of Drones
     *
     * Generates a list of drones, optionally paginated, sorted and/or filtered.
     * This page requires authentication.
     * Request type: GET
     */
    public function getDroneSprunje($request, $response, $args)
    {
        // GET parameters
        $params = $request->getQueryParams();

        /** @var UserFrosting\Sprinkle\Account\Authorize\AuthorizationManager $authorizer */
        $authorizer = $this->ci->authorizer;

        /** @var UserFrosting\Sprinkle\Account\Database\Models\User $currentUser */
        $currentUser = $this->ci->currentUser;

        // Access-controlled page
        //if (!$authorizer->checkAccess($currentUser, 'uri_users')) {
        //    throw new ForbiddenException();
        //}

        /** @var UserFrosting\Sprinkle\Core\Util\ClassMapper $classMapper */
        $classMapper = $this->ci->classMapper;

        $sprunje = $classMapper->createInstance('drone_sprunje', $classMapper, $params);

        // Be careful how you consume this data - it has not been escaped and contains untrusted user-supplied content.
        // For example, if you plan to insert it into an HTML DOM, you must escape it on the client side (or use client-side templating).
        return $sprunje->toResponse($response);
    }

     /**
     * Renders the default operation page for labGC.
     *
     * By default, this is the page that non-authenticated users will first see when they navigate to your website's root.
     * Request type: GET
     */
    public function pageOperation($request, $response, $args)
    {
        /** @var UserFrosting\Sprinkle\Account\Authenticate\Authenticator $authenticator */
        $authenticator = $this->ci->authenticator;
        if (!$authenticator->check()) {
            $loginPage = $this->ci->router->pathFor('login');
            return $response->withRedirect($loginPage, 400);
        }
        error_log("operations");
        error_log(print_r($args,true));
        if($args){
            $drone = Drone::where ('id', '=', $args['drone_id'])->first();
        }else{
            $drone = new \stdClass();
            $drone->id= 'all';
            $drone->drone_name = 'all';
        }
        return $this->ci->view->render($response, 'pages/operation.html.twig', [
            "page" => [
                "validators" => [
                    "register" => 1
                ],
                "drone" => [
                    "id" => $drone->id,
                    "drone_name" => $drone->drone_name
                ]
            ]
        ]);
    }

    /**
     * Returns a sprunje of Operations
     *
     * Generates a list of operations by drone, optionally paginated, sorted and/or filtered.
     * This page requires authentication.
     * Request type: GET
     */
    public function getOperationsByDrone($request, $response, $args)
    {
        // GET parameters
        $params = $request->getQueryParams();
        error_log("operationbydrone");
        error_log(print_r($params,true));
        error_log(print_r($args,true));
        if($args['drone_id'] == 'all'){
            //no filter
        }
        else {
            $params['filters']['drone_id'] = $args['drone_id'];
        }
        /** @var UserFrosting\Sprinkle\Account\Authorize\AuthorizationManager $authorizer */
        $authorizer = $this->ci->authorizer;

        /** @var UserFrosting\Sprinkle\Account\Database\Models\User $currentUser */
        $currentUser = $this->ci->currentUser;

        // Access-controlled page
        //if (!$authorizer->checkAccess($currentUser, 'uri_users')) {
        //    throw new ForbiddenException();
        //}

        /** @var UserFrosting\Sprinkle\Core\Util\ClassMapper $classMapper */
        $classMapper = $this->ci->classMapper;

        $sprunje = $classMapper->createInstance('operation_sprunje', $classMapper, $params);

        // Be careful how you consume this data - it has not been escaped and contains untrusted user-supplied content.
        // For example, if you plan to insert it into an HTML DOM, you must escape it on the client side (or use client-side templating).
        return $sprunje->toResponse($response);
    }

    /**
     * Renders the default home page for Cosmos.
     *
     * By default, this is the page that non-authenticated users will first see when they navigate to your website's root.
     * Request type: GET
     */
    public function pagePresentation($request, $response, $args)
    {
        /** @var UserFrosting\Sprinkle\Account\Authenticate\Authenticator $authenticator */
        return $this->ci->view->render($response, 'pages/presentation.php');
    }

    /**
     * Renders the default VR page for labGC.
     *
     * By default, this is the page that non-authenticated users will first see when they navigate to your website's root.
     * Request type: GET
     */
    public function pageWIP($request, $response, $args)
    {
        /** @var UserFrosting\Sprinkle\Account\Authenticate\Authenticator $authenticator */
        $authenticator = $this->ci->authenticator;
        if (!$authenticator->check()) {
            $loginPage = $this->ci->router->pathFor('login');
            return $response->withRedirect($loginPage, 400);
        }
        return $this->ci->view->render($response, 'pages/wip.html.twig');
    }

}