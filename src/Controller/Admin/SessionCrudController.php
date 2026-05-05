<?php

namespace App\Controller\Admin;

use App\Entity\Session;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;

class SessionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string { return Session::class; }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title'),
            DateTimeField::new('sessionDate'),
            IntegerField::new('duration'),
            IntegerField::new('capacity'),
            AssociationField::new('coaches')->autocomplete(),
            AssociationField::new('members')->autocomplete(),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return Crud::new()->setPaginatorPageSize(10);
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->add('index', 'detail');
    }
}