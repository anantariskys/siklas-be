<?php

namespace App\Repositories;

use App\Models\BidangPenelitian;
use App\Repositories\Interfaces\BidangPenelitianRepositoryInterface;

class BidangPenelitianRepository extends BaseRepository implements BidangPenelitianRepositoryInterface
{
    public function __construct(BidangPenelitian $model)
    {
        parent::__construct($model);
    }

    public function findBySlugWithDosen(string $slug)
    {
        return $this->model->where('slug', $slug)
            ->with([
                'dosenMajor.major:id,nama',
                'dosenMajor.minors:id,nama',
                'dosenMinor.major:id,nama',
                'dosenMinor.minors:id,nama',
            ])
            ->first();
    }
}
