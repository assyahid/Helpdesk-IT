<?php

namespace App;

use Common\Files\FileEntry as CommonFileEntry;
use Common\Files\FileEntryUser;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Carbon;

/**
 * App\FileEntry
 *
 * @property int $id
 * @property string $name
 * @property string $file_name
 * @property int $file_size
 * @property string|null $mime
 * @property string|null $extension
 * @property int|null $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property bool $public
 * @property string|null $disk_prefix
 * @property int|null $parent_id
 * @property string|null $description
 * @property string|null $password
 * @property string|null $type
 * @property Carbon|null $deleted_at
 * @property string|null $path
 * @property string|null $preview_token
 * @property bool $thumbnail
 * @property-read Collection|CannedReply[] $canned_replies
 * @property-read int|null $canned_replies_count
 * @property-read Collection|FileEntry[] $children
 * @property-read int|null $children_count
 * @property-read string $hash
 * @property-read string|null $url
 * @property-read User $owner
 * @property-read FileEntry|null $parent
 * @property-read Collection|Reply[] $replies
 * @property-read int|null $replies_count
 * @property-read Collection|\Common\Tags\Tag[] $tags
 * @property-read int|null $tags_count
 * @property-read Collection|FileEntryUser[] $users
 * @property-read int|null $users_count
 * @method static Builder|FileEntry allChildren()
 * @method static Builder|FileEntry allParents()
 * @method static Builder|FileEntry matches(array $columns, string $value)
 * @method static Builder|FileEntry mysqlSearch(string $query)
 * @method static Builder|FileEntry newModelQuery()
 * @method static Builder|FileEntry newQuery()
 * @method static Builder|FileEntry query()
 * @method static Builder|FileEntry whereHash($value)
 * @method static Builder|FileEntry whereNotOwner($userId)
 * @method static Builder|FileEntry whereOwner($userId)
 * @method static Builder|FileEntry whereRootOrParentNotTrashed()
 * @method static Builder|FileEntry whereUser($userId, $owner = null)
 * @mixin Eloquent
 * @property int $owner_id
 */
class FileEntry extends CommonFileEntry
{
    public function replies(): MorphToMany
    {
        return $this->morphedByMany(Reply::class, 'model', 'file_entry_models');
    }

    public function canned_replies(): BelongsToMany
    {
        return $this->morphedByMany(
            CannedReply::class,
            'model',
            'file_entry_models',
        );
    }
}
