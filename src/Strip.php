<?php
declare(strict_types = 1);

namespace Dinofei\HtmlStrip;

class Strip
{
    protected static $tokens = [
        'style' => '<style\\s*[\s\S]*>[\s\S]+<\/style>',
        'textnode' => '(?<=>|^)[^<]++(?=<|$)',
        'attributevalue' => '\\s*+=\\s*+(?:"[^"]*+"++|\'[^\']*+\'++|[^\\s>]*+)',
        'attribute' => '\\s*+[^<>"\'\\/=\\s]++',
        'tagopenend' => '\\s*+>',
        'tagselfclose' => '\\s*+\\/>',
        'tagopenstart' => '<[a-zA-Z][a-zA-Z0-9_:.-]*+',
        'tagclose' => '<\\/[a-zA-Z][a-zA-Z0-9_:.-]*+\\s*+>',
        'doctype' => '<!(?i:DOCTYPE)',
        'comment' => '<!--[\\d\\D]*?(?<=--)>',
        'cdata' => '<!\\[CDATA\\[[\\d\\D]*?\\]\\]>',
        'quotes' => '\\s*+(?:"[^"]*+"|\'[^\']*+\')',
        'other' => '.'
    ];

    protected static $htmlEntities = [
        '\\s+',
        '&ensp;',
        '&emsp;',
        '&nbsp;',
        '&lt;',
        '&gt;',
        '&amp;',
        '&quot;',
        '&copy;',
        '&reg;',
        '™',
        '&times;',
        '&divide;',
    ];

    protected string $ignoreRule;

    public function __construct()
    {
        $this->ignoreRule = sprintf('/^((%s))+$/su', implode(')|(', static::$htmlEntities));
    }

    public function parse(string $html): array
    {
        $output = [];
        $tokens = new Tokenise(self::$tokens, $html);
        while (($token = $tokens->next()) !== null) {
            if ($token['type'] == 'textnode' && $tokens->current() != null) {
                $item = trim($token['value']);
                if (!$this->ignore($item)) {
                    $output[] = $item;
                }
            }
        }
        return $output;
    }

    protected function ignore(string $str): bool
    {
        if (!$str) {
            return true;
        }
        $res = preg_match($this->ignoreRule, $str);
        return $res !== false && $res > 0;
    }

}