<?php

namespace App\Command;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;

#[AsCommand(
    name: 'app:Backup',
    description: 'Add a short description for your command',
)]
class BackupCommand extends Command
{

    private $manager;
    private $appKernel;
    private $projectDir;

    public function __construct(EntityManagerInterface $manager, KernelInterface $appKernel)
    {
        parent::__construct();
        $this->manager = $manager;
        $this->appKernel = $appKernel;
        $this->projectDir = $appKernel->getProjectDir();
    }


    protected function configure(): void
    {
        $this
            ->addOption('backup', null, InputOption::VALUE_NONE, 'Backup')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        if ($input->getOption('backup')) {
            $products = $this->manager->getRepository(Product::class)->findAll();
            $count= count($products);
            $io->writeln('<info>'.$count.' Produits </info>');
            $objet = [];
            try {
                foreach ($products as $product)
                {
                    $row = [
                        "id" => $product->getId(),
                        "price" => $product->getPrice(),
                    ];
                    array_push($objet, $row);

                }
                $directory = $this->projectDir.'/src/Backup/';
                $path= $directory.'products.json';
                $content = json_encode($objet, JSON_PRETTY_PRINT);
                $filesystem = new Filesystem();
                $filesystem->dumpFile($path, ''.$content);
                $filesystem->chmod($path, 0664);
                $filesystem->chown($path, 1000);
                $filesystem->chgrp($path, 1000);
            }
            catch (\Exception $e) {
                $io->writeln('<error>'.$e->getMessage().'</error>') ;
            }


        }

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
