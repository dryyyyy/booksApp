<?php

namespace App\Controller;

use App\Entity\Book;
use App\Services\WordsCounter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BookApiController extends AbstractController
{
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
        $wordCounter = new WordsCounter();

        $dir = getcwd() . '/../books/';
        $filename = $dir . $bookName . '.txt';
        $entries = $wordCounter->wordEntries($filename, $word);

        return $this->json([
           'Book' =>  substr($book->getName(), 0, -4),
            'Word' => $word,
            'Number of entries' => $entries
        ]);
    }
}
