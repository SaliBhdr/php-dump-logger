<?php

namespace SaliBhdr\DumpLog\Dumpers;

use SaliBhdr\DumpLog\Contracts\DumperStrategyInterface;
use Symfony\Component\VarDumper\Dumper\AbstractDumper;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;

class HtmlDumperStrategy implements DumperStrategyInterface
{
    /**
     * {@inheritDoc}
     */
    public function getDumper(): AbstractDumper
    {
        return new HtmlDumper();
    }

    /**
     * {@inheritDoc}
     */
    public function getExtension(): string
    {
        return 'html';
    }

    /**
     * {@inheritDoc}
     */
    public function getTitle(): string
    {
        $title = "\n";
        $title .= '<br><p>';
        $title .= '---| ';
        $title .= '<span style="font-weight: bold;">' . date('Y-m-d H:i:s') . '</span>';
        $title .= ' |-------------------------------------------------------------------------------------------';
        $title .= '</p>';
        $title .= "\n";

        return $title;
    }
}
