<?php

namespace App\Livewire;

use App\Models\SpinParticipant;
use App\Models\TestData;
use Livewire\Component;

class Spinner extends Component
{
    public $finalDatas = [];

    public function mount(){
        $this->finalDatas = $this->getData();
    }

    public function getData(){
        $datas = SpinParticipant::where('is_win', 0)->get();
        $finalDatas = [];
        foreach($datas as $data){
            $finalDatas[] = [
                    'fillStyle' => $data->fill_style,
                    'id' => $data->id,
                    'full_name' => $data->full_name,
                    'name' => $data->name,
                    'city' => $data->city,
                ];
        }
        
        return $finalDatas;
    }

    public function updateWinner($id){
        $data = SpinParticipant::where('id', $id)->first();
        $data->is_win = true;
        $data->save();
    }

    public function render()
    {
        return view('livewire.spinner');
    }
}
