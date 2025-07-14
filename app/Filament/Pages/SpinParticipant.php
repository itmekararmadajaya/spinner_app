<?php

namespace App\Filament\Pages;

use App\Exports\SpinParticipantExport;
use App\Models\SpinParticipant as ModelsSpinParticipant;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Maatwebsite\Excel\Facades\Excel;

class SpinParticipant extends Page implements HasForms, HasTable
{
    use InteractsWithTable, InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.spin-participant';

    public function syncSpreadsheetData(){
        $fileUrl = "https://docs.google.com/spreadsheets/d/e/2PACX-1vSUPYrySGrvpFfcebZOIQDRbxeLAWjXvQER_PopiuUTtT9SFAZoweH-Osh_m-3h18yO9_W1FjevUyb1/pub?gid=504572525&single=true&output=csv";

        $handle = fopen($fileUrl, 'r');
        $datas = [];

        if ($handle !== false) {
            while (($row = fgetcsv($handle, 1000, ",")) !== false) {
                $datas[] = $row;
            }
            fclose($handle);
        }

        foreach($datas as $key => $data){
            if ($key != 0) {
            
                $exists = ModelsSpinParticipant::
                    where('full_name', $data[1])
                    ->where('name', $data[2])
                    ->where('city', $data[3])
                    ->exists();

                if(!$exists){
                    $newData = new ModelsSpinParticipant();
                    $newData->full_name = $data[1];
                    $newData->name = $data[2];
                    $newData->city = $data[3]; 
                    $newData->fill_style = $this->getRandomColor();
                    $newData->save();
                }
            }
        }

        Notification::make()
        ->title('Success sync data')
        ->success()
        ->send();

        $this->resetTable();
    }

    public function getRandomColor() {
        $r = random_int(150, 255);
        $g = random_int(150, 255);
        $b = random_int(150, 255);
        return sprintf("#%02X%02X%02X", $r, $g, $b);
    }

    public function resetWinner(){
        ModelsSpinParticipant::query()->update(['is_win' => false]);

        Notification::make()
        ->title('Success reset winner')
        ->success()
        ->send();

        $this->resetTable();
    }

    public function exportWinner(){
        try {
            return Excel::download(new SpinParticipantExport, 'Report Winner.xlsx');
        } catch (\Throwable $th) {
            return Notification::make()
            ->title('Error export winner')
            ->danger()
            ->send();
        }
    }

    public function cancleWinner($data){
        $data->is_win = false;
        $data->save();

        $this->resetTable();

        return Notification::make()
            ->title('Success reset winner')
            ->success()
            ->send();
    }

    public function makeWinner($data){
        $data->is_win = true;
        $data->save();

        $this->resetTable();

        return Notification::make()
            ->title('Success make winner')
            ->success()
            ->send();
    }

    public function table(Table $table): Table {
        return $table->query(ModelsSpinParticipant::query())
            ->headerActions([
                Action::make("Export Winner")->color("gray")->action(fn() => $this->exportWinner()),
                Action::make("Reset Winner")->color("gray")->action(fn() => $this->resetWinner())->requiresConfirmation(),
                Action::make("Sync Spreadsheet Data")->color("success")->action(fn() => $this->syncSpreadsheetData())                
            ])
            ->filters([
                TernaryFilter::make("is_win")
            ])
            ->columns([
                TextColumn::make('id')->searchable()->sortable(),
                TextColumn::make('full_name')->searchable()->sortable(),
                TextColumn::make('name')->searchable(),
                TextColumn::make('city')->searchable(),
                IconColumn::make('is_win')->boolean()
            ])
            ->actions([
                Action::make('Cancle Win')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(fn ($record) => $this->cancleWinner($record)),
                Action::make('Make Win')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(fn ($record) => $this->makeWinner($record))
            ]);
    }
}
