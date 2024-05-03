<?php

namespace App\Controller\Admin;

use App\Entity\Users;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;

class UsersCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Users::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            TextField::new('email'),
            TextField::new('password'),
            ChoiceField::new('roles', 'Roles')
                ->setPermission(('ROLE_USER'))
                ->allowMultipleChoices()
                ->setChoices([
                    'User' => 'ROLE_USER',
                    'Admin' => 'ROLE_ADMIN'
                ])
                ->renderAsBadges([
                    'ROLE_USER' => 'primary',
                    'ROLE_ADMIN' => 'danger',
                ])
        ];
    }
}
