<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name       : 'app:user:create',
    description: 'Permet de créer un user',
)]
class UserCreateCommand extends Command
{
    public function __construct(
        private readonly UserPasswordHasherInterface $userPasswordHasher,
        private readonly EntityManagerInterface      $em,
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $helper = $this->getHelper('question');

        $question = new Question('Identifiant : ');
        $login    = $helper->ask($input, $output, $question);

        $question = new Question('Mot de passe : ');
        $password = $helper->ask($input, $output, $question);

        $question = new ConfirmationQuestion(sprintf('%s ? (y/N)', User::ROLE_ADMIN), false);
        $isAdmin  = $helper->ask($input, $output, $question);

        $user = (new User())
            ->setEmail($login)
            ->setPassword($password)
            ->setRoles($isAdmin ? [User::ROLE_ADMIN] : [])
            ->setIsVerified(true)
        ;

        $table = new Table($output);
        $table
            ->setHeaders(['Identifiant', 'Mot de passe', 'Roles'])
            ->setRows([
                          [$user->getUserIdentifier(), $user->getPassword(), implode(',', $user->getRoles())],
                      ])
        ;
        $table->render();

        $question = new ConfirmationQuestion('Confirmer la création (Y/n) ', true);
        if (!$helper->ask($input, $output, $question)) {
            $io->info('Création annulée');
            return Command::SUCCESS;
        }

        $user->setPassword($this->userPasswordHasher->hashPassword($user, $user->getPassword()));

        $this->em->persist($user);
        $this->em->flush();

        $io->success('Utilisateur créer avec succès');
        return Command::SUCCESS;
    }
}
