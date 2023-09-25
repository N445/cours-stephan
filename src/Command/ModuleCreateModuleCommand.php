<?php

namespace App\Command;

use App\Entity\Module\Module;
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
    name       : 'app:module:create-module',
    description: 'Créer les module de base',
)]
class ModuleCreateModuleCommand extends Command
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
        foreach ($this->getModules() as $moduleName => $subModules) {

            $module = (new Module())
                ->setName($moduleName)
            ;

            foreach ($subModules as $subModule) {
                if (in_array($subModule, $subModulesNames)) {
                    $module->addSubModule($subModulesNames[$subModule]);
                    continue;
                }
                $nbCreated++;
                $subModule                              = (new SubModule())->setName($subModule);
                $subModulesNames[$subModule->getName()] = $subModule;
                $module->addSubModule($subModule);
            }


            $this->em->persist($module);
        }
        $this->em->flush();


        $io->success('Modulé créé : ' . $nbCreated);

        return Command::SUCCESS;
    }

    private function getModules(): array
    {

        return [
            "Module « commentaire composé »" => [
                "Description des composantes (les « ingrédients »)",
                "La méthode (une « recette »)",
                "Application immédiate de la méthode",
                "Constitution d’un modèle complet, rédigé",
                "Un devoir blanc à effectuer par l’étudiant* (corrigé et annoté par mes soins)"
            ],
            "Module « dissertation bac français »" => [
                "Description des composantes (les « ingrédients »)",
                "La méthode (une « recette »)",
                "Application immédiate de la méthode",
                "Constitution d’un modèle complet, rédigé",
                "Un devoir blanc à effectuer par l’étudiant* (corrigé et annoté par mes soins)"
            ],
            "Module « entrainement à l’écrit de français »" => [
                "3h, 3 sujets d’entrainement au commentaire",
                "3h, 3 sujets d’entrainement à la dissertation",
                "2 devoirs complets à effectuer par l’étudiant* (corrigés et annotés par mes soins)"
            ],
            "Module « préparation oral du bac »" => [
                "Comment faire une fiche sur la base d’un cours",
                "Une application et un modèle de fiche",
                "Comment apprendre une fiche",
                "Traitement des questions de grammaire",
                "Construction de la présentation de l’œuvre choisie",
                "1 oral blanc complet"
            ],
            "Module « dissertation de philo »" => [
                "Description des composantes (les « ingrédients »)",
                "La méthode (une « recette »)",
                "Application",
                "Constitution d’un modèle complet, rédigé",
                "Un devoir blanc à effectuer par l’étudiant* (corrigé et annoté par mes soins)"
            ],
            "Module « l’essentiel du brevet »" => [
                "Le sujet de réflexion et l’invention",
                "Comment répondre efficacement aux questions sur un texte",
                "Les notions essentielles (grammaire, rhétorique)",
                "Remise à niveau de l’orthographe"
            ],
            "Module « préparation de l’épreuve du brevet »" => [
                "6 heures, 4 sujets d’entrainement",
                "2 devoirs complets à effectuer par l’élève* (corrigés et annotés par mes soins)"
            ],
            "Module art de la rhétorique pour adultes (remise à niveau, préparation de concours, épanouissement personnel)" => [
                "Analyser une argumentation, un raisonnement",
                "Effectuer une synthèse, un résumé",
                "Construire un raisonnement structuré en arborescence",
                "Comment mieux écrire"
            ]
        ];
    }
}
