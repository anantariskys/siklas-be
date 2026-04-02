<?php

namespace App\Services;

use App\Repositories\Interfaces\BidangPenelitianRepositoryInterface;

class BidangPenelitianService extends BaseService
{
    public function __construct(BidangPenelitianRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }
}
