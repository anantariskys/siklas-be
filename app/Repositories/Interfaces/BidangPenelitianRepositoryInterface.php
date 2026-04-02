<?php

namespace App\Repositories\Interfaces;

interface BidangPenelitianRepositoryInterface extends BaseRepositoryInterface
{
    public function findBySlugWithDosen(string $slug);
}
