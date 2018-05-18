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
class MountpointController extends SimpleController
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

        // Load the request schema
        $schema = new RequestSchema('schema://requests/mountpoint/create.yaml');

        // Whitelist and set parameter defaults
        $transformer = new RequestDataTransformer($schema);
        $data = $transformer->transform($params);


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

        $mountpoint = $classMapper->createInstance('mountpoint', $data);
        

        // Load validation rules
        $schema = new RequestSchema('schema://requests/mountpoint/create.yaml');
        $validator = new JqueryValidationAdapter($schema, $this->ci->translator);

        return $this->ci->view->render($response, 'modals/mountpoint.html.twig', [
            'mountpoint' => $mountpoint,
            'locales' => $locales,
            'form' => [
                'action' => 'api/mountpoints',//trigger create function
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
        $schema = new RequestSchema('schema://requests/mountpoint/create.yaml');

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

        if ($error) {
            return $response->withStatus(400);
        }

        /** @var UserFrosting\Config\Config $config */
        $config = $this->ci->config;

        // All checks passed!  log events/activities and create drone
        // Begin transaction - DB will be rolled back if an exception occurs
        Capsule::transaction( function() use ($classMapper, $data, $ms, $config, $currentUser) {
            // Create the group
            $mountpoint = $classMapper->createInstance('mountpoint', $data);

            // Store new mountpoint to database
            $mountpoint->save();

            // Create activity record
            $this->ci->userActivityLogger->info("User {$currentUser->user_name} created mountpoint {$mountpoint->name}.", [
                'type' => 'mountpoint_create',
                'user_id' => $currentUser->id
            ]);

            $ms->addMessageTranslated('success', 'MOUNTPOINT.CREATION_SUCCESSFUL', $data);
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

        $mountpoint = $this->getMountpointFromParams($params);

        // If the group doesn't exist, return 404
        if (!$mountpoint) {
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
        $schema = new RequestSchema('schema://requests/mountpoint/edit-info.yaml');
        $validator = new JqueryValidationAdapter($schema, $translator);


        return $this->ci->view->render($response, 'modals/mountpoint.html.twig', [
            'mountpoint' => $mountpoint,
            //'fleets' => $fleets,
            'form' => [
                'action' => "api/mountpoints/m/{$mountpoint->id}",//Trigger updateInfo
                'method' => 'PUT',
                'fields' => $fields,
                'submit_text' => $translator->translate('UPDATE')
            ],
            'page' => [
                'validators' => $validator->rules('json', false)
            ]
        ]);
    }

    protected function getMountpointFromParams($params)
    {
        // Load the request schema
        $schema = new RequestSchema('schema://requests/mountpoint/get-by-id.yaml');

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

        // Get the mountpoint
        $mountpoint = $classMapper->staticMethod('mountpoint', 'where', 'id', $data['id'])
            ->first();
        return $mountpoint;
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
        $mountpoint = $this->getMountpointFromParams($args);

        if (!$mountpoint) {
            throw new NotFoundException($request, $response);
        }

        /** @var UserFrosting\Config\Config $config */
        $config = $this->ci->config;

        // Get PUT parameters: (name, slug, icon, description)
        $params = $request->getParsedBody();

        /** @var UserFrosting\Sprinkle\Core\MessageStream $ms */
        $ms = $this->ci->alerts;

        // Load the request schema
        $schema = new RequestSchema('schema://requests/mountpoint/edit-info.yaml');

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

        // Begin transaction - DB will be rolled back if an exception occurs
        Capsule::transaction( function() use ($data, $mountpoint, $currentUser) {
            // Update the group and generate success messages
            foreach ($data as $name => $value) {
                if ($value != $mountpoint->$name) {
                    $mountpoint->$name = $value;
                }
            }

            $mountpoint->save();

            // Create activity record
            $this->ci->userActivityLogger->info("User {$currentUser->user_name} updated details for mountpoint {$mountpoint->name}.", [
                'type' => 'mountpoint_update_info',
                'user_id' => $currentUser->id
            ]);
        });

        $ms->addMessageTranslated('success', 'MOUNTPOINT.UPDATE', [
            'name' => $mountpoint->name
        ]);

        return $response->withStatus(200);
    }


    public function getModalConfirmDelete($request, $response, $args)
    {
        // GET parameters
        $params = $request->getQueryParams();

        $mountpoint = $this->getMountpointFromParams($params);

        // If the group no longer exists, forward to main group listing page
        if (!$mountpoint) {
            throw new NotFoundException($request, $response);
        }

        /** @var UserFrosting\Sprinkle\Account\Authorize\AuthorizationManager $authorizer */
        $authorizer = $this->ci->authorizer;

        /** @var UserFrosting\Sprinkle\Account\Database\Models\User $currentUser */
        $currentUser = $this->ci->currentUser;

        // Access-controlled page
        /*if (!$authorizer->checkAccess($currentUser, 'delete_group', [
            'group' => $group
        ])) {
            throw new ForbiddenException();
        }*/

        /** @var UserFrosting\Sprinkle\Core\Util\ClassMapper $classMapper */
        $classMapper = $this->ci->classMapper;

        return $this->ci->view->render($response, 'modals/confirm-delete-mountpoint.html.twig', [
            'mountpoint' => $mountpoint,
            'form' => [
                'action' => "api/mountpoints/m/{$mountpoint->id}",//trigger delete
            ]
        ]);
    }

    /**
     * Processes the request to delete an existing stream/mountpoint.
     *
     * Deletes the specified stream, removing any existing associations.
     * Before doing so, checks that:
     * 1. You have permission to delete the target user's account.
     * This route requires authentication
     * Request type: DELETE
     */
    public function delete($request, $response, $args)
    {
        $mountpoint = $this->getMountpointFromParams($args);

        // If the mountpoint doesn't exist, return 404
        if (!$mountpoint) {
            throw new NotFoundException($request, $response);
        }

        /** @var UserFrosting\Sprinkle\Account\Authorize\AuthorizationManager $authorizer */
        $authorizer = $this->ci->authorizer;

        /** @var UserFrosting\Sprinkle\Account\Database\Models\User $currentUser */
        $currentUser = $this->ci->currentUser;

        // Access-controlled page
        /*if (!$authorizer->checkAccess($currentUser, 'delete_user', [
            'user' => $user
        ])) {
            throw new ForbiddenException();
        }*/

        /** @var UserFrosting\Config\Config $config */
        $config = $this->ci->config;

        // Check that we are not deleting the master account
        // Need to use loose comparison for now, because some DBs return `id` as a string
        if ($user->id == $config['reserved_user_ids.master']) {
            $e = new BadRequestException();
            $e->addUserMessage('DELETE_MASTER');
            throw $e;
        }

        $mountpointName = $mountpoint->name;
        $mountpointId = $mountpoint->id;

        // Begin transaction - DB will be rolled back if an exception occurs
        Capsule::transaction( function() use ($mountpoint, $mountpointName, $mountpointId, $currentUser) {
            $mountpoint->delete();
            unset($mountpoint);

            // Create activity record
            $this->ci->userActivityLogger->info("User {$currentUser->user_name} deleted the mountpoint for {$mountpointName} {$mountpointId}.", [
                'type' => 'mountpoint_delete',
                'user_id' => $currentUser->id
            ]);
        });

        /** @var UserFrosting\Sprinkle\Core\MessageStream $ms */
        $ms = $this->ci->alerts;

        $ms->addMessageTranslated('success', 'MOUNTPOINT.DELETION_SUCCESSFUL', [
            'mountpoint_name' => $mountpointName
        ]);

        return $response->withStatus(200);
    }
}
