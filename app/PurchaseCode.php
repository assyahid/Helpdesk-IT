<?php namespace App;

use Carbon\Carbon;
use Common\Search\Searchable;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\EnvatoPurchaseCode
 *
 * @property int $id
 * @property string $code
 * @property int $user_id
 * @property string $item_name
 * @property string $item_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read User $user
 * @mixin Eloquent
 * @property string $url
 * @property string $image
 * @property ?Carbon $supported_until
 * @property string|null $envato_username
 * @method static Builder|PurchaseCode newModelQuery()
 * @method static Builder|PurchaseCode newQuery()
 * @method static Builder|PurchaseCode query()
 * @property-read string $model_type
 * @method static Builder|PurchaseCode matches(array $columns, string $value)
 * @method static Builder|PurchaseCode mysqlSearch(string $query)
 */
class PurchaseCode extends Model
{
    use Searchable;

    const MODEL_TYPE = 'purchaseCode';

    protected $guarded = ['id'];
    protected $casts = ['id' => 'integer', 'user_id' => 'integer'];
    protected $dates = ['supported_until', 'purchased_at'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'item_name' => $this->item_name,
            'envato_username' => $this->envato_username,
        ];
    }

    public static function filterableFields(): array
    {
        return ['id', 'created_at', 'updated_at'];
    }

    public static function getModelTypeAttribute(): string
    {
        return static::MODEL_TYPE;
    }
}
