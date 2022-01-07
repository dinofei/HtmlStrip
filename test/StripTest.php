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

    public function originProvider() :array
    {
        return [['
<h1>这是一段测试代码</h1>
<p>这是文本内容 yy 哦</p><style>...</style>
<table>
<tr>
<td>标题 -</td>
</tr>
<tr>
<td>穷哈哈 222...</td>
</tr>
</table>
']];
    }

    /**
     * @dataProvider originProvider
     */
    public function testFilter(string $origin)
    {
        $cases = $this->main->filter($origin);
        $this->assertCount(4, $cases);
        $this->assertEquals('这是一段测试代码', $cases[array_keys($cases)[0]]);

        foreach ($cases as $key => $case) {
            $cases[$key] = $this->name($case);
        }

        $output = $this->main->replace($cases);
        var_dump($output);
    }

    protected function name(string $key) :string
    {
        $text = [
            '这是一段测试代码' => 'This is a test code',
            '这是文本内容 yy 哦' => 'This is the text YY',
            '标题 -' => 'Title-',
            '穷哈哈 222...' => 'Poor ha ha 222'
        ];
        return $text[$key];
    }

}