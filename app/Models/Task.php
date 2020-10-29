<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Traits\Date;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Task model
 *
 * @property int user_id User ID
 * @property Date $date Task date
 * @property string $title Task title
 * @property int $hours Task hours spent
 *
 * @author Mike Shatunov <mixasic@yandex.ru>
 */
class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id', 'date', 'title', 'hours'];
    protected $hidden = ['created_at', 'updated_at'];

    const ATTR_ID = 'id';
    const ATTR_USER_ID = 'user_id';
}
