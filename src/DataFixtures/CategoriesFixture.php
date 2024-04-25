<?php
namespace App\DataFixtures;

use App\Entity\Categories;
use App\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;
class CategoriesFixture extends AbstractFixture
{
   public function load(ObjectManager $manager)
   {
      for ($i = 0; $i < 5; $i ++) {
         $category = new Categories();
         $category->setTitle($this->faker->word());
         $manager->persist($category);
         $this->setReference('category_' . $i, $category);
      }
   $manager->flush();
   }
}