<?php

namespace App\Service;
use Knp\Bundle\MarkdownBundle\MarkdownParserInterface;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\Cache\CacheInterface;

class MarkdownHelper
{
    private $markdownParser;
    private $markdownLogger;
    private $cache;
    private $isDebugEnabled;


    public function __construct(
        MarkdownParserInterface $markdownParser,
        CacheInterface $cache,
        LoggerInterface $markdownLogger,
        bool $isDebugEnabled)
    {
        $this->markdownParser = $markdownParser;
        $this->cache = $cache;
        $this->isDebugEnabled = $isDebugEnabled;
        $this->markdownLogger = $markdownLogger;
    }

    /**
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function parse(string $stringToParse): string
    {
        if (stripos($stringToParse, 'cat')) {
            $this->markdownLogger->info('cool');
        }
        if ($this->isDebugEnabled) {
            return $this->markdownParser->transformMarkdown($stringToParse);
        }

        return $this->cache->get(
            'markdown_'.md5($stringToParse),
            function () use ($stringToParse) {
            return $this->markdownParser->transformMarkdown($stringToParse);
            }
            );
    }
}