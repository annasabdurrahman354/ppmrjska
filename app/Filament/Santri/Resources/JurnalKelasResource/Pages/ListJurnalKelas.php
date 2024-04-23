<?php

namespace App\Filament\Santri\Resources\JurnalKelasResource\Pages;

use App\Enums\StatusPondok;
use App\Filament\Santri\Resources\JurnalKelasResource;
use App\Models\User;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListJurnalKelas extends ListRecords
{
    protected static string $resource = JurnalKelasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Action::make('createUsingQRCode')
                ->label('Buat dengan QRCode')
                ->hidden(!auth()->user()->can('create_jurnal::kelas'))
                ->action('createUsingQRCode')
                ->color('secondary'),

            /*Action::make('isiRekaman')
                ->label('Isi Rekaman')
                ->color('secondary')
                ->form([
                    DatePicker::make('tanggal')
                        ->label('Tanggal KBM')
                        ->required(),

                    Select::make('sesi')
                        ->label('Sesi KBM')
                        ->required()
                        ->options(Sesi::class),
                    
                    Select::make('kelas')
                        ->label('Kelas')
                        ->multiple()
                        ->options(
                            User::where('kelas', '!=', 'admin')
                                ->where('status_pondok', StatusPondok::AKTIF->value)
                                ->where('tanggal_lulus_pondok', null)
                                ->select('kelas')
                                ->orderBy('kelas')
                                ->distinct()
                                ->get()
                                ->pluck('kelas', 'kelas')
                        ),

                    ComponentsActions::make([
                        FormAction::make('findJurnalKelas')
                            ->label('Cari Jurnal Kelas')
                            ->icon('heroicon-o-magnifying-glass')
                            ->color('info')
                            ->action(function (Get $get, Set $set) {
                                $jurnalKelas = JurnalKelas::where('tanggal', $get('tanggal'))
                                    ->where('sesi', $get('sesi'))
                                    ->whereJsonContains('kelas', $get('kelas'))
                                    ->orderBy('tanggal')
                                    ->select('id', 'sesi', 'kelas', 'perekap_id')
                                    ->get()
                                    ->toArray();
                                $set('jurnalKelas', $jurnalKelas);
                            }),
                    ]),
                        
                    Repeater::make('jurnalKelas')
                        ->hiddenLabel()
                        ->addable(false)
                        ->deletable(false)
                        ->reorderable(false)
                        ->defaultItems(0)
                        ->live()
                        ->schema([
                            Select::make('sesi')
                                ->label('Sesi KBM')
                                ->options(Sesi::class)
                                ->disabled(),
                            
                            Select::make('kelas')
                                ->label('Kelas')
                                ->multiple()
                                ->options(
                                    User::where('kelas', '!=', 'admin')
                                        ->where('status_pondok', StatusPondok::AKTIF->value)
                                        ->where('tanggal_lulus_pondok', null)
                                        ->select('kelas')
                                        ->orderBy('kelas')
                                        ->distinct()
                                        ->get()
                                        ->pluck('kelas', 'kelas')
                                )
                                ->disabled(),
                            
                            Select::make('perekap_id')
                                ->label('Perekap')
                                ->required()
                                ->disabled()
                                ->options(
                                    User::select('nama', 'id')
                                        ->distinct()
                                        ->get()
                                        ->pluck('nama', 'id')
                                )
                                ->preload()
                                ->columnSpanFull(),       
                        ])
                ])
                ->action(function (array $data): void {
                    
                })
            */

        ];
    }

    
    public function createUsingQRCode()
    {
        $resources = static::getResource();
        $this->redirect($resources::getUrl('qr-code-create'));
    }

    public function getTabs(): array
    {
        $semuaKelas = User::where('tanggal_lulus_pondok', null)
                            ->where('status_pondok', StatusPondok::AKTIF->value)
                            ->where('kelas', '!=', 'admin')
                            ->orderBy('kelas')
                            ->select('kelas')
                            ->distinct()
                            ->get()
                            ->pluck('kelas');
                                    
        $tabs = [
            null => Tab::make('All'),
        ];
        foreach ($semuaKelas as $kelas){
            $tabs[$kelas] = Tab::make()->query(fn ($query) => $query->whereJsonContains('kelas', (string) $kelas));
        }
        return $tabs;
    }

    protected function getTableQuery() : Builder
    {
        $user =  auth()->user();
        $model = (new (static::$resource::getModel()))->query();
        $model = $model->where('jenis_kelamin', $user->jenis_kelamin);

        return $model;
    }
}
