<?php

namespace Dinofei\Test;

use Dinofei\HtmlStrip\Main;
use PHPUnit\Framework\TestCase;

class StripTest extends TestCase
{

    protected Main $main;

    public function setUp(): void
    {
        $this->main = new Main();
    }

    public function originProvider(): array
    {
        return [
            [
                '<p>hello</p><p>hello</p><p>hello man</p>'
            ]
        ];
    }

    /**
     * @dataProvider originProvider
     */
    public function testFilter(string $origin)
    {
        $cases = $this->main->filter($origin);

        $this->assertCount(2, $cases);

        foreach ($cases as $key => $case) {
            $cases[$key] = $this->name($case);
        }

        $output = $this->main->replace($cases);

        var_dump($output);
    }

    protected function name(string $key): string
    {
        $text = [
            'hello' => '你好',
            'hello man' => '你好 兄弟',
        ];
        return $text[$key];
    }

}