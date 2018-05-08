<?php

namespace UserFrosting\Sprinkle\Site\Controller;

use UserFrosting\Sprinkle\Core\Controller\SimpleController;
use UserFrosting\Sprinkle\Account\Authenticate\Authenticator;
use UserFrosting\Support\Exception\ForbiddenException;
use UserFrosting\Sprinkle\Site\Model\Drone;
use UserFrosting\Sprinkle\Site\Model\Fleet;

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
     * Returns a sprunje of Drones
     *
     * Generates a list of drones, optionally paginated, sorted and/or filtered.
     * This page requires authentication.
     * Request type: GET
     */
    public function getMyDroneSprunje($request, $response, $args)
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

        $UserWGrp = $classMapper->staticMethod('user', 'where', 'id', $currentUser->id)
                                ->with('group')
                                ->first();

        $validFleet = [];
        foreach ($UserWGrp->group as $group) {
            $fleets = Fleet::where('group_id', '=', $group->id)
                    ->get();
            foreach ($fleets as $fleet) {
                array_push($validFleet, $fleet->id);
            }
        }

        $validDrones = [];
        $drones = Drone::whereIn('fleet_id', $validFleet)
                    ->get();
        foreach ($drones as $drone) {
            array_push($validDrones, $drone->id);
        }

        //filter
        $params['filters']['my_drones'] = $validDrones;

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

    public function pageData($request, $response, $args)
    {
        /** @var UserFrosting\Sprinkle\Account\Authenticate\Authenticator $authenticator */
        $authenticator = $this->ci->authenticator;
        if (!$authenticator->check()) {
            $loginPage = $this->ci->router->pathFor('login');
            return $response->withRedirect($loginPage, 400);
        }
        return $this->ci->view->render($response, 'pages/data.html.twig');
    }

    /**
     * Renders the fleet listing page.
     *
     * This page renders a table of fleets, with dropdown menus for admin actions for each fleet.
     * Actions typically include: edit fleet, delete fleet.
     * This page requires authentication.
     * Request type: GET
     */
    public function fleetList($request, $response, $args)
    {
        /** @var UserFrosting\Sprinkle\Account\Authorize\AuthorizationManager $authorizer */
        $authorizer = $this->ci->authorizer;

        /** @var UserFrosting\Sprinkle\Account\Database\Models\User $currentUser */
        $currentUser = $this->ci->currentUser;

        // Access-controlled page
        if (!$authorizer->checkAccess($currentUser, 'uri_fleets')) {
            throw new ForbiddenException();
        }

        return $this->ci->view->render($response, 'pages/fleets.html.twig');
    }
     /**
     * Renders a page displaying a fleet's information, in read-only mode.
     *
     * This checks that the currently logged-in user has permission to view the requested fleet's info.
     * It checks each field individually, showing only those that you have permission to view.
     * This will also try to show buttons for deleting, and editing the group.
     * This page requires authentication.
     * Request type: GET
     */
    public function fleetInfo($request, $response, $args)
    {
        $group = $this->getGroupFromParams($args);

        // If the group no longer exists, forward to main group listing page
        if (!$group) {
            $redirectPage = $this->ci->router->pathFor('uri_groups');
            return $response->withRedirect($redirectPage, 404);
        }

        /** @var UserFrosting\Sprinkle\Account\Authorize\AuthorizationManager $authorizer */
        $authorizer = $this->ci->authorizer;

        /** @var UserFrosting\Sprinkle\Account\Database\Models\User $currentUser */
        $currentUser = $this->ci->currentUser;

        // Access-controlled page
        if (!$authorizer->checkAccess($currentUser, 'uri_group', [
                'group' => $group
            ])) {
            throw new ForbiddenException();
        }

        // Determine fields that currentUser is authorized to view
        $fieldNames = ['name', /*'slug', 'icon',*/ 'description'];

        // Generate form
        $fields = [
            'hidden' => []
        ];

        foreach ($fieldNames as $field) {
            if (!$authorizer->checkAccess($currentUser, 'view_group_field', [
                'group' => $group,
                'property' => $field
            ])) {
                $fields['hidden'][] = $field;
            }
        }

        // Determine buttons to display
        $editButtons = [
            'hidden' => []
        ];

        if (!$authorizer->checkAccess($currentUser, 'update_group_field', [
            'group' => $group,
            'fields' => ['name', 'slug', 'icon', 'description']
        ])) {
            $editButtons['hidden'][] = 'edit';
        }

        if (!$authorizer->checkAccess($currentUser, 'delete_group', [
            'group' => $group
        ])) {
            $editButtons['hidden'][] = 'delete';
        }

        return $this->ci->view->render($response, 'pages/group.html.twig', [
            'group' => $group,
            'fields' => $fields,
            'tools' => $editButtons
        ]);
    }

    /**
     * Returns a list of Fleets (own anly)
     *
     * Generates a list of fleets, optionally paginated, sorted and/or filtered.
     * This page requires authentication.
     * Request type: GET
     */
    public function getFleetList($request, $response, $args)
    {
        // GET parameters
        $params = $request->getQueryParams();

        /** @var UserFrosting\Sprinkle\Account\Authorize\AuthorizationManager $authorizer */
        $authorizer = $this->ci->authorizer;

        /** @var UserFrosting\Sprinkle\Account\Database\Models\User $currentUser */
        $currentUser = $this->ci->currentUser;

        // Access-controlled page
        if (!$authorizer->checkAccess($currentUser, 'uri_fleets')) {
            throw new ForbiddenException();
        }

        /** @var UserFrosting\Sprinkle\Core\Util\ClassMapper $classMapper */
        $classMapper = $this->ci->classMapper;

        $UserWGrp = $classMapper->staticMethod('user', 'where', 'id', $currentUser->id)
                                ->with('group')
                                ->first();

        $validFleet = [];
        foreach ($UserWGrp->group as $group) {
            $fleets = Fleet::where('group_id', '=', $group->id)
                    ->get();
            foreach ($fleets as $fleet) {
                array_push($validFleet, $fleet->id);
            }
        }

        //filter
        $params['filters']['my_fleets'] = $validFleet;

        $sprunje = $classMapper->createInstance('fleet_sprunje', $classMapper, $params);

        // Be careful how you consume this data - it has not been escaped and contains untrusted user-supplied content.
        // For example, if you plan to insert it into an HTML DOM, you must escape it on the client side (or use client-side templating).
        return $sprunje->toResponse($response);
    }
}