<?php

namespace App\Controller\Admin;

use App\Entity\Cart\Cart;
use App\Entity\Cart\CartItem;
use App\Service\Cart\EmailPdf\CartEmailSender;
use App\Service\Cart\EmailPdf\CartToPdf;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;

class CartCrudController extends AbstractCrudController
{
    public function __construct(
        private readonly CartToPdf       $cartToPdf,
        private readonly CartEmailSender $cartEmailSender,
    )
    {
    }

    public static function getEntityFqcn(): string
    {
        return Cart::class;
    }

    public function showInvoice(AdminContext $context): BinaryFileResponse
    {
        /** @var Cart $cart */
        $cart     = $context->getEntity()->getInstance();
        $filePath = $this->cartToPdf->getPdf($cart);
        return $this->file($filePath);
    }

    public function sendInvoice(AdminContext $context): RedirectResponse
    {
        /** @var Cart $cart */
        $cart = $context->getEntity()->getInstance();
        $this->cartEmailSender->sendMail($cart);
        $url = $this->container->get(AdminUrlGenerator::class)
                               ->setAction(Action::INDEX)
                               ->generateUrl()
        ;
        $this->addFlash('success', 'Facture renvoyé avec succès');
        return $this->redirect($url);
    }

    public function configureActions(Actions $actions): Actions
    {
        // this action executes the 'renderInvoice()' method of the current CRUD controller
        $showInvoice = Action::new('showInvoice', 'Voir la facture')
                             ->linkToCrudAction('showInvoice')
                             ->displayIf(static function (Cart $entity) {
                                 return $entity->isPaid();
                             })
        ;;
        // this action executes the 'renderInvoice()' method of the current CRUD controller
        $sendInvoice = Action::new('sendInvoice', 'Renvoyer la facture')
                             ->linkToCrudAction('sendInvoice')
                             ->displayIf(static function (Cart $entity) {
                                 return $entity->isPaid();
                             })
        ;;

        return $actions
            ->add(Crud::PAGE_INDEX, $showInvoice)
            ->add(Crud::PAGE_DETAIL, $showInvoice)
            ->add(Crud::PAGE_INDEX, $sendInvoice)
            ->add(Crud::PAGE_DETAIL, $sendInvoice)
            ->remove(Crud::PAGE_INDEX, Action::EDIT)
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            DateTimeField::new('createdAt', 'Créé le'),
            DateTimeField::new('updatedAt', 'Modifié le'),
            AssociationField::new('user', 'Utilisateur'),
            ChoiceField::new('place', 'État')->setChoices([
                                                              'Panier'   => 'cart',
                                                              'Annulé'   => 'cancelled',
                                                              'En cours' => 'pending',
                                                              'Validé'   => 'complete',
                                                          ]),
            MoneyField::new('total', 'Total')->setCurrency('EUR'),
            AssociationField::new('cartItems', 'Modules')->onlyOnIndex(),
            AssociationField::new('cartItems', 'Modules')->onlyOnDetail()->setTemplatePath('admin/cart/cart-item.html.twig')
            ,
        ];
    }
}
