<?php
namespace App\Collections;

use App\Models\Referral;
use App\Models\User;

class ReferralsCollection
{
    public $referralsTree = [];
    public $maxDepth;

    public function __construct(User $user, $maxDepth = 5)
    {
        $this->maxDepth = 5;
        $this->next($user);
    }

    private function next(User $user,$depth=0)
    {
        $referrals = $user->getReferrals();
        if (count($referrals)) {
            $depth++;
            if ($depth > $this->maxDepth)
                return;
            foreach ($referrals as $referral)
            {
                $this->referralsTree[] = new Referral($referral,$depth);
                $this->next($referral,$depth);
            }
        }
    }
}