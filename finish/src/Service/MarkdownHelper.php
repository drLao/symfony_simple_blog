<?php

namespace App\Service;
use Knp\Bundle\MarkdownBundle\MarkdownParserInterface;
use Symfony\Contracts\Cache\CacheInterface;

class MarkdownHelper
{
    private $markdownParser;
    private $cache;

    public function __construct(
        MarkdownParserInterface $markdownParser,
        CacheInterface $cache)
    {
        $this->markdownParser = $markdownParser;
        $this->cache = $cache;
    }

    /**
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function parse(string $stringToParse): string
    {
        return $this->cache->get('markdown_'.md5($stringToParse), function () use ($stringToParse) {
            return $this->markdownParser->transformMarkdown($stringToParse);
        });

    }
}