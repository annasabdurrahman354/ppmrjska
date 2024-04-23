<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\JadwalMunaqosahResource\Pages;
use App\Filament\Admin\Resources\JadwalMunaqosahResource\Widgets\CalendarWidget;
use App\Models\JadwalMunaqosah;
use App\Models\MateriMunaqosah;
use App\Models\User;
use Awcodes\Shout\Components\Shout;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class JadwalMunaqosahResource extends Resource
{
    protected static ?string $model = JadwalMunaqosah::class;
    protected static ?string $slug = 'jadwal-munaqosah';
    protected static ?string $modelLabel = 'Jadwal Munaqosah';
    protected static ?string $pluralModelLabel = 'Jadwal Munaqosah';
    protected static ?string $navigationLabel = 'Jadwal Munaqosah';
    protected static ?string $recordTitleAttribute = 'recordTitle';

    protected static ?string $navigationGroup = 'Manajemen Munaqosah';
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?int $navigationSort = 62;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Detail Munaqosah')
                    ->schema([
                        Select::make('materi_munaqosah_id')
                            ->label('Materi Munaqosah')
                            ->options(MateriMunaqosah::all()->pluck('recordTitle', 'id'))
                            ->searchable()
                            ->columnSpanFull()
                            ->required(),
                        DateTimePicker::make('waktu')
                            ->label('Waktu Munaqosah')
                            ->required(),
                        TextInput::make('maksimal_pendaftar')
                            ->label('Maksimal Pendaftar')
                            ->required()
                            ->numeric()
                            ->minValue(fn (Get $get) => count($get('plotJadwalMunaqosah')))
                            ->live(),
                        DateTimePicker::make('batas_awal_pendaftaran')
                            ->label('Batas Mulai Pendaftaran')
                            ->required(),
                        DateTimePicker::make('batas_akhir_pendaftaran')
                            ->label('Batas Akhir Pendaftaran')
                            ->required(),
                    ]),
                
                Section::make('Plot Jadwal Munaqosah')
                    ->schema([
                        Shout::make('st-empty')
                            ->content('Belum ada pendaftar!')
                            ->type('info')
                            ->color(Color::Yellow)
                            ->visible(fn (Get $get) => count($get('plotJadwalMunaqosah')) == 0 || $get('plotJadwalMunaqosah') == null),
                        Repeater::make('plotJadwalMunaqosah')
                            ->hiddenLabel()
                            ->relationship('plotJadwalMunaqosah')
                            ->schema([
                                Select::make('user_id')
                                    ->hiddenLabel()
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->placeholder('Pilih santri sesuai kelas munaqosah...')
                                    ->getSearchResultsUsing(function (string $search, Get $get): array{
                                        $materiMunaqosah = MateriMunaqosah::find($get('../../materi_munaqosah_id'));
                                        $kelas = $materiMunaqosah->kelas;
                                        return User::where('kelas', $kelas)
                                            ->where('nama', 'like', "%{$search}%")
                                            ->where('status_pondok', 'aktif')
                                            ->where('tanggal_lulus_pondok', null)
                                            ->limit(20)
                                            ->pluck('nama', 'id')
                                            ->toArray();
                                    })
                                    ->getOptionLabelUsing(fn ($value): ?string => User::find($value)?->nama)
                                    ->columnSpan(4),
                                Toggle::make('status_terlaksana')
                                    ->label('Sudah Terlaksana?')
                                    ->default(false)
                                    ->required()
                                    ->columnSpan(1),
                            ])
                            ->columns(5)
                            ->addable()
                            ->addActionLabel('Tambah Pendaftar +')
                            ->maxItems(fn (Get $get) => $get('maksimal_pendaftar'))
                            ->live()
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('materiMunaqosah.recordTitle')
                    ->label('Materi Munaqosah')
                    ->searchable(),
                Tables\Columns\TextColumn::make('waktu')
                    ->label('Waktu Munaqosah')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('maksimal_pendaftar')
                    ->label('Maksimal Pendaftar')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('batas_awal_pendaftaran')
                    ->label('Batas Mulai Pendaftaran')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('batas_akhir_pendaftaran')
                    ->label('Batas Akhir Pendaftaran')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->groups([
                Group::make('materiMunaqosah.kelas')
                    ->label('Kelas')
                    ->groupQueryUsing(fn (Builder $query) => $query->groupBy('materiMunaqosah.kelas')),
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

    public static function getWidgets(): array
    {
        return [
            CalendarWidget::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJadwalMunaqosahs::route('/'),
            'create' => Pages\CreateJadwalMunaqosah::route('/create'),
            'view' => Pages\ViewJadwalMunaqosah::route('/{record}'),
            'edit' => Pages\EditJadwalMunaqosah::route('/{record}/edit'),
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
