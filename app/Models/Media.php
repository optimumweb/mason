<?php

namespace App\Models;

use App\Traits\Metable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Media extends Model
{
    use HasFactory, SoftDeletes, Metable;

    const STORAGE_DIR = 'public/media';
    const DEFAULT_VISIBILITY = 'public';
    const RANDOM_PATH_LENGTH = 8;

    protected $fillable = [
        'title',
        'file',
    ];

    public function __toString()
    {
        return "{$this->title}";
    }

    public function setFileAttribute(File|UploadedFile $file)
    {
        $this->title ??= $file->getClientOriginalName();

        $date = date('Y/m');
        $randomString = Str::random(static::RANDOM_PATH_LENGTH);

        $this->storage_key = Storage::putFileAs(
            static::STORAGE_DIR,
            $file,
            "{$date}/{$randomString}/{$this->title}",
            static::DEFAULT_VISIBILITY
        );
    }

    public function getUrlAttribute()
    {
        if (isset($this->storage_key)) {
            return Storage::url($this->storage_key);
        }
    }

    public function parent()
    {
        return $this->morphTo();
    }
}
