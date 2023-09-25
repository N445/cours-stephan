<?php

namespace App\Command;

use App\Entity\Module\SubModule;
use App\Repository\Module\SubModuleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name       : 'app:module:create-sub-module',
    description: 'Créer les sous module de base',
)]
class ModuleCreateSubModuleCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly SubModuleRepository    $subModuleRepository,
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $subModulesNames = [];
        /** @var SubModule $item */
        foreach ($this->subModuleRepository->findAll() as $item) {
            $subModulesNames[$item->getName()] = $item;
        }

        $nbCreated = 0;
        foreach ($this->getSubModules() as $subModule) {
            if (in_array($subModule, $subModulesNames)) {
                continue;
            }
            $subModulesNames[$subModule] = $subModule;
            $nbCreated++;
            $subModule = (new SubModule())->setName($subModule);
            $this->em->persist($subModule);
        }
        $this->em->flush();


        $io->success('Sous modulé créé : ' . $nbCreated);

        return Command::SUCCESS;
    }

    private function getSubModules(): array
    {
        return [
            'Description des composantes (les « ingrédients »)',
            'La méthode (une « recette »)',
            'Application immédiate de la méthode',
            'Constitution d’un modèle complet, rédigé',
            'Un devoir blanc à effectuer par l’étudiant* (corrigé et annoté par mes soins)',
            '3h, 3 sujets d’entrainement au commentaire',
            '3h, 3 sujets d’entrainement à la dissertation ',
            '2 devoirs complets à effectuer par l’étudiant* (corrigés et annotés par mes soins)',
            'Comment faire une fiche sur la base d’un cours',
            'Une application et un modèle de fiche',
            'Comment apprendre une fiche',
            'Traitement des questions de grammaire',
            'Construction de la présentation de l’œuvre choisie',
            '1 oral blanc complet',
            'Application',
            'Le sujet de réflexion et l’invention',
            'Comment répondre efficacement aux questions sur un texte',
            'Les notions essentielles (grammaire, rhétorique)',
            'Remise à niveau de l’orthographe',
            '6 heures, 4 sujets d’entrainement',
            'Analyser une argumentation, un raisonnement',
            'Effectuer une synthèse, un résumé',
            'Construire un raisonnement structuré en arborescence',
            'Comment mieux écrire',
        ];
    }
}
