<?php

namespace App\Support;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMXPath;

/**
 * Lapisan pertahanan kedua (defense-in-depth) untuk konten artikel.
 *
 * Konten artikel dicetak sebagai HTML mentah ({!! !!}) di halaman publik.
 * Saat ini pengisian field `content` memang dibatasi ke admin lewat
 * middleware `auth` + `admin` (lihat routes/admin.php), jadi risiko utama
 * (stored XSS dari input publik) tidak ada.
 *
 * Class ini menambah lapisan proteksi tambahan supaya:
 *  - Kalau suatu saat ada role baru (editor, kontributor, dst.) yang boleh
 *    mengisi/mengedit artikel tanpa hak admin penuh, konten tetap aman.
 *  - Kalau akun admin ter-kompromi (password bocor, dsb.), payload
 *    <script>, event handler (onerror, onclick, ...), atau `javascript:`
 *    URL tetap tidak akan pernah tersimpan/tercetak sebagai HTML aktif.
 *  - Editor WYSIWYG di form admin (mis. TinyMCE/Quill) kadang menyisipkan
 *    markup "kotor" (style inline, span bawaan browser, dll) — sanitizer
 *    ini merapikannya sekaligus.
 *
 * Pendekatan: whitelist tag & attribute (bukan blacklist), karena
 * blacklist selalu bisa dilewati vektor baru. Tag/attribute yang tidak
 * diizinkan akan dibuang (isi teks di dalamnya tetap dipertahankan),
 * bukan membuat seluruh konten ditolak.
 */
class HtmlSanitizer
{
    /**
     * Tag yang diizinkan, sesuai kebutuhan konten artikel
     * (lihat styling .article-content di resources/css/app.css).
     */
    private const ALLOWED_TAGS = [
        'p', 'br', 'hr',
        'strong', 'b', 'em', 'i', 'u', 's',
        'h2', 'h3', 'h4',
        'ul', 'ol', 'li',
        'blockquote',
        'a', 'img',
        'table', 'thead', 'tbody', 'tr', 'th', 'td',
        'code', 'pre', 'span',
        'figure', 'figcaption',
    ];

    /**
     * Attribute yang diizinkan per tag. '*' berlaku untuk semua tag
     * yang diizinkan di atas.
     */
    private const ALLOWED_ATTRIBUTES = [
        '*' => [],
        'a' => ['href', 'title'],
        'img' => ['src', 'alt', 'title', 'width', 'height'],
        'table' => ['border'],
        'th' => ['colspan', 'rowspan'],
        'td' => ['colspan', 'rowspan'],
    ];

    /** Skema URL yang diizinkan untuk href / src. */
    private const ALLOWED_URL_SCHEMES = ['http', 'https', 'mailto', 'tel'];

    public static function clean(?string $html): ?string
    {
        if ($html === null || trim($html) === '') {
            return $html;
        }

        $dom = new DOMDocument('1.0', 'UTF-8');

        libxml_use_internal_errors(true);
        // Bungkus dengan wrapper <div> supaya fragment HTML (bukan dokumen
        // penuh) di-parse dengan aman dan encoding UTF-8 terjaga.
        // LIBXML_HTML_NOIMPLIED mencegah libxml otomatis menambah <html>/
        // <body>, jadi documentElement langsung berupa <div> wrapper kita
        // (lebih andal daripada getElementById, yang butuh DTD supaya
        // atribut id dikenali sebagai ID — tidak tersedia di sini).
        $dom->loadHTML(
            '<?xml encoding="utf-8"?><div>' . $html . '</div>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );
        libxml_clear_errors();

        $root = $dom->documentElement;

        if (!$root) {
            return '';
        }

        self::sanitizeChildren($root, $dom);

        $output = '';
        foreach (iterator_to_array($root->childNodes) as $child) {
            $output .= $dom->saveHTML($child);
        }

        return trim($output);
    }

    private static function sanitizeChildren(DOMNode $node, DOMDocument $dom): void
    {
        // Salin dulu ke array karena kita akan memodifikasi DOM saat iterasi.
        $children = iterator_to_array($node->childNodes);

        foreach ($children as $child) {
            if ($child->nodeType === XML_TEXT_NODE || $child->nodeType === XML_CDATA_SECTION_NODE) {
                continue;
            }

            if (!($child instanceof DOMElement)) {
                // Komentar, processing instruction, dll — buang.
                $node->removeChild($child);
                continue;
            }

            $tag = strtolower($child->tagName);

            // script, style, iframe, form, object, svg, dst. dibuang
            // beserta seluruh isinya (bukan cuma tag-nya) karena isinya
            // sendiri berbahaya (mis. <script>...</script>).
            if (in_array($tag, ['script', 'style', 'iframe', 'object', 'embed', 'form', 'svg', 'math'], true)) {
                $node->removeChild($child);
                continue;
            }

            if (!in_array($tag, self::ALLOWED_TAGS, true)) {
                // Tag tidak diizinkan: rekursif dulu supaya anaknya tetap
                // disaring, lalu "unwrap" — anak-anaknya dipindah ke induk
                // menggantikan posisi tag ini, teksnya tidak hilang.
                self::sanitizeChildren($child, $dom);

                while ($child->firstChild) {
                    $node->insertBefore($child->firstChild, $child);
                }
                $node->removeChild($child);
                continue;
            }

            self::sanitizeAttributes($child, $tag);
            self::sanitizeChildren($child, $dom);
        }
    }

    private static function sanitizeAttributes(DOMElement $el, string $tag): void
    {
        $allowed = array_merge(
            self::ALLOWED_ATTRIBUTES['*'],
            self::ALLOWED_ATTRIBUTES[$tag] ?? []
        );

        // Salin dulu nama attribute-nya karena kita akan menghapus saat iterasi.
        $attributeNames = [];
        foreach (iterator_to_array($el->attributes) as $attr) {
            $attributeNames[] = $attr->name;
        }

        foreach ($attributeNames as $name) {
            $lower = strtolower($name);

            // Buang semua event handler (onclick, onerror, onload, ...)
            // dan atribut lain yang tidak eksplisit di-whitelist.
            if (!in_array($lower, $allowed, true)) {
                $el->removeAttribute($name);
                continue;
            }

            $value = $el->getAttribute($name);

            if (in_array($lower, ['href', 'src'], true) && !self::isSafeUrl($value)) {
                $el->removeAttribute($name);
                continue;
            }
        }

        // Link keluar: paksa rel yang aman supaya tidak bisa dipakai untuk
        // tabnapping (target=_blank tanpa noopener), tanpa perlu
        // meng-whitelist rel/target sebagai input bebas dari konten.
        if ($tag === 'a' && $el->hasAttribute('href')) {
            $el->setAttribute('rel', 'noopener noreferrer nofollow');
        }

        if ($tag === 'img') {
            $el->setAttribute('loading', 'lazy');
        }
    }

    private static function isSafeUrl(string $url): bool
    {
        $url = trim($url);

        if ($url === '') {
            return false;
        }

        // URL relatif (termasuk anchor #, path lokal) diizinkan.
        if (!str_contains($url, ':') || str_starts_with($url, '/') || str_starts_with($url, '#')) {
            return true;
        }

        $scheme = strtolower((string) parse_url($url, PHP_URL_SCHEME));

        return in_array($scheme, self::ALLOWED_URL_SCHEMES, true);
    }
}
