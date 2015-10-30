<?php namespace Anomaly\UserSecurityCheckExtension\Command;

use Anomaly\Streams\Platform\Message\MessageBag;
use Anomaly\UsersModule\User\Contract\UserInterface;
use Anomaly\UsersModule\User\UserAuthenticator;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Routing\Redirector;

/**
 * Class CheckUser
 *
 * @link          http://anomaly.is/streams-platform
 * @author        AnomalyLabs, Inc. <hello@anomaly.is>
 * @author        Ryan Thompson <ryan@anomaly.is>
 * @package       Anomaly\UserSecurityCheckExtension\Command
 */
class CheckUser implements SelfHandling
{

    /**
     * The user instance.
     *
     * @var UserInterface
     */
    protected $user;

    /**
     * Create a new CheckUser instance.
     *
     * @param UserInterface $user
     */
    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }

    /**
     * @param UserAuthenticator $authenticator
     * @param MessageBag        $message
     * @param Redirector        $redirect
     * @return bool|\Illuminate\Http\RedirectResponse
     */
    public function handle(UserAuthenticator $authenticator, MessageBag $message, Redirector $redirect)
    {
        if (!$this->user->isActivated()) {

            $message->error('Your account has not been activated.');

            $authenticator->logout(); // Just in case.

            return $redirect->back();
        }

        if (!$this->user->isEnabled()) {

            $message->error('Your account has been disabled.');

            $authenticator->logout(); // Just in case.

            return $redirect->back();
        }

        return true;
    }
}
