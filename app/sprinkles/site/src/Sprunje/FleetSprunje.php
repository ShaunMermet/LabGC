<?php
namespace UserFrosting\Sprinkle\Site\Sprunje;

use UserFrosting\Sprinkle\Core\Facades\Debug;
use UserFrosting\Sprinkle\Core\Sprunje\Sprunje;
use UserFrosting\Sprinkle\Site\Model\Fleet;

class FleetSprunje extends Sprunje
{
    protected $name = 'Fleet';

    protected $sortable = [
        'name',
    ];

    protected $filterable = [
        'name',
        'description',
        'my_fleets'
    ];

    /**
     * Set the initial query used by your Sprunje.
     */
    protected function baseQuery()
    {
        $query = new Fleet();

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
    protected function filterMyFleets($query, $value)
    {
        $query->whereIn('id', $value);
        return $this;
    }
}