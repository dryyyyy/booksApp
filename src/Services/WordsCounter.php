<?php

namespace App\Services;

/**
 * Class WordsCounter
 * @package App\Services
 */
class WordsCounter
{
    private $delimiters = [' ', ',', '.', ';', ':'];

    /**
     * @param string $filename
     * @return int
     */
    public function countIn(string $filename) : int
    {
        $word = '';
        $rawWords = [];
        $file = fopen($filename, 'r');
        while (false !== ($c = fgetc($file))) {
            if (!in_array($c, $this->delimiters)) {
                $word .= $c;
            } else {
                $rawWords[] = $word;
                $word = '';
            }
        }
        fclose($file);
        $words = count(array_unique($rawWords));
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
        $haystack = file_get_contents($filename);
        $entriesNumber = substr_count($haystack, $word);
        return $entriesNumber;
    }
}