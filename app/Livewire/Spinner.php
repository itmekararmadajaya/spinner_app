<?php

namespace App\Livewire;

use App\Models\TestData;
use Livewire\Component;

class Spinner extends Component
{
    public $finalDatas = [];

    public function mount(){
        $this->finalDatas = $this->getData();
    }

    public function getData(){
        $datas = TestData::where('is_win', 0)->get();
        $finalDatas = [];
        foreach($datas as $data){
            $finalDatas[] = [
                    'fillStyle' => $data->fill_style,
                    'id' => $data->id,
                    'text' => $data->id."",
                    'nama' => $data->nama,
                    'alamat' => $data->alamat,
                    'no_wa' => $data->no_wa,
                ];
        }
        
        return $finalDatas;
    }

    public function updateWinner($id){
        $data = TestData::where('id', $id)->first();
        $data->is_win = true;
        $data->save();
    }

    public function render()
    {
        return view('livewire.spinner');
    }
}
