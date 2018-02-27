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
use UserFrosting\Fortress\RequestSchema;
use UserFrosting\Fortress\Adapter\JqueryValidationAdapter;

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

        // Load validation rules
        $schema = new RequestSchema('schema://requests/drone/create.yaml');
        $validator = new JqueryValidationAdapter($schema, $this->ci->translator);

        return $this->ci->view->render($response, 'modals/drone.html.twig', [
            'drone' => $drone,
            //'groups' => $groups,
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
}
