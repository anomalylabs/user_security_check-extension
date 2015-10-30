<?php namespace Anomaly\UserSecurityCheckExtension;

use Anomaly\UsersModule\User\Contract\UserInterface;
use Anomaly\UsersModule\User\Security\SecurityCheckExtension;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UserSecurityCheckExtension
 *
 * @link          http://anomaly.is/streams-platform
 * @author        AnomalyLabs, Inc. <hello@anomaly.is>
 * @author        Ryan Thompson <ryan@anomaly.is>
 * @package       Anomaly\UserSecurityCheckExtension
 */
class UserSecurityCheckExtension extends SecurityCheckExtension
{

    /**
     * This extension provides a security check that
     * assures the user is active, enabled, etc.
     *
     * @var null|string
     */
    protected $provides = 'anomaly.module.users::security_check';

    /**
     * Check an HTTP request.
     *
     * @param UserInterface|null $user
     * @return bool|Response
     */
    public function check(UserInterface $user = null)
    {
        return parent::check($user);
    }

}
