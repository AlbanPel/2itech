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

#[AsCommand(
    name: 'app:restore',
    description: 'Add a short description for your command',
)]
class RestoreCommand extends Command
{
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct();
        $this->manager = $manager;
    }
    protected function configure(): void
    {
        $this
            ->addOption('restore', null, InputOption::VALUE_NONE, 'restore backup')
            ->addOption('flush', null, InputOption::VALUE_NONE, 'force flush')

        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);



        if ($input->getOption('restore')) {
            //Product
            $filesystem = new Filesystem();
            if (!$filesystem->exists($this->path('products'))) {
                $io->writeln('<error>aucune sauvegarde de trouvée</error>');
            }
            try {
                $products = $this->getJsonFile('products');
                foreach ($products as $product ) {
                    $MainProduct = new Product();
                    $MainProduct->setprice($product['price']);
                    $this->manager->persist($MainProduct);
                    if ($input->getOption('flush')) {
                        $this->manager->flush();
                        $io->writeln('<info>Products restore dans bdd</info>');
                    }
                }
                $io->writeln('<info>Products ok</info>');
            }
            catch (\Exception $e) {
                $io->writeln('<error>'.$e->getMessage().'</error>') ;
            }

        }

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }

    private function path($name){
        $path= '/var/www/symfony/src/Backup/'.$name.'.json';
        return $path;
    }
    private function getJsonFile($file) {
        $json = file_get_contents('/var/www/symfony/src/Backup/'.$file .'.json');
        return json_decode($json, true);
    }

}