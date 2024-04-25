<?php
namespace App\DataFixtures;

use App\Entity\Users;
use App\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;
class UsersFixture extends AbstractFixture
{
    public function load(ObjectManager $manager)
    {
        $adminUser = new Users();
        $adminUser->setEmail('damien@gmail.com');
        $adminUser->setRoles(['ROLE_ADMIN']);
        $adminUser->setName('Damien');
        $adminUser->setPassword('$2y$13$U8SGgqEF/Z4Jhaeh/7888esUUEkaqkUiHpdXdBSCpVntyJojHPD/S');
        $manager->persist($adminUser);

        for ($i = 0; $i < 5; $i ++) {
            $user = new Users();
            $user->setName($this->faker->name());
            $user->setEmail($this->faker->email());
            $user->setPassword($this->faker->password());
            $user->setRoles(['ROLE_USER']);
            $user->setCreatedAt($this->faker->dateTimeBetween('-1 years', 'now'));

            $user->setPassword(
                $this->passwordHasher->hashPassword($user, 'password ?')
              );
              
            $manager->persist($user);
            $this->setReference('user_'. $i, $user);
        }
    $manager->flush();
    }
}