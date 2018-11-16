<?php

namespace App\Services;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * Class WordsCounter
 * @package App\Services
 */
class WordsCounter implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    private $delimiters = [' ', ',', '.', ';', ':'];

    /**
     * @param string $filename
     * @return int
     */
    public function countIn(string $filename) : int
    {
        $word = '';
        $uniqueWords = [];
        $file = fopen($filename, 'r');
        while (false !== ($c = fgetc($file))) {
            if (!in_array($c, $this->delimiters)) {
                $word .= $c;
            } else {
                if (!in_array($word, $uniqueWords)) {
                    $uniqueWords[] = $word;
                }
                $word = '';
            }
        }
        fclose($file);
        $words = count($uniqueWords);
        return $words;
    }

    /**
     * @param string $filename
     * @return int
     */
    public function fastCountIn(string $filename) : int
    {
        $words = count(array_unique(str_word_count(file_get_contents($filename), 1)));
        return $words;
    }

    /**
     * @param string $filename
     * @param string $word
     * @return int|null
     */
    public function wordEntries(string $filename, string $word) : ?int
    {
        $dir = $this->container->getParameter('bookApp.watch_folder');
        $haystack = file_get_contents($dir . $filename);
        $entriesNumber = substr_count($haystack, $word);
        return $entriesNumber;
    }
}