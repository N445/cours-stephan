<?php

namespace App\Controller\Admin\Module;

use App\Entity\Module\Schedule;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ColorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ScheduleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Schedule::class;
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param Schedule               $entityInstance
     *
     * @return void
     */
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $entityInstance->setEndAt($entityInstance->getEndAt()->setTime(23,59,59));
        $entityManager->persist($entityInstance);
        $entityManager->flush();
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param Schedule               $entityInstance
     *
     * @return void
     */
    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $entityInstance->setEndAt($entityInstance->getEndAt()->setTime(23,59,59));
        $entityManager->persist($entityInstance);
        $entityManager->flush();
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Nom'),
            ColorField::new('color', 'Couleur'),
            DateField::new('startAt', 'Début'),
            DateField::new('endAt', 'Fin'),
        ];
    }
}
