<?php

namespace App\Twig\Components;

use App\Entity\Cart\Cart;
use App\Form\Cart\RecapCartType;
use App\Service\Module\ModuleOccurenceCounter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\Attribute\PreReRender;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\TwigComponent\Attribute\PostMount;
use Symfony\UX\TwigComponent\Attribute\PreMount;

#[AsLiveComponent()]
final class RecapCart extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    /**
     * The initial data used to create the form.
     */
    #[LiveProp(writable: true)]
    public ?Cart $initialFormData = null;
    public array $nbOccurenceByCartItem = [];

    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly ModuleOccurenceCounter $moduleOccurenceCounter
    )
    {
    }

    protected function instantiateForm(): FormInterface
    {
        if (!$this->initialFormData){
            return $this->createForm(RecapCartType::class, $this->initialFormData);
        }
        foreach ($this->initialFormData->getCartItems() as $cartItem) {
            $this->nbOccurenceByCartItem[$cartItem->getId()] = $this->moduleOccurenceCounter->getNbOccurenceBySchedule($cartItem->getSchedule(),$cartItem->getOccurenceId());
        }

        // we can extend AbstractController to get the normal shortcuts
        return $this->createForm(RecapCartType::class, $this->initialFormData);
    }

    public function getCart(): ?Cart
    {
        return $this->initialFormData;
    }

    #[LiveAction]
    public function save(): void
    {
        // Submit the form! If validation fails, an exception is thrown
        // and the component is automatically re-rendered with the errors
        $this->submitForm();

        /** @var Cart $cart */
        $cart = $this->getForm()->getData();
        $this->em->persist($cart);
        $this->em->flush();

//        return $this->redirectToRoute('APP_CHECKOUT_ADDRESS', [
//        ]);
    }
}
