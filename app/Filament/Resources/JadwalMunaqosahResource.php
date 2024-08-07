<?php

namespace App\Filament\Resources;

use App\Enums\StatusPondok;
use App\Filament\Resources\JadwalMunaqosahResource\Pages\CreateJadwalMunaqosah;
use App\Filament\Resources\JadwalMunaqosahResource\Pages\EditJadwalMunaqosah;
use App\Filament\Resources\JadwalMunaqosahResource\Pages\ListJadwalMunaqosahs;
use App\Filament\Resources\JadwalMunaqosahResource\Pages\ManageJadwalMunaqosahPlotJadwalMunaqosah;
use App\Filament\Resources\JadwalMunaqosahResource\Pages\ViewJadwalMunaqosah;
use App\Filament\Resources\JadwalMunaqosahResource\Widgets\JadwalMunaqosahCalendarWidget;
use App\Models\JadwalMunaqosah;
use App\Models\MateriMunaqosah;
use App\Models\PlotJadwalMunaqosah;
use App\Models\User;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Carbon\Carbon;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
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
    protected static ?string $recordTitleAttribute = 'recordTitle';

    protected static ?string $navigationLabel = 'Jadwal Munaqosah';
    protected static ?string $navigationGroup = 'Manajemen Munaqosah';
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?int $navigationSort = 62;

    public static function form(Form $form): Form
    {
        return $form
            ->schema(JadwalMunaqosah::getForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->withCount([
                'plotJadwalMunaqosah as total_plotjadwalmunaqosah',
                'plotJadwalMunaqosah as terlaksana_plotjadwalmunaqosah' => function ($query) {
                    $query->where('status_terlaksana', true);
                },
            ]))
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('materiMunaqosah.recordTitle')
                    ->label('Materi Munaqosah')
                    ->searchable(),
                Tables\Columns\TextColumn::make('waktu')
                    ->label('Waktu Munaqosah')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('maksimal_pendaftar')
                    ->label('Maks Pendaftar')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_plotjadwalmunaqosah')
                    ->label('Pendaftar')
                    ->numeric(),
                Tables\Columns\TextColumn::make('terlaksana_plotjadwalmunaqosah')
                    ->label('Terlaksana')
                    ->numeric(),
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
            ])
            ->groups([
                Group::make('materiMunaqosah.kelas')
                    ->label('Kelas')
                    ->groupQueryUsing(fn (Builder $query) => $query->groupBy('materiMunaqosah.kelas')),
            ])
            ->filters([

            ])
            ->actions([
                Tables\Actions\Action::make('ambil_jadwal')
                    ->label('Ambil Jadwal')
                    ->visible(function (JadwalMunaqosah $record) {
                        $user = auth()->user();
                        $hasPlot = PlotJadwalMunaqosah::where('user_id', $user->id)
                            ->exists();
                        $isRegistrationOpen = $record->batas_akhir_pendaftaran >= now();
                        return !$hasPlot && $isRegistrationOpen;
                    })
                    ->action(
                        function (JadwalMunaqosah $record) {
                            PlotJadwalMunaqosah::create([
                                'jadwal_munaqosah_id' => $record->id,
                                'user_id' => auth()->user()->id,
                                'status_terlaksana' => false,
                            ]);
                            Notification::make('ambil_jadwal')
                                ->title('Jadwal munaqosah sukses diambil!')
                                ->success()
                                ->send();
                        }
                    ),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation(),
                ]),
            ])
            ->selectCurrentPageOnly();
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            ViewJadwalMunaqosah::class,
            ManageJadwalMunaqosahPlotJadwalMunaqosah::class,
        ]);
    }

    public static function getRelations(): array
    {
        return [

        ];
    }

    public static function getWidgets(): array
    {
        return [
            JadwalMunaqosahCalendarWidget::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListJadwalMunaqosahs::route('/'),
            'create' => CreateJadwalMunaqosah::route('/create'),
            'view' => ViewJadwalMunaqosah::route('/{record}'),
            'edit' => EditJadwalMunaqosah::route('/{record}/edit'),
            'plot' => ManageJadwalMunaqosahPlotJadwalMunaqosah::route('/{record}/plot'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
    }
}
