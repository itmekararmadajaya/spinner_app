<?php

namespace App\Exports;

use App\Models\TestData;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TestDataExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return TestData::select('nama','alamat','no_wa')->where('is_win', true)->get();
    }

    public function headings(): array
    {
        return [
            'nama',
            'alamat',
            'no_wa',
        ];
    }
}
