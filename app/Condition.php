<?php namespace App;

use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Condition
 *
 * @property integer $id
 * @property string $name
 * @property string $value
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Collection|Operator[] $operators
 * @mixin Eloquent
 * @property string $type
 * @property-read array $input_config
 * @property-read int|null $operators_count
 * @property boolean $time_based
 * @method static Builder|Condition newModelQuery()
 * @method static Builder|Condition newQuery()
 * @method static Builder|Condition query()
 */
class Condition extends Model
{
    protected $guarded = ['id'];

    protected $casts = ['id' => 'integer', 'time_based' => 'boolean'];

    public $timestamps = false;

    /**
     *  Operators that are attached to this condition.
     */
    public function operators()
    {
        return $this->belongsToMany(Operator::class);
    }

    /**
     * @param string $value
     * @return array
     */
    public function getInputConfigAttribute($value)
    {
        return json_decode($value);
    }
}
