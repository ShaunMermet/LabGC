<?php
/**
 * UserFrosting (http://www.userfrosting.com)
 *
 * @link      https://github.com/userfrosting/UserFrosting
 * @license   https://github.com/userfrosting/UserFrosting/blob/master/licenses/UserFrosting.md (MIT License)
 */
namespace UserFrosting\Sprinkle\Site\Controller\Overrides;

use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Capsule\Manager as Capsule;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\NotFoundException;
use UserFrosting\Fortress\RequestDataTransformer;
use UserFrosting\Fortress\RequestSchema;
use UserFrosting\Fortress\ServerSideValidator;
use UserFrosting\Fortress\Adapter\JqueryValidationAdapter;
use UserFrosting\Sprinkle\Account\Database\Models\Group;
use UserFrosting\Sprinkle\Account\Database\Models\User;
use UserFrosting\Sprinkle\Account\Facades\Password;
use UserFrosting\Sprinkle\Core\Controller\SimpleController;
use UserFrosting\Sprinkle\Core\Facades\Debug;
use UserFrosting\Sprinkle\Core\Mail\EmailRecipient;
use UserFrosting\Sprinkle\Core\Mail\TwigMailMessage;
use UserFrosting\Support\Exception\BadRequestException;
use UserFrosting\Support\Exception\ForbiddenException;
use UserFrosting\Support\Exception\HttpException;

/**
 * Controller class for user-related requests, including listing users, CRUD for users, etc.
 *
 * @author Alex Weissman (https://alexanderweissman.com)
 */
class UserController extends SimpleController
{

    /**
     * Processes the request to update an existing user's basic details (first_name, last_name, email, locale, group_id)
     *
     * Processes the request from the user update form, checking that:
     * 1. The target user's new email address, if specified, is not already in use;
     * 2. The logged-in user has the necessary permissions to update the putted field(s);
     * 3. The submitted data is valid.
     * This route requires authentication.
     * Request type: PUT
     */
    public function updateInfo($request, $response, $args)
    {
        // Get the username from the URL
        $user = $this->getUserFromParams($args);

        if (!$user) {
            throw new NotFoundException($request, $response);
        }

        /** @var UserFrosting\Config\Config $config */
        $config = $this->ci->config;

        // Get PUT parameters
        $params = $request->getParsedBody();

        /** @var UserFrosting\Sprinkle\Core\MessageStream $ms */
        $ms = $this->ci->alerts;

        // Load the request schema
        $schema = new RequestSchema('schema://requests/user/edit-info.yaml');

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
            if ($name == 'first_name' || $name == 'last_name') {
                $fieldNames[] = 'name';
            } elseif ($name == 'group_id') {
                $fieldNames[] = 'group';
            } else {
                $fieldNames[] = $name;
            }
        }

        /** @var UserFrosting\Sprinkle\Account\Authorize\AuthorizationManager $authorizer */
        $authorizer = $this->ci->authorizer;

        /** @var UserFrosting\Sprinkle\Account\Database\Models\User $currentUser */
        $currentUser = $this->ci->currentUser;

        // Access-controlled resource - check that currentUser has permission to edit submitted fields for this user
        if (!$authorizer->checkAccess($currentUser, 'update_user_field', [
            'user' => $user,
            'fields' => array_values(array_unique($fieldNames))
        ])) {
            throw new ForbiddenException();
        }

        // Only the master account can edit the master account!
        if (
            ($user->id == $config['reserved_user_ids.master']) &&
            ($currentUser->id != $config['reserved_user_ids.master'])
        ) {
            throw new ForbiddenException();
        }

        /** @var UserFrosting\Sprinkle\Core\Util\ClassMapper $classMapper */
        $classMapper = $this->ci->classMapper;

        // Check if email already exists
        if (
            isset($data['email']) &&
            $data['email'] != $user->email &&
            $classMapper->staticMethod('user', 'findUnique', $data['email'], 'email')
        ) {
            $ms->addMessageTranslated('danger', 'EMAIL.IN_USE', $data);
            $error = true;
        }

        if ($error) {
            return $response->withStatus(400);
        }

        // Begin transaction - DB will be rolled back if an exception occurs
        Capsule::transaction( function() use ($data, $user, $currentUser) {
            // Update the user and generate success messages
            foreach ($data as $name => $value) {
                if ($value != $user->$name) {
                    if($name == 'group_id'){
                        $user->group()->sync($value);
                    }else {
                        $user->$name = $value;
                        $user->save();
                    }
                }
            }

            // Create activity record
            $this->ci->userActivityLogger->info("User {$currentUser->user_name} updated basic account info for user {$user->user_name}.", [
                'type' => 'account_update_info',
                'user_id' => $currentUser->id
            ]);
        });

        $ms->addMessageTranslated('success', 'DETAILS_UPDATED', [
            'user_name' => $user->user_name
        ]);
        return $response->withStatus(200);
    }

    protected function getUserFromParams($params)
    {
        // Load the request schema
        $schema = new RequestSchema('schema://requests/user/get-by-username.yaml');

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

        // Get the user to delete
        $user = $classMapper->staticMethod('user', 'where', 'user_name', $data['user_name'])
            ->first();

        return $user;
    }
}
