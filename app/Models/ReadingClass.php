<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReadingClass extends Model
{
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'text_id',
    ];

    protected $afterSaveCallbacks = [];

    protected static function booted()
    {
        static::saved(function ($model) {
            foreach ($model->afterSaveCallbacks as $callback) {
                $callback();
            }
            $model->afterSaveCallbacks = [];
        });
    }

    public function setTextIdAttribute($value)
    {
        if ($value) {
            $this->afterSaveCallbacks[] = function () use ($value) {
                $this->texts()->syncWithoutDetaching([$value]);
            };
        }
    }

    public function getTextIdAttribute()
    {
        return $this->texts->first()?->id ?? $this->texts()->first()?->id;
    }

    public function getTextAttribute()
    {
        return $this->texts->first() ?? $this->texts()->first();
    }

    /**
     * @return BelongsToMany<Text, $this>
     */
    public function texts(): BelongsToMany
    {
        return $this->belongsToMany(Text::class, 'reading_class_text');
    }

    /**
     * @return BelongsToMany<User, $this>
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'reading_class_user');
    }

    /**
     * @return HasMany<Assignment, $this>
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }
}
