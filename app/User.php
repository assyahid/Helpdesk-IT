<?php namespace App;

use Common\Auth\BaseUser;
use Common\Auth\Permissions\Permission;
use Common\Auth\Roles\Role;
use Common\Auth\SocialProfile;
use Common\Billing\Subscription;
use Common\Notifications\NotificationSubscription;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\PersonalAccessToken;

/**
 * App\User
 *
 * @property-read Collection|Ticket[] $tickets
 * @property-read Collection|Reply[] $replies
 * @property-read Collection|CannedReply[] $cannedReplies
 * @property-read Collection|PurchaseCode[] $envato_purchase_codes
 * @property-read Collection|PurchaseCode[] $purchase_codes
 * @property-read Collection|Email[] $secondary_emails
 * @property-read ?UserDetails $details
 *  @mixin Eloquent
 * @property int $id
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string $email
 * @property string|null $avatar
 * @property string|null $language
 * @property string|null $country
 * @property string|null $timezone
 * @property string|null $password
 * @property string|null $api_token
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property int $confirmed
 * @property string|null $confirmation_code
 * @property string|null $stripe_id
 * @property int|null $available_space
 * @property string|null $username
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property-read int|null $canned_replies_count
 * @property-read string $display_name
 * @property-read bool $has_password
 * @property-read string $model_type
 * @property-read Collection|NotificationSubscription[] $notificationSubscriptions
 * @property-read int|null $notification_subscriptions_count
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read Collection|Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read int|null $purchase_codes_count
 * @property-read int|null $replies_count
 * @property-read Collection|Role[] $roles
 * @property-read int|null $roles_count
 * @property-read int|null $secondary_emails_count
 * @property-read Collection|SocialProfile[] $social_profiles
 * @property-read int|null $social_profiles_count
 * @property-read Collection|Subscription[] $subscriptions
 * @property-read int|null $subscriptions_count
 * @property-read Collection|Tag[] $tags
 * @property-read int|null $tags_count
 * @property-read int|null $tickets_count
 * @property-read Collection|PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static Builder|BaseUser compact()
 * @method static Builder|BaseUser matches(array $columns, string $value)
 * @method static Builder|BaseUser mysqlSearch(string $query)
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User query()
 * @method static Builder|BaseUser whereNeedsNotificationFor($notifId)
 * @mixin Eloquent
 */
class User extends BaseUser
{
    use HasApiTokens;

    protected $billingEnabled = false;

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class)->orderBy('created_at', 'desc');
    }

    /**
     * User profile.
     *
     * @return HasOne
     */
    public function details()
    {
        return $this->hasOne(UserDetails::class);
    }

    /**
     * Secondary email address belonging to user.
     *
     * @return HasMany
     */
    public function secondary_emails()
    {
        return $this->hasMany(Email::class);
    }

    public function purchase_codes(): HasMany
    {
        return $this->hasMany(PurchaseCode::class)->orderBy(
            'created_at',
            'desc',
        );
    }

    /**
     * Replies submitted by this user.
     *
     * @return HasMany
     */
    public function replies()
    {
        return $this->hasMany('App\Reply');
    }

    public function cannedReplies(): HasMany
    {
        return $this->hasMany(CannedReply::class);
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function updatePurchases(?iterable $purchases)
    {
        if ($purchases) {
            $this->purchase_codes()->delete();
            $newCodes = $this->purchase_codes()->createMany($purchases);
            $this->setRelation('purchase_codes', $newCodes);
        }
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasPermission('superAdmin') ||
            $this->hasPermission('admin');
    }

    public function isAgent(): bool
    {
        return $this->isSuperAdmin() ||
            $this->belongsToRole('agents') ||
            $this->hasPermission('tickets.update');
    }

    public function belongsToRole(string $name): bool
    {
        return $this->roles->contains('name', $name);
    }

    public function toSearchableArray(): array
    {
        $data = parent::toSearchableArray();
        $data['purchase_codes'] = $this->purchase_codes->pluck(
            'envato_username',
        );
        return $data;
    }
}
