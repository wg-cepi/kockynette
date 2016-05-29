<?php

namespace App\Model;


use Nette;
use Nette\Security as NS;


class Authenticator extends Nette\Object implements NS\IAuthenticator
{
    /** @var \App\Model\Table\User  */
    private $user;

    function __construct(\App\Model\Table\User $user)
    {
        $this->user = $user;
    }

    function authenticate(array $credentials)
    {
        list($email, $password) = $credentials;
        $user = $this->user->where('email', $email)->fetch();

        if (!$user) {
            throw new NS\AuthenticationException('User not found.');
        }

        if (!password_verify($password, $user->password)) {
            throw new NS\AuthenticationException('Invalid password.');
        }

        return new NS\Identity($user->id, $user->role, array('email' => $user->email));
    }

}

