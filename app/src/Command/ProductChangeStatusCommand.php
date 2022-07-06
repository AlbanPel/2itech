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

#[AsCommand(
    name: 'app:productChangeStatus',
    description: 'Change status => best seller, new arrival, featured, spécial offer for all products',
)]
class ProductChangeStatusCommand extends Command
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
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('check', null, InputOption::VALUE_NONE, 'Check all status')
            ->addOption('changeNew', null, InputOption::VALUE_NONE, 'change status new->old')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        //Check
        if ($input->getOption('check')) {
            $products = $this->manager->getRepository(Product::class)->findAll();
            $productCount = count($products);
            $io->writeln('<error>-------------------------</error>');
            $io->writeln('<info>'.$productCount.' Produits dans la base de données! </info>');
            $io->writeln('<info>-------------------------</info>');
        }

        //Change status new->old
        if ($input->getOption('changeNew')) {
            $newProducts = $this->manager->getRepository(Product::class)->findBy(['isNewArrival' => true]);
            $newProductsCount = count($newProducts);

            foreach ($newProducts as $newProduct)
            {
                $newProduct->setIsNewArrival(false);
                $this->manager->persist($newProduct);
                $this->manager->flush();
            }

            $io->writeln('<info>-------------------------</info>');
            $io->writeln('<info>'.$newProductsCount.' Nouveaux Produits dans ont été changés dans la  base de données! </info>');
            $io->writeln('<info>-------------------------</info>');

        }


        return Command::SUCCESS;
    }
}
