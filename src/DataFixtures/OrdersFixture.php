<?php
namespace App\DataFixtures;

use App\Entity\Orders;
use App\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;


class OrdersFixture extends AbstractFixture implements DependentFixtureInterface

    {
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i ++) {
            $order = new Orders();
            $order->setUsers($this->getReference('user_' . $this->faker->numberBetween(0, 4)));
            $order->setCreatedAt($this->faker->dateTimeBetween('-1 years', 'now'));
            $order->setOrderNumber($this->faker->randomNumber(5, true));
            $manager->persist($order);
        }
    $manager->flush();
    }

    public function getDependencies() {
        return [UsersFixture::class];
    }
}