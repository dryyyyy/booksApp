<?php

namespace App\Command;

use App\Entity\Book;
use App\Entity\DirectoryImage;
use App\Services\WordsCounter;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class BooksWatcherCommand
 * @package App\Command
 */
class BooksWatcherCommand extends ContainerAwareCommand
{
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
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $dir = getcwd() . '/' .$input->getArgument('folderName') . '/';
        $files =  array_diff(scandir($dir), ['.', '..']);

        $wordsCounter = new WordsCounter();
        $entityManager = $this->getContainer()->get('doctrine')->getManager();
        $entityManager->getRepository(Book::class)->deleteIfNotIn($files);

        $dirImage = $entityManager->getRepository(DirectoryImage::class)->findOneBy(array('id' => '1'));
        if (null === $dirImage) {
            $dirImage = new DirectoryImage();
            $dirImage->setFiles($files);
            $entityManager->persist($dirImage);
        } else {
            if ($files === $dirImage->getFiles()) {
                $io->success('Nothing to update');
                return;
            }

            foreach ($files as $file) {
                $book = $entityManager->getRepository(Book::class)->findOneBy(array('name' => $file));
                if (null !== $book) {
                    continue;
                }
                $book = new Book();
                $book->setName($file);
                $book->setUniqueWords($wordsCounter->countIn($dir . $file));
                $entityManager->persist($book);
            }

            $dirImage->setFiles($files);
        }

        $entityManager->flush();

        $io->success('Success');
    }
}
