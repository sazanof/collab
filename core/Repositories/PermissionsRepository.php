<?php

namespace CLB\Core\Repositories;

use CLB\Core\Models\Permissions;
use CLB\Security\Acl;

class PermissionsRepository extends CollabRepository
{
    /**
     * @throws \Doctrine\Persistence\Mapping\MappingException
     */
    public function insertDefaultPermissions($type = 'core'){
        Permissions::create([
            'type'=>$type,
            'action'=>Acl::CAN_CREATE
        ]);

        Permissions::create([
            'type'=>$type,
            'action'=>Acl::CAN_READ
        ]);

        Permissions::create([
            'type'=>$type,
            'action'=>Acl::CAN_UPDATE
        ]);

        Permissions::create([
            'type'=>$type,
            'action'=>Acl::CAN_DELETE
        ]);
    }
}