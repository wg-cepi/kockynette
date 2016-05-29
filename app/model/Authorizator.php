<?php

namespace App\Model;

use Nette;
use Nette\Security\Permission;

/**
 * Class Authorizator
 * @package Model
 */
class Authorizator implements Nette\Security\IAuthorizator
{

    private $acl;

    const CREATE = 'create';
    const READ = 'read';
    const UPDATE = 'update';
    const DELETE = 'delete';


    public function __construct()
    {
        $acl = new Nette\Security\Permission();
        // definice rolí
        $acl->addRole('guest');
        $acl->addRole('user', 'guest'); // demo dědí od guest
        $acl->addRole('admin', 'user'); // a od něj dědí admin
        // seznam zdrojů, ke kterým mohou uživatelé přistupovat

        $acl->addResource('Admin:Depozitum');
        $acl->addResource('Admin:Cat');
        $acl->addResource('Admin:Color');
        $acl->addResource('Admin:Contact');
        $acl->addResource('Common:Homepage');


        // pravidla, určující, kdo co může s čím dělat
        $acl->allow('guest', Permission::ALL, self::READ);

        $acl->allow('user', Permission::ALL, self::READ);

        $acl->allow('admin', Permission::ALL, Permission::ALL);
        // Nastaveno!
        $this->acl = $acl;
    }

    /**
     * @param $role
     * @param $resource
     * @param $privilege
     *
     * @return bool
     */
    public function isAllowed($role, $resource, $privilege)
    {
        return $this->acl->isAllowed($role, $resource, $privilege);
    }

    /**
     * @param $role
     * @param Nette\Security\User $user
     *
     * @return bool
     */
    public function isAtLeastInRole($role, Nette\Security\User $user)
    {
        $result = TRUE;
        foreach ($user->getRoles() as $userRole) {
            if ($userRole === $role) {
                return TRUE;
            }
            $result &= $this->acl->roleInheritsFrom($userRole, $role);
        }
        return (boolean)$result;
    }

}