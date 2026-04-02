<?php

namespace Database\Seeders;

use App\Models\Dosen;
use App\Models\BidangPenelitian;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class DosenSeeder extends Seeder
{
    public function run(): void
    {
        $filePath = public_path('Data_dosen.xlsx');

        if (!file_exists($filePath)) {
            $this->command->error("File not found: {$filePath}");
            return;
        }

        // Clear existing data
        DB::table('dosen_minor_bidang')->delete();
        Dosen::query()->delete();

        // Load Excel
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        // Remove header row
        $headers = array_shift($rows);

        foreach ($rows as $row) {
            // Ensure row is not completely empty
            if (empty(array_filter($row))) {
                continue;
            }

            $nama = $row[0];
            $gelarAwal = $row[1] ?: null;
            $gelarAkhir = $row[2] ?: null;
            $minatMayor = $row[3];
            $minatMinorRaw = $row[4];

            // Split minat_minor by semicolon or comma (allowing for some variation)
            $minatMinor = $minatMinorRaw ? array_map('trim', explode(';', $minatMinorRaw)) : [];

            // Find or create major field
            $majorField = BidangPenelitian::firstOrCreate(
                ['nama' => $minatMayor],
                [
                    'id' => Str::uuid(),
                    'slug' => Str::slug($minatMayor)
                ]
            );

            // Create Dosen
            $dosen = Dosen::create([
                'id' => Str::uuid(),
                'nama' => $nama,
                'gelar_awal' => $gelarAwal,
                'gelar_akhir' => $gelarAkhir,
                'bidang_penelitian_major_id' => $majorField->id,
            ]);

            // Sync Minors
            if (!empty($minatMinor)) {
                $minorIds = [];
                foreach ($minatMinor as $minorName) {
                    if (empty($minorName)) continue;

                    $minorField = BidangPenelitian::firstOrCreate(
                        ['nama' => $minorName],
                        [
                            'id' => Str::uuid(),
                            'slug' => Str::slug($minorName)
                        ]
                    );
                    $minorIds[] = $minorField->id;
                }
                $dosen->minors()->sync($minorIds);
            }
        }

        $this->command->info('DosenSeeder: Successfully seeded from Excel.');
    }
}
