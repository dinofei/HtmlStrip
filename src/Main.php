<?php
declare(strict_types = 1);

namespace Dinofei\HtmlStrip;

class Main
{
    protected string $origin = '';

    protected string $skeleton = '';

    protected string $output = '';

    protected string $separateTpl = '{{$-%d-$}}';

    protected array $cases = [];
    
    protected array $newCases = [];

    protected Strip $strip;

    public function __construct()
    {
        $this->strip = new Strip();
    }

    public function replace(array $cases) :string
    {
        $this->newCases = $this->withSortCases($cases);

        $this->setOutput();

        return $this->output;
    }

    public function filter(string $origin) :array
    {
        $this->origin = $origin;

        $cases = $this->strip->parse($this->origin);

        $this->buildCases($this->withSortCases($cases));

        $this->setSkeleton();

        return array_flip($this->cases);
    }

    protected function buildCases(array $cases) :void
    {
        foreach ($cases as $num => $case) {
            $this->cases[$case] = $this->getSeparate($num);
        }
    }

    protected function setSkeleton() :void
    {
        $this->skeleton = str_replace(array_keys($this->cases), array_values($this->cases), $this->origin);
    }

    protected function setOutput() :void
    {
        $this->output = str_replace(array_keys($this->newCases), array_values($this->newCases), $this->skeleton);
    }

    protected function getSeparate(int $num) :string
    {
        return sprintf($this->separateTpl, $num);
    }

    /**
     * @return string
     */
    public function getOrigin(): string
    {
        return $this->origin;
    }

    protected function withSortCases($arr) :array
    {
        $sortMap = array_map(function ($item) {
            return strlen($item);
        }, $arr);
        arsort($sortMap);

        $newCases = [];

        foreach ($sortMap as $k => $v) {
            $newCases[$k] = $arr[$k];
        }

        return $newCases;
    }

}