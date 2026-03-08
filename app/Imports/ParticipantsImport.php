<?php

namespace App\Imports;

use App\Models\Participant;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ParticipantsImport implements
    ToCollection,
    WithChunkReading,
    WithBatchInserts,
    SkipsOnError,
    WithHeadingRow
{
    use SkipsErrors;

    protected int $drawId;

    public function __construct(int $drawId)
    {
        $this->drawId = $drawId;
    }

    public function batchSize(): int
    {
        return 800;
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function collection(Collection $rows)
    {
        $participants = collect();

        foreach ($rows as $row) {

            $name = trim($row['dsr_name'] ?? '');
            $store_code = trim($row['store_code'] ?? '');
            $store_name = trim($row['store_name'] ?? '');
            $base_invoice = trim($row['invoice_number'] ?? '');
            $store_address = trim($row['store_address'] ?? '');
            $no_coupons = (int) ($row['no_of_coupons'] ?? 0);

            if ($no_coupons < 1 || empty($base_invoice)) {
                continue;
            }

            for ($i = 1; $i <= $no_coupons; $i++) {
                $invoice = $no_coupons == 1 ? $base_invoice : "$base_invoice-$i";

                $participants->push([
                    'draw_id' => $this->drawId,
                    'name' => $name,
                    'store_code' => $store_code,
                    'store_name' => $store_name,
                    'store_address' => $store_address,
                    'invoice_number' => $invoice,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $unique = $participants->keyBy('invoice_number')->values();

        if ($unique->isNotEmpty()) {
            Participant::insertOrIgnore($unique->toArray());
        }
    }
}
