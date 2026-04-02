<?php

namespace App\Services;

use App\Repositories\Interfaces\DosenRepositoryInterface;

class DosenService extends BaseService
{
    protected $bidangRepository;

    public function __construct(
        DosenRepositoryInterface $repository,
        \App\Repositories\Interfaces\BidangPenelitianRepositoryInterface $bidangRepository
    ) {
        parent::__construct($repository);
        $this->bidangRepository = $bidangRepository;
    }

    public function listDosen(array $params, int $limit = 10)
    {
        return $this->repository->getFilteredPaginated($params, $limit);
    }

    public function getByBidang(string $bidangSlug)
    {
        $bidang = $this->bidangRepository->findBySlugWithDosen($bidangSlug);

        if (!$bidang) return null;

        $allDosen = $bidang->dosenMajor
            ->merge($bidang->dosenMinor)
            ->unique('id')
            ->map(function ($d) {
                return [
                    'nama' => $d->nama,
                    'gelar' => trim($d->gelar_awal . ' ' . $d->nama . ', ' . $d->gelar_akhir),
                    'major' => $d->major?->nama,
                    'minor' => $d->minors->pluck('nama')->values(),
                ];
            })
            ->values();

        return [
            'bidang' => $bidang->nama,
            'dosen' => $allDosen,
        ];
    }
}
