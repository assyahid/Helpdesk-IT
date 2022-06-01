<?php namespace App;

use Carbon\Carbon;
use Common\Search\Searchable;
use Eloquent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Query\Builder;

/**
 * App\Operator
 *
 * @property integer $id
 * @property string $name
 * @method static Builder|Operator whereId($value)
 * @method static Builder|Operator whereName($value)
 * @mixin Eloquent
 * @property string $description
 * @property integer $times_fired
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Collection|Condition[] $conditions
 * @property-read Collection|Action[] $actions
 * @property integer $user_id
 * @property-read int|null $actions_count
 * @property-read int|null $conditions_count
 * @property-read string $model_type
 * @method static \Illuminate\Database\Eloquent\Builder|Trigger matches(array $columns, string $value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trigger mysqlSearch(string $query)
 * @method static \Illuminate\Database\Eloquent\Builder|Trigger newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Trigger newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Trigger query()
 */
class Trigger extends Model
{
    use Searchable;

    const MODEL_TYPE = 'trigger';

    protected $guarded = ['id'];
    protected $appends = ['model_type'];

    protected $casts = [
        'id' => 'integer',
        'times_fired' => 'integer',
        'user_id' => 'integer',
    ];

    public function conditions(): BelongsToMany
    {
        return $this->belongsToMany(
            Condition::class,
            'trigger_condition',
        )->withPivot(['condition_value', 'match_type', 'operator_id']);
    }

    public function actions(): BelongsToMany
    {
        return $this->belongsToMany(Action::class, 'trigger_action')->withPivot(
            ['action_value'],
        );
    }

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'created_at' => $this->created_at->timestamp ?? '_null',
            'updated_at' => $this->updated_at->timestamp ?? '_null',
        ];
    }

    public static function filterableFields(): array
    {
        return ['id', 'created_at', 'updated_at'];
    }

    public static function getModelTypeAttribute(): string
    {
        return self::MODEL_TYPE;
    }
}
