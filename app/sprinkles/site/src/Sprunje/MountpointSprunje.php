<?php
namespace UserFrosting\Sprinkle\Site\Sprunje;

use UserFrosting\Sprinkle\Core\Facades\Debug;
use UserFrosting\Sprinkle\Core\Sprunje\Sprunje;
use UserFrosting\Sprinkle\Site\Model\Mountpoint;

class MountpointSprunje extends Sprunje
{
    protected $name = 'Mountpoint';

    protected $sortable = [
        'name',
        'port'
    ];

    protected $filterable = [
        'drone_id',
        'name',
        'port',
        'by_ids',
    ];

    /**
     * Set the initial query used by your Sprunje.
     */
    protected function baseQuery()
    {
        $query = new Mountpoint();

        // Alternatively, if you have defined a class mapping, you can use the classMapper:
        // $query = $this->classMapper->createInstance('owl');

        return $query;
    }

    /**
     * Filter 
     *
     * @param Builder $query
     * @param mixed $value
     * @return $this
     */
    protected function filterByIds($query, $value)
    {
        $query->whereIn('drone_id', $value);
        return $this;
    }
}