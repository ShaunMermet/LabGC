<?php
/**
 *  ()
 *
 * @link      
 * @copyright 
 * @license   
 */
namespace UserFrosting\Sprinkle\Site\Model;


use UserFrosting\Sprinkle\Core\Model\UFModel;

/**
 * Verification Class
 *
 * Represents a mountpoint. Belongs to a drone
 * @author Shaun Mermet ()
 */
class mountpoint extends UFModel
{
    /**
     * @var string The name of the table for the current model.
     */
    protected $table = "mountpoints";

    protected $fillable = [
    	"name",
        "port",
        "drone_id"
    ];

    /**
     * Return this mountpoint's drone.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function drone()
    {
        /** @var UserFrosting\Sprinkle\Core\Util\ClassMapper $classMapper */
        $classMapper = static::$ci->classMapper;

        return $this->belongsTo($classMapper->getClassMapping('drone'), 'drone_id');
    }

}

