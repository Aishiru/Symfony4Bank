<?php

namespace App\Service;

use App\Entity\Account;

class TransactionService
{
    public function creditDebitAccounts(Account $creditAccount, Account $debitAccount, $amount)
    {
        $creditAccount->setBalance($creditAccount->getBalance() + $amount);
        $debitAccount->setBalance($debitAccount->getBalance() -  $amount);
    }
}