<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Role
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $users
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property string|null $display_name
 * @property string|null $description
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role whereDisplayName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role whereUpdatedAt($value)
 */
class Role extends Model
{
    /**
     * @var array
     */
    public static $roles = [
        'admin',
        'client',
        'account_manager',
        'writer',
        'editor',
        'designer',
        'researcher'
    ];

    /**
     * @const string
     */
    const ADMIN = 'admin';

    /**
     * @const string
     */
    const CLIENT = 'client';

    /**
     * @const string
     */
    const ACCOUNT_MANAGER = 'account_manager';

    /**
     * @const string
     */
    const WRITER = 'writer';

    /**
     * @const string
     */
    const EDITOR = 'editor';

    /**
     * @const string
     */
    const RESEARCHER = 'researcher';

    /**
     * @const string
     */
    const DESIGNER = 'designer';

    const TABLE = 'roles';

    const COLUMN = 'name';

    const WORKERS = [
        self::WRITER,
        self::DESIGNER,
        self::EDITOR,
        self::RESEARCHER,
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

}
