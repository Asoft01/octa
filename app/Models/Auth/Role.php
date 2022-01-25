<?php

namespace App\Models\Auth;

use Altek\Accountant\Contracts\Recordable;
use Altek\Accountant\Recordable as RecordableTrait;
use App\Models\Auth\Traits\Method\RoleMethod;
use Spatie\Permission\Models\Role as SpatieRole;

/**
 * Class Role.
 */
class Role extends SpatieRole implements Recordable
{
    use RecordableTrait,
        RoleMethod;

    

    public function scopeMentors($query)
    {
        return $query->where('name', 'mentor');
    }

    public function scopeContributors($query)
    {
        return $query->where('name', 'contributor');
    }
}
