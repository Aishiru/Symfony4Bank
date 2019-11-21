<?php

namespace App\Command;

use App\Entity\Account;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AccountBalanceCalculateCommand extends Command
{
    protected static $defaultName = 'account:balance:calculate';

    private $entityManager;

    public function __construct(EntityManagerInterface $em)
    {
        $this->entityManager = $em;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('account_id', InputArgument::REQUIRED, 'Account ID')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $accountId = $input->getArgument('account_id');

        $account = $this->entityManager->getRepository(Account::class)->find($accountId);

        $balance = 0;

        $credits = $account->getCreditTransactions();
        $debits = $account->getDebitTransactions();

        foreach ($credits as $credit) {
            $balance += $credit->getAmount();
        }

        foreach ($debits as $debit) {
            $balance -= $debit->getAmount();
        }

        $account->setBalance($balance);

        $this->entityManager->flush();

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return 0;
    }
}
