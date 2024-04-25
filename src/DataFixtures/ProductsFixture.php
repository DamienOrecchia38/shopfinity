<?php
namespace App\DataFixtures;

use App\Entity\Products;
use App\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;
// use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ProductsFixture extends AbstractFixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i ++) {
            $product = new Products();
            $product->setTitle($this->faker->sentence(3));
            $product->setPrice($this->faker->randomFloat(2, 1, 1000));
            $product->setDescription($this->faker->paragraph(3));
            $product->setAvailable($this->faker->boolean(90));
            $product->setCategories($this->getReference('category_' . $this->faker->numberBetween(0, 4)));
            $manager->persist($product);
        }
    $manager->flush();
    }
}
