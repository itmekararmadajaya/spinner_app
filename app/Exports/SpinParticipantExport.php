<?php

namespace App\Exports;

use App\Models\SpinParticipant;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SpinParticipantExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return SpinParticipant::select('full_name','name','city')->where('is_win', true)->get();
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
