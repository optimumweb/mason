<?php

namespace App\Models;

use App\Traits\Localizable;
use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Form extends Model
{
    use HasFactory,
        Localizable,
        Translatable,
        SoftDeletes;

    const ICON = 'fa-clipboard-list';

    protected $fillable = [
        'title',
        'name',
        'description',
        'locale_id',
        'confirmation_message',
        'send_to',
        'reply_to',
        'cc',
        'bcc',
        'redirect_to',
        'grecaptcha_enabled',
        'grecaptcha_site_key',
        'grecaptcha_secret_key',
        'fields',
    ];

    protected $casts = [
        'grecaptcha_enabled' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $hidden = [
        'grecaptcha_secret_key',
    ];

    /**
     * ==================================================
     * Static Methods
     * ==================================================
     */

    protected static function boot(): void
    {
        parent::boot();

        static::saving(function (self $form) {
            $form->name ??= Str::slug($form->title);

            // Prevent enabling Google reCAPTCHA without entering required keys
            if (! isset($form->grecaptcha_site_key, $form->grecaptcha_secret_key)) {
                $form->grecaptcha_enabled = false;
            }
        });

        static::deleted(function (self $form) {
            $form->fields()->delete();
            $form->submissions()->delete();
        });
    }

    /**
     * ==================================================
     * Scopes
     * ==================================================
     */

    public function scopeSearch(Builder $query, string $term): Builder
    {
        return $query
            ->where('name', 'LIKE', "%{$term}%");
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        if (isset($filters['locale_id'])) {
            $query->byLocale($filters['locale_id']);
        }

        return $query;
    }

    /**
     * ==================================================
     * Accessors & Mutators
     * ==================================================
     */

    public function getActionAttribute(): string
    {
        if (isset($this->locale) && ! $this->locale->is_default) {
            return route(
                name: 'locale.form.submit',
                parameters: [
                    'locale' => $this->locale,
                    'form' => $this,
                ]
            );
        }

        return route(
            name: 'form.submit',
            parameters: [
                'form' => $this,
            ]
        );
    }

    public function getMethodAttribute(): string
    {
        return 'POST';
    }

    public function getRulesAttribute(): array
    {
        $rules = [];

        $fields = $this->fields()->whereNotNull('rules')->get();

        foreach ($fields as $field) {
            $rules[$field->rulekey] = $field->rules;
        }

        return $rules;
    }

    public function setFieldsAttribute(array $fields = []): void
    {
        $fieldKeys = [];
        $rank = 0;

        foreach ($fields as $fieldKey => $fieldAttributes) {
            if ($field = $this->fields()->find($fieldKey)) {
                $fieldKeys[] = $fieldKey;
                $fieldAttributes['rank'] = $rank++;
                $field->updateOrFail($fieldAttributes);
            }
        }

        $this->fields()->whereNotIn('id', $fieldKeys)->delete();
    }

    /**
     * ==================================================
     * Relationships
     * ==================================================
     */

    public function locale(): BelongsTo
    {
        return $this->belongsTo(Locale::class);
    }

    public function fields(): HasMany
    {
        return $this->hasMany(FormField::class);
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(FormSubmission::class);
    }

    /**
     * ==================================================
     * Helpers
     * ==================================================
     */

    public function __toString()
    {
        return $this->render();
    }

    public function view(): ?string
    {
        $views = [
            "{$this->locale->name}.forms.{$this->name}",
            "{$this->locale->name}.forms.default",
            "{$this->locale->name}.form",
            "forms.{$this->name}",
            "forms.default",
            "form",
        ];

        foreach ($views as $view) {
            if (view()->exists($view)) {
                return $view;
            }
        }

        return null;
    }

    public function render(array $data = []): string
    {
        if ($view = $this->view()) {
            return view($view, array_merge($data, ['form' => $this]))->render();
        }

        return "";
    }

    public function runActions(FormSubmission $submission): void
    {
        if (! $submission->isSpam()) {
            if (isset($this->send_to)) {
                $submission->send($this->send_to);
            }
        }

        if (isset($this->redirect_to)) {
            redirect()->to($this->redirect_to)->send();
        }
    }
}
