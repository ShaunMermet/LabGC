<?php
namespace UserFrosting\Sprinkle\Site\Sprunje;

use UserFrosting\Sprinkle\Core\Facades\Debug;
use UserFrosting\Sprinkle\Core\Sprunje\Sprunje;
use UserFrosting\Sprinkle\Site\Model\Drone;

class DroneSprunje extends Sprunje
{
    protected $name = 'Drone';

    protected $sortable = [
        'drone_name',
    ];

    protected $filterable = [
        'drone_name',
        'my_drones'
    ];

    /**
     * Set the initial query used by your Sprunje.
     */
    protected function baseQuery()
    {
        $query = new Drone();

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
    protected function filterMyDrones($query, $value)
    {
        $query->whereIn('id', $value);
        return $this;
    }
}