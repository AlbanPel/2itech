<?php

namespace App\DataFixtures;

use App\Entity\Categories;
use App\Entity\Product;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        //users
        $user = new User();
        $user->setEmail('alban@pelissier.dev');
        $user->setRoles(['user']);
        $user->setIsVerified(true);
        $password = $this->hasher->hashPassword($user, 'test1234');
        $user->setPassword($password);
        $user->setUsername('userAlban');
        $user->setLastname('Pelissier');
        $user->setFirstname('Alban');

        $manager->persist($user);
        $manager->flush();

        //admin
        $user = new User();
        $user->setEmail('alban+admin@pelissier.dev');
        $user->setRoles(['admin']);
        $user->setIsVerified(true);
        $password = $this->hasher->hashPassword($user, 'test1234');
        $user->setPassword($password);
        $user->setUsername('adminAlban');
        $user->setLastname('Pelissier');
        $user->setFirstname('Alban');

        $manager->persist($user);
        $manager->flush();

        //categories
        $category = new Categories();
        $category->setName('bonnet');

        $manager->persist($category);
        $manager->flush();

        //Products
        for ($i = 0; $i< 1000; $i++) {
            $product = new Product();
            $product->setName('product '.$i);
            $product->setDescription('descrition '.$i);
            $product->setPrice(rand(100,  5000));
            $product->setIsBestSeller(rand(0 , 1));
            $product->setIsFeatured(rand(0 , 1));
            $product->setIsSpecialOffer(rand(0 , 1));
            $product->setIsNewArrival(rand(0 , 1));
            $product->setQuantity(rand(5 , 50));

            $manager->persist($product);
        }

        $manager->flush($product);

    }
}
