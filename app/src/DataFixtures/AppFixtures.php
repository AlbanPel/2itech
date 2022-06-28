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
        $product = new Product();
        $product->setName('Bonnets tricoté pour femmes');
        $product->setDescription('Bonnets tricoté pour femmes, Bonnet de marque, épais et chaud, tête de mort en tricot, lettre, Bonnet, ensembles d\'équitation en plein air');
        $product->setPrice('12');
        $product->setIsBestSeller(true);
        $product->setIsFeatured(false);
        $product->setIsSpecialOffer(false);
        $product->setQuantity(10);

        $manager->persist($product);
        $manager->flush($product);

    }
}
