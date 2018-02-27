<?php
/**
 * UserFrosting (http://www.userfrosting.com)
 *
 * @link      https://github.com/userfrosting/UserFrosting
 * @copyright Copyright (c) 2013-2016 Alexander Weissman
 * @license   https://github.com/userfrosting/UserFrosting/blob/master/licenses/UserFrosting.md (MIT License)
 */
namespace UserFrosting\Sprinkle\Site\Sprunje;

use Illuminate\Database\Capsule\Manager as Capsule;
use UserFrosting\Sprinkle\Core\Facades\Debug;
use UserFrosting\Sprinkle\Core\Sprunje\Sprunje;
use UserFrosting\Sprinkle\Admin\Sprunje\GroupSprunje;

/**
 * GroupSprunje
 *
 * Implements Sprunje for the groups API.
 *
 * @author Alex Weissman (https://alexanderweissman.com)
 */
class GroupSprunjeLab extends GroupSprunje
{
    protected $filterable = [
        'name',
        'description',
        'id',
        'my_groups'
    ];

    /**
     * Filter 
     *
     * @param Builder $query
     * @param mixed $value
     * @return $this
     */
    protected function filterMyGroups($query, $value)
    {
        $query->whereIn('id', $value);
        return $this;
    }
}

