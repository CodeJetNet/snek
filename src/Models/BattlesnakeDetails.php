<?php

namespace PhpTek\BattleSnek\Models;

class BattlesnakeDetails implements \JsonSerializable
{
    public function __construct(
        private string $apiVersion,
        private string $author,
        private string $color,
        private string $head,
        private string $tail
    ) {
    }

    public function jsonSerialize(): mixed
    {
        return [
            'apiversion' => $this->apiVersion,
            'author' => $this->author,
            'color' => $this->color,
            'head' => $this->head,
            'tail' => $this->tail,
        ];
    }
}
