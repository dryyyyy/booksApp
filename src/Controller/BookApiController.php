<?php

namespace App\Controller;

use App\Entity\Book;
use App\Services\WordsCounter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BookApiController extends AbstractController
{
    private $wordsCounter;

    /**
     * BookApiController constructor.
     * @param WordsCounter $wordsCounter
     */
    public function __construct(WordsCounter $wordsCounter)
    {
        $this->wordsCounter = $wordsCounter;
    }

    /**
     * @Route("/api/{bookName}", name="book_api")
     *
     * @param string $bookName
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function showUniqueWords(string $bookName)
    {

        $entityManager = $this->getDoctrine()->getManager();
        $book = $entityManager->getRepository(Book::class)->findOneBy(array('name' => "$bookName.txt"));

        return $this->json([
            'book' => substr($book->getName(), 0, -4),
            'Number of unique words' => $book->getUniqueWords()
        ]);
    }

    /**
     * @Route("/api/{bookName}/{word}", name="word_api")
     *
     * @param string $bookName
     * @param string $word
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function findWordEntries(string $bookName, string $word)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $book = $entityManager->getRepository(Book::class)->findOneBy(array('name' => "$bookName.txt"));

        $filename = $bookName . '.txt';
        $entries = $this->wordsCounter->wordEntries($filename, $word);

        return $this->json([
           'Book' =>  substr($book->getName(), 0, -4),
            'Word' => $word,
            'Number of entries' => $entries
        ]);
    }
}
