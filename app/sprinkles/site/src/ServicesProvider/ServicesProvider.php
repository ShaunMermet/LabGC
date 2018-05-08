<?php
/**
 * UserFrosting (http://www.userfrosting.com)
 *
 * @link      https://github.com/userfrosting/UserFrosting
 * @copyright Copyright (c) 2013-2016 Alexander Weissman
 * @license   https://github.com/userfrosting/UserFrosting/blob/master/licenses/UserFrosting.md (MIT License)
 */
namespace UserFrosting\Sprinkle\Site\ServicesProvider;

use Birke\Rememberme\Authenticator as RememberMe;
use Illuminate\Database\Capsule\Manager as Capsule;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use UserFrosting\Sprinkle\Account\Authenticate\Authenticator;
use UserFrosting\Sprinkle\Account\Authenticate\AuthGuard;
use UserFrosting\Sprinkle\Account\Authorize\AuthorizationManager;
use UserFrosting\Sprinkle\Account\Log\UserActivityDatabaseHandler;
use UserFrosting\Sprinkle\Account\Log\UserActivityProcessor;
use UserFrosting\Sprinkle\Site\Model\User;
use UserFrosting\Sprinkle\Account\Repository\PasswordResetRepository;
use UserFrosting\Sprinkle\Account\Repository\VerificationRepository;
use UserFrosting\Sprinkle\Account\Twig\AccountExtension;
use UserFrosting\Sprinkle\Core\Facades\Debug;
use UserFrosting\Sprinkle\Core\Log\MixedFormatter;

/**
 * Registers services for the site sprinkle.
 *
 * @author Alex Weissman (https://alexanderweissman.com)
 */
class ServicesProvider
{
    /**
     * Register UserFrosting's site services.
     *
     * @param Container $container A DI container implementing ArrayAccess and container-interop.
     */
    public function register($container)
    {
        /**
         * Extend the 'classMapper' service to register model classes.
         *
         * Mappings added: User, Group, Role, Permission, Activity, PasswordReset, Verification
         */
        $container->extend('classMapper', function ($classMapper, $c) {
            $classMapper->setClassMapping('drone', 'UserFrosting\Sprinkle\Site\Model\Drone');
            $classMapper->setClassMapping('drone_sprunje', 'UserFrosting\Sprinkle\Site\Sprunje\DroneSprunje');
            $classMapper->setClassMapping('operation', 'UserFrosting\Sprinkle\Site\Model\Operation');
            $classMapper->setClassMapping('operation_sprunje', 'UserFrosting\Sprinkle\Site\Sprunje\OperationSprunje');
            $classMapper->setClassMapping('fleet', 'UserFrosting\Sprinkle\Site\Model\Fleet');
            $classMapper->setClassMapping('fleet_sprunje', 'UserFrosting\Sprinkle\Site\Sprunje\FleetSprunje');
            
            $classMapper->setClassMapping('user', 'UserFrosting\Sprinkle\Site\Database\Models\User');
            $classMapper->setClassMapping('user_sprunje', 'UserFrosting\Sprinkle\Site\Sprunje\UserSprunje');
            $classMapper->setClassMapping('group_sprunje', 'UserFrosting\Sprinkle\Site\Sprunje\GroupSprunjeLab');
            return $classMapper;
        });
    }
}
