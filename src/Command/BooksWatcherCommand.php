<?php

namespace App\Command;

use App\Entity\Book;
use App\Entity\DirectoryImage;
use App\Services\WordsCounter;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class BooksWatcherCommand
 * @package App\Command
 */
class BooksWatcherCommand extends ContainerAwareCommand
{
    private $io;
    private $dir = '';
    private $entityManager;
    private $files = [];
    private $wordsCounter;
    private $dirImage;
    protected static $defaultName = 'app:books-watcher';

    protected function configure()
    {
        $this
            ->setDescription('Watches a directory with book files and synchronizes it with the db.')
            ->addArgument('folderName', InputArgument::REQUIRED, 'The name of the folder you want to watch.')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    private function init(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
        $this->wordsCounter = new WordsCounter();
        $this->dir = getcwd() . '/' .$input->getArgument('folderName') . '/';
        $this->files =  array_diff(scandir($this->dir), ['.', '..']);
        $this->entityManager = $this->getContainer()->get('doctrine')->getManager();
        $this->dirImage = $this->entityManager->getRepository(DirectoryImage::class)->findOneBy(array('id' => '1'));
    }

    /**
     * Remove row from db if it does not have a corresponding file
     */
    private function syncFilesWithDb()
    {
        $this->entityManager->getRepository(Book::class)->deleteIfNotIn($this->files);
    }

    /**
     * Add new rows to db if there are new files
     */
    private function syncBooks()
    {
        foreach ($this->files as $file) {
            $book = $this->entityManager->getRepository(Book::class)->findOneBy(array('name' => $file));
            if (null !== $book) {
                continue;
            }
            $book = new Book();
            $book->setName($file);
            $book->setUniqueWords($this->wordsCounter->countIn($this->dir . $file));
            $this->entityManager->persist($book);
        }
    }

    /**
     * @param array $files
     * @return bool
     */
    private function isSynced(array $files) : bool
    {
        if ($files === $this->dirImage->getFiles()) {
            return true;
        }
        return false;
    }

    private function mainLogic()
    {
        if (null !== $this->dirImage) {
            if ($this->isSynced($this->files)) {
                $this->io->success('Nothing to update');
                return;
            }

            $this->syncBooks();

            $this->dirImage->setFiles($this->files);
        } else {
            $this->dirImage = new DirectoryImage();
            $this->dirImage->setFiles($this->files);
            $this->entityManager->persist($this->dirImage);
        }
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->init($input, $output);
        $this->syncFilesWithDb();
        $this->mainLogic();

        $this->entityManager->flush();
        $this->io->success('Success');
    }
}
