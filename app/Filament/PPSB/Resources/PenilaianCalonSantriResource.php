<?php

namespace App\Filament\PPSB\Resources;

use App\Enums\StatusPenerimaan;
use App\Filament\PPSB\Resources\PenilaianCalonSantriResource\Pages;
use App\Models\CalonSantri;
use App\Models\PenilaianCalonSantri;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Mokhosh\FilamentRating\Components\Rating;

class PenilaianCalonSantriResource extends Resource
{
    protected static ?string $model = PenilaianCalonSantri::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('calon_santri_id')
                    ->label('Calon Santri')
                    ->options(CalonSantri::all()->pluck('nama', 'id'))
                    ->required()
                    ->afterStateUpdated(
                        function (Set $set, $state){
                            if(filled($state)){
                                $calonSantri = CalonSantri::where('id', $state)->first();
                                $indikator_penilaian =  $calonSantri->gelombangPendaftaran->pendaftaran->indikator_penilaian;

                                $set('nilai_tes', $indikator_penilaian);
                            }
                        }
                    ),
                Select::make('penguji_id')
                    ->label('Penguji')
                    ->options(User::all()->pluck('nama', 'id'))
                    ->default(auth()->id())
                    ->dehydrated()
                    ->required(),

                TableRepeater::make('nilai_tes')
                    ->label('Nilai Tes')
                    ->schema([
                        TextInput::make('indikator')
                            ->label('Indikator Penilaian'),
                        TextInput::make('bobot')
                            ->label('Bobot'),
                        TextInput::make('nilai')
                            ->label('Nilai Tes'),
                    ])
                    ->addable(false)
                    ->deletable(false)
                    ->reorderable(false)
                    ->cloneable(false)
                    ->collapsible(false)
                    ->columnSpanFull(),

                Forms\Components\Actions::make([
                    Forms\Components\Actions\Action::make('hitung_nilai_akhir')
                        ->label('Hitung Nilai Akhir')
                        ->action(function (Forms\Get $get, Forms\Set $set){
                            if (!filled($get('nilai_tes')) ){
                                Notification::make()
                                    ->title('Isi nilai tes untuk semua indikator!')
                                    ->danger()
                                    ->send();
                            }
                            else {

                                $set('nilai_akhir', '');
                            }
                        })
                ])
                ->columnSpanFull(),

                TextInput::make('nilai_akhir')
                    ->label('Nilai Akhir')
                    ->required(),
                Textarea::make('catatan_penguji')
                    ->label('Catatan Penguji')
                    ->required(),
                Rating::make('rekomendasi_penguji')
                    ->label('Rekomendasi Penguji')
                    ->stars(5)
                    ->required(),
                ToggleButtons::make('status_penerimaan')
                    ->label('Status Penerimaan')
                    ->inline()
                    ->grouped()
                    ->options(StatusPenerimaan::class)
                    ->default(StatusPenerimaan::BELUM_DITENTUKAN->value)
                    ->required(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPenilaianCalonSantri::route('/'),
            'create' => Pages\CreatePenilaianCalonSantri::route('/create'),
            'view' => Pages\ViewPenilaianCalonSantri::route('/{record}'),
            'edit' => Pages\EditPenilaianCalonSantri::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
