<?php

namespace App\Models;

use App\Support\HtmlSanitizer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'thumbnail',
        'content',
        'views',
        'meta_title',
        'meta_description',
        'is_published',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mutator: setiap kali `content` di-set (create/update, dari controller
     * mana pun), dibersihkan dulu lewat allowlist sanitizer.
     *
     * Ini lapisan pertahanan tambahan (defense-in-depth) di atas pembatasan
     * akses admin yang sudah ada di routes/admin.php — supaya markup
     * berbahaya (script, event handler, javascript: URL, dsb.) tidak
     * pernah tersimpan di database, apalagi dicetak sebagai HTML mentah
     * di resources/views/articles/show.blade.php.
     */
    protected function setContentAttribute(?string $value): void
    {
        $this->attributes['content'] = HtmlSanitizer::clean($value);
    }
}
