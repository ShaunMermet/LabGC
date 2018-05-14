<?php
/**
 * labelimage ()
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
 * Represents a set that an image belongs to.
 * @author Shaun Mermet ()
 */
class Drone extends UFModel
{
    /**
     * @var string The name of the table for the current model.
     */
    protected $table = "drones";

    protected $fillable = [
    	"drone_name",
        "drone_slug",
        "fleet_id",
        "ipv4"
    ];

    /**
     * Return this drone's fleet.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fleet()
    {
        /** @var UserFrosting\Sprinkle\Core\Util\ClassMapper $classMapper */
        $classMapper = static::$ci->classMapper;

        return $this->belongsTo($classMapper->getClassMapping('fleet'), 'fleet_id');
    }

}

