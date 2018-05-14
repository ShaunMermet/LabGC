<?php
/**
 * UserFrosting (http://www.userfrosting.com)
 *
 * @link      https://github.com/userfrosting/UserFrosting
 * @license   https://github.com/userfrosting/UserFrosting/blob/master/licenses/UserFrosting.md (MIT License)
 */
namespace UserFrosting\Sprinkle\Site\Controller;

use UserFrosting\Sprinkle\Core\Controller\SimpleController;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\NotFoundException as NotFoundException;
use Illuminate\Database\Capsule\Manager as Capsule;
use UserFrosting\Fortress\RequestSchema;
use UserFrosting\Fortress\Adapter\JqueryValidationAdapter;
use UserFrosting\Fortress\RequestDataTransformer;
use UserFrosting\Fortress\ServerSideValidator;
use UserFrosting\Support\Exception\BadRequestException;


/**
 * CoreController Class
 *
 * Implements some common sitewide routes.
 * @author Alex Weissman (https://alexanderweissman.com)
 * @see http://www.userfrosting.com/navigating/#structure
 */
class DroneController extends SimpleController
{
    /**
     * Renders the modal form for creating a new drone.
     *
     * This does NOT render a complete page.  Instead, it renders the HTML for the modal, which can be embedded in other pages.
     * If the currently logged-in user has permission to modify drone group membership, then the group toggle will be displayed.
     * Otherwise, the drone will be added to the default group and receive the default roles/other automatically.
     * This page requires authentication.
     * Request type: GET
     */
    public function getModalCreate($request, $response, $args)
    {
        // GET parameters
        $params = $request->getQueryParams();

        /** @var UserFrosting\Sprinkle\Account\Authorize\AuthorizationManager $authorizer */
        $authorizer = $this->ci->authorizer;

        /** @var UserFrosting\Sprinkle\Account\Database\Models\User $currentUser */
        $currentUser = $this->ci->currentUser;

        /** @var UserFrosting\I18n\MessageTranslator $translator */
        $translator = $this->ci->translator;

        // Access-controlled page
    //    if (!$authorizer->checkAccess($currentUser, 'create_drone')) {
    //        throw new ForbiddenException();
    //    }

        /** @var UserFrosting\Sprinkle\Core\Util\ClassMapper $classMapper */
        $classMapper = $this->ci->classMapper;

        /** @var UserFrosting\Config\Config $config */
        $config = $this->ci->config;

        // Determine form fields to hide/disable
        // TODO: come back to this when we finish implementing theming
        $fields = [
            'hidden' => ['theme'],
            'disabled' => []
        ];

        // Get a list of all locales
        $locales = $config->getDefined('site.locales.available');

        // Determine if currentUser has permission to modify the group.  If so, show the 'group' dropdown.
        // Otherwise, set to the currentUser's group and disable the dropdown.
    //    if ($authorizer->checkAccess($currentUser, 'create_drone_field', [
    //        'fields' => ['group']
    //    ])) {
            // Get a list of all groups
    //        $groups = $classMapper->staticMethod('group', 'all');
    //    } else {
            // Get the current user's group
    //        $groups = $currentUser->group()->get();
    //        $fields['disabled'][] = 'group';
    //    }

        // Create a dummy drone to prepopulate fields
        $data = [
            //'group_id' => $currentUser->group_id,
            //'locale'   => $config['site.registration.user_defaults.locale'],
            //'theme'    => ''
        ];

        $drone = $classMapper->createInstance('drone', $data);

        // Get the current user's group/groups
        $fleets = [];
        $groups = $currentUser->group()->get();
        foreach ($groups as $group) {
            $groupfleets = $group->fleets()->get();
            foreach ($groupfleets as $fleet) {
                array_push($fleets, $fleet);
            }
        }
        

        // Get the current user's fleet
        //$fleets = $currentUser->fleet()->get();

        // Load validation rules
        $schema = new RequestSchema('schema://requests/drone/create.yaml');
        $validator = new JqueryValidationAdapter($schema, $this->ci->translator);

        return $this->ci->view->render($response, 'modals/drone.html.twig', [
            'drone' => $drone,
            'fleets' => $fleets,
            'locales' => $locales,
            'form' => [
                'action' => 'api/drones',
                'method' => 'POST',
                'fields' => $fields,
                'submit_text' => $translator->translate('CREATE')
            ],
            'page' => [
                'validators' => $validator->rules('json', false)
            ]
        ]);
    }

    /**
     * Processes the request to create a new drone.
     *
     * Processes the request from the group creation form, checking that:
     * 1. The drone name and slug are not already in use;
     * 2. The user has permission to create a new drone for given fleet;
     * 3. The submitted data is valid.
     * This route requires authentication ().
     * Request type: POST
     * @see getModalCreateGroup
     */
    public function create($request, $response, $args)
    {
        // Get POST parameters: name, slug, icon, description
        $params = $request->getParsedBody();

        /** @var UserFrosting\Sprinkle\Account\Authorize\AuthorizationManager $authorizer */
        $authorizer = $this->ci->authorizer;

        /** @var UserFrosting\Sprinkle\Account\Database\Models\User $currentUser */
        $currentUser = $this->ci->currentUser;

        // Access-controlled page
        //if (!$authorizer->checkAccess($currentUser, 'create_group')) {
        //    throw new ForbiddenException();
        //}

        /** @var UserFrosting\Sprinkle\Core\MessageStream $ms */
        $ms = $this->ci->alerts;

        // Load the request schema
        $schema = new RequestSchema('schema://requests/drone/create.yaml');

        // Whitelist and set parameter defaults
        $transformer = new RequestDataTransformer($schema);
        $data = $transformer->transform($params);

        $error = false;

        // Validate request data
        $validator = new ServerSideValidator($schema, $this->ci->translator);
        if (!$validator->validate($data)) {
            $ms->addValidationErrors($validator);
            $error = true;
        }

        /** @var UserFrosting\Sprinkle\Core\Util\ClassMapper $classMapper */
        $classMapper = $this->ci->classMapper;

        // Check if name or slug already exists
        if ($classMapper->staticMethod('drone', 'where', 'drone_name', $data['name'])->first()) {
            $ms->addMessageTranslated('danger', 'DRONE.NAME.IN_USE', $data);
            $error = true;
        }

        if ($classMapper->staticMethod('drone', 'where', 'drone_slug', $data['slug'])->first()) {
            $ms->addMessageTranslated('danger', 'GROUP.SLUG.IN_USE', $data);
            $error = true;
        }

        if ($error) {
            return $response->withStatus(400);
        }

        $data["drone_name"] = $data["name"];
        $data["drone_slug"] = $data["slug"];
        $data["ipv4"] = ip2long($data["ipv4"]);
        unset($data["name"]);
        unset($data["slug"]);
        
        //return $response->withStatus(200);;

        /** @var UserFrosting\Config\Config $config */
        $config = $this->ci->config;

        // All checks passed!  log events/activities and create drone
        // Begin transaction - DB will be rolled back if an exception occurs
        Capsule::transaction( function() use ($classMapper, $data, $ms, $config, $currentUser) {
            // Create the group
            $drone = $classMapper->createInstance('drone', $data);

            // Store new group to database
            $drone->save();

            // Create activity record
            $this->ci->userActivityLogger->info("User {$currentUser->user_name} created drone {$drone->name}.", [
                'type' => 'drone_create',
                'user_id' => $currentUser->id
            ]);

            $ms->addMessageTranslated('success', 'DRONE.CREATION_SUCCESSFUL', $data);
        });

        return $response->withStatus(200);
    }

    /**
     * Renders the modal form for editing an existing drone.
     *
     * This does NOT render a complete page.  Instead, it renders the HTML for the modal, which can be embedded in other pages.
     * This page requires authentication.
     * Request type: GET
     */
    public function getModalEdit($request, $response, $args)
    {
        // GET parameters
        $params = $request->getQueryParams();

        $drone = $this->getDroneFromParams($params);

        // If the group doesn't exist, return 404
        if (!$drone) {
            throw new NotFoundException($request, $response);
        }

        /** @var UserFrosting\Sprinkle\Core\Util\ClassMapper $classMapper */
        $classMapper = $this->ci->classMapper;

        /** @var UserFrosting\Sprinkle\Account\Authorize\AuthorizationManager $authorizer */
        $authorizer = $this->ci->authorizer;

        /** @var UserFrosting\Sprinkle\Account\Database\Models\User $currentUser */
        $currentUser = $this->ci->currentUser;

        /** @var UserFrosting\I18n\MessageTranslator $translator */
        $translator = $this->ci->translator;

        // Access-controlled resource - check that currentUser has permission to edit basic fields "name", "slug", "icon", "description" for this group
        //$fieldNames = ['name', 'slug', 'icon', 'description'];
        //if (!$authorizer->checkAccess($currentUser, 'update_group_field', [
        //    'group' => $group,
        //    'fields' => $fieldNames
        //])) {
        //    throw new ForbiddenException();
        //}

        // Generate form
        $fields = [
            'hidden' => [],
            'disabled' => []
        ];

        // Load validation rules
        $schema = new RequestSchema('schema://requests/drone/edit-info.yaml');
        $validator = new JqueryValidationAdapter($schema, $translator);

        // Get the current user's group/groups
        $fleets = [];
        $groups = $currentUser->group()->get();
        foreach ($groups as $group) {
            $groupfleets = $group->fleets()->get();
            foreach ($groupfleets as $fleet) {
                array_push($fleets, $fleet);
            }
        }

        return $this->ci->view->render($response, 'modals/drone.html.twig', [
            'drone' => $drone,
            'fleets' => $fleets,
            'form' => [
                'action' => "api/drones/d/{$drone->drone_slug}",
                'method' => 'PUT',
                'fields' => $fields,
                'submit_text' => $translator->translate('UPDATE')
            ],
            'page' => [
                'validators' => $validator->rules('json', false)
            ]
        ]);
    }

    protected function getDroneFromParams($params)
    {
        $params["slug"] = $params["drone_slug"];
        // Load the request schema
        $schema = new RequestSchema('schema://requests/drone/get-by-slug.yaml');

        // Whitelist and set parameter defaults
        $transformer = new RequestDataTransformer($schema);
        $data = $transformer->transform($params);
        
        // Validate, and throw exception on validation errors.
        $validator = new ServerSideValidator($schema, $this->ci->translator);
        if (!$validator->validate($data)) {
            // TODO: encapsulate the communication of error messages from ServerSideValidator to the BadRequestException
            $e = new BadRequestException();
            foreach ($validator->errors() as $idx => $field) {
                foreach($field as $eidx => $error) {
                    $e->addUserMessage($error);
                }
            }
            throw $e;
        }

        /** @var UserFrosting\Sprinkle\Core\Util\ClassMapper $classMapper */
        $classMapper = $this->ci->classMapper;

        // Get the group
        $drone = $classMapper->staticMethod('drone', 'where', 'drone_slug', $data['slug'])
            ->first();
        $drone->ipv4 = long2ip($drone->ipv4);
        return $drone;
    }

    /**
     * Processes the request to update an existing drone's details.
     *
     * Processes the request from the group update form, checking that:
     * 1. The drone name/slug are not already in use;
     * 2. The user has the necessary permissions to update the drone infos
     * 3. The submitted data is valid.
     * This route requires authentication (and should generally be limited to admins or the root user).
     * Request type: PUT
     * @see getModalGroupEdit
     */
    public function updateInfo($request, $response, $args)
    {
        // Get the group based on slug in URL
        $drone = $this->getDroneFromParams($args);

        if (!$drone) {
            throw new NotFoundException($request, $response);
        }

        /** @var UserFrosting\Config\Config $config */
        $config = $this->ci->config;

        // Get PUT parameters: (name, slug, icon, description)
        $params = $request->getParsedBody();

        /** @var UserFrosting\Sprinkle\Core\MessageStream $ms */
        $ms = $this->ci->alerts;

        // Load the request schema
        $schema = new RequestSchema('schema://requests/drone/edit-info.yaml');

        // Whitelist and set parameter defaults
        $transformer = new RequestDataTransformer($schema);
        $data = $transformer->transform($params);

        $error = false;

        // Validate request data
        $validator = new ServerSideValidator($schema, $this->ci->translator);
        if (!$validator->validate($data)) {
            $ms->addValidationErrors($validator);
            $error = true;
        }

        // Determine targeted fields
        $fieldNames = [];
        foreach ($data as $name => $value) {
            $fieldNames[] = $name;
        }

        /** @var UserFrosting\Sprinkle\Account\Authorize\AuthorizationManager $authorizer */
        $authorizer = $this->ci->authorizer;

        /** @var UserFrosting\Sprinkle\Account\Database\Models\User $currentUser */
        $currentUser = $this->ci->currentUser;

        // Access-controlled resource - check that currentUser has permission to edit submitted fields for this group
        //if (!$authorizer->checkAccess($currentUser, 'update_group_field', [
        //    'group' => $group,
        //    'fields' => array_values(array_unique($fieldNames))
        //])) {
        //    throw new ForbiddenException();
        //}

        /** @var UserFrosting\Sprinkle\Core\Util\ClassMapper $classMapper */
        $classMapper = $this->ci->classMapper;

        // Check if name or slug already exists
        if (
            isset($data['name']) &&
            $data['name'] != $group->name &&
            $classMapper->staticMethod('drone', 'where', 'drone_name', $data['name'])->first()
        ) {
            $ms->addMessageTranslated('danger', 'DRONE.NAME.IN_USE', $data);
            $error = true;
        }

        if (
            isset($data['slug']) &&
            $data['slug'] != $group->slug &&
            $classMapper->staticMethod('drone', 'where', 'drone_slug', $data['slug'])->first()
        ) {
            $ms->addMessageTranslated('danger', 'DRONE.SLUG.IN_USE', $data);
            $error = true;
        }

        if ($error) {
            return $response->withStatus(400);
        }

        $data["drone_name"] = $data["name"];
        $data["drone_slug"] = $data["slug"];
        $data["ipv4"] = ip2long($data["ipv4"]);
        unset($data["name"]);
        unset($data["slug"]);
        error_log("updateInfo data");
        error_log(print_r($data,true));

        // Begin transaction - DB will be rolled back if an exception occurs
        Capsule::transaction( function() use ($data, $drone, $currentUser) {
            // Update the group and generate success messages
            foreach ($data as $name => $value) {
                if ($value != $drone->$name) {
                    $drone->$name = $value;
                }
            }

            $drone->save();

            // Create activity record
            $this->ci->userActivityLogger->info("User {$currentUser->user_name} updated details for drone {$drone->drone_name}.", [
                'type' => 'drone_update_info',
                'user_id' => $currentUser->id
            ]);
        });

        $ms->addMessageTranslated('success', 'DRONE.UPDATE', [
            'name' => $drone->drone_name
        ]);

        return $response->withStatus(200);
    }
}
