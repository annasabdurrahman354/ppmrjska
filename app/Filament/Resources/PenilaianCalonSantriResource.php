<?php

namespace App\Filament\Resources;

use App\Enums\StatusPenerimaan;
use App\Filament\Resources\PenilaianCalonSantriResource\Pages\CreatePenilaianCalonSantri;
use App\Filament\Resources\PenilaianCalonSantriResource\Pages\EditPenilaianCalonSantri;
use App\Filament\Resources\PenilaianCalonSantriResource\Pages\ListPenilaianCalonSantri;
use App\Filament\Resources\PenilaianCalonSantriResource\Pages\ViewPenilaianCalonSantri;
use App\Models\CalonSantri;
use App\Models\PenilaianCalonSantri;
use App\Models\User;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Filament\Forms;
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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Mokhosh\FilamentRating\Components\Rating;

class PenilaianCalonSantriResource extends Resource
{
    protected static ?string $model = PenilaianCalonSantri::class;
    protected static ?string $slug = 'penilaian-casanru';
    protected static ?string $modelLabel = 'Penilaian Casanru';
    protected static ?string $pluralModelLabel = 'Penilaian Casanru';
    protected static ?string $recordTitleAttribute = 'recordTitle';

    protected static ?string $navigationLabel = 'Penilaian Casanru';
    protected static ?string $navigationGroup = 'Manajemen Pendaftaran';
    protected static ?string $navigationIcon = 'heroicon-o-identification';
    protected static ?int $navigationSort = 42;

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
                    ->required(),

                TableRepeater::make('nilai_tes')
                    ->label('Nilai Tes')
                    ->headers([
                        Header::make('Indikator Penilaian'),
                        Header::make('Nilai Tes')
                    ])
                    ->schema([
                        TextInput::make('indikator')
                            ->label('Indikator Penilaian'),
                        TextInput::make('nilai')
                            ->label('Nilai Tes')
                            ->numeric()
                            ->helperText('Masukkan nilai akhir untuk setiap indikator penilaian.')
                    ])
                    ->addable(false)
                    ->deletable(false)
                    ->reorderable(false)
                    ->cloneable(false)
                    ->collapsible(false)
                    ->columnSpanFull()
                    ->extraActions([
                        Forms\Components\Actions\Action::make('hitungNilaiAkhir')
                            ->icon('heroicon-m-inbox-arrow-down')
                            ->action(function (Forms\Get $get, Forms\Set $set){
                                if (!filled($get('nilai_tes')) ){
                                    Notification::make()
                                        ->title('Isi nilai tes untuk semua indikator!')
                                        ->danger()
                                        ->send();
                                }
                                else {
                                    ## TODO HITUNG NILAI AKHIR
                                    $set('nilai_akhir', 90);
                                }
                            })
                    ]),

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
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),

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
            'index' => ListPenilaianCalonSantri::route('/'),
            'create' => CreatePenilaianCalonSantri::route('/create'),
            'view' => ViewPenilaianCalonSantri::route('/{record}'),
            'edit' => EditPenilaianCalonSantri::route('/{record}/edit'),
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
