<?php

namespace App\Support;

use DOMDocument;
use DOMElement;
use DOMNode;

/**
 * Whitelist sanitizer for the rich-text HTML that Quill stores in the
 * institution description columns.
 *
 * The description fields accept `nullable|string`, so a portal user can POST
 * markup that never passed through Quill. Anything rendered with {!! !!} in the
 * admin panel must go through here first.
 */
class HtmlSanitizer
{
    /** Tags Quill's toolbar can produce, plus the block wrappers it nests them in. */
    private const ALLOWED_TAGS = [
        'p', 'br', 'span', 'div',
        'strong', 'b', 'em', 'i', 'u', 's',
        'ol', 'ul', 'li',
        'blockquote', 'pre',
        'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
        'a',
    ];

    /** Elements whose text content is dropped along with the tag itself. */
    private const VOID_SUBTREES = ['script', 'style', 'iframe', 'object', 'embed', 'form'];

    private const ALLOWED_SCHEMES = ['http', 'https', 'mailto', 'tel'];

    public static function clean(?string $html): string
    {
        $html = trim((string) $html);

        if ($html === '') {
            return '';
        }

        $doc = new DOMDocument();

        $previous = libxml_use_internal_errors(true);
        // The meta charset keeps DOMDocument from mangling the Kurdish/Arabic text.
        $doc->loadHTML(
            '<?xml encoding="UTF-8"><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><div id="root">' . $html . '</div>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $root = $doc->getElementById('root');

        if (!$root) {
            return '';
        }

        self::cleanNode($root);

        $out = '';
        foreach ($root->childNodes as $child) {
            $out .= $doc->saveHTML($child);
        }

        return trim($out);
    }

    private static function cleanNode(DOMNode $node): void
    {
        // Snapshot first — the child list mutates while we unwrap and remove.
        foreach (iterator_to_array($node->childNodes) as $child) {
            if ($child instanceof DOMElement) {
                self::cleanElement($child);
                continue;
            }

            // Keep text, drop comments / processing instructions.
            if ($child->nodeType !== XML_TEXT_NODE) {
                $node->removeChild($child);
            }
        }
    }

    private static function cleanElement(DOMElement $el): void
    {
        $tag = strtolower($el->nodeName);

        if (in_array($tag, self::VOID_SUBTREES, true)) {
            $el->parentNode->removeChild($el);
            return;
        }

        // Recurse before unwrapping so descendants are cleaned either way.
        self::cleanNode($el);

        if (!in_array($tag, self::ALLOWED_TAGS, true)) {
            self::unwrap($el);
            return;
        }

        self::cleanAttributes($el, $tag);
    }

    private static function cleanAttributes(DOMElement $el, string $tag): void
    {
        foreach (iterator_to_array($el->attributes) as $attr) {
            $name  = strtolower($attr->nodeName);
            $value = $attr->nodeValue;

            // Quill encodes indent and alignment as ql-* classes.
            if ($name === 'class') {
                $kept = array_filter(
                    preg_split('/\s+/', trim($value)) ?: [],
                    fn ($c) => preg_match('/^ql-[\w-]+$/', $c) === 1
                );

                $kept ? $el->setAttribute('class', implode(' ', $kept))
                      : $el->removeAttribute('class');
                continue;
            }

            if ($tag === 'a' && $name === 'href' && self::isSafeUrl($value)) {
                continue;
            }

            // Everything else goes: on* handlers, style, src, data-*, srcset...
            $el->removeAttribute($attr->nodeName);
        }

        if ($tag === 'a' && $el->hasAttribute('href')) {
            $el->setAttribute('target', '_blank');
            $el->setAttribute('rel', 'noopener noreferrer');
        }
    }

    /** Replace an element with its children, preserving the text inside it. */
    private static function unwrap(DOMElement $el): void
    {
        $parent = $el->parentNode;

        while ($el->firstChild) {
            $parent->insertBefore($el->firstChild, $el);
        }

        $parent->removeChild($el);
    }

    private static function isSafeUrl(string $url): bool
    {
        $url = trim($url);

        // Relative links carry no scheme and cannot smuggle javascript:.
        if ($url === '' || str_starts_with($url, '/') || str_starts_with($url, '#')) {
            return true;
        }

        $scheme = parse_url($url, PHP_URL_SCHEME);

        return $scheme !== null && in_array(strtolower($scheme), self::ALLOWED_SCHEMES, true);
    }
}
