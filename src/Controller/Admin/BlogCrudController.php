<?php

namespace App\Controller\Admin;

use App\Entity\Blog\Blog;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class BlogCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Blog::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            ImageField::new('imageName', 'Image')->setBasePath('/uploads/blog')->hideOnForm(),
            Field::new('imageFile', 'Image')->setFormType(VichImageType::class)->onlyOnForms(),
            TextField::new('title','Titre'),
            TextEditorField::new('content','Contenu'),
        ];
    }
}
