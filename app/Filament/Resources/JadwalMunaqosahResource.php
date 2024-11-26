<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JadwalMunaqosahResource\Pages\CreateJadwalMunaqosah;
use App\Filament\Resources\JadwalMunaqosahResource\Pages\EditJadwalMunaqosah;
use App\Filament\Resources\JadwalMunaqosahResource\Pages\ListJadwalMunaqosahs;
use App\Filament\Resources\JadwalMunaqosahResource\Pages\ManageJadwalMunaqosahPlotJadwalMunaqosah;
use App\Filament\Resources\JadwalMunaqosahResource\Pages\ViewJadwalMunaqosah;
use App\Filament\Resources\JadwalMunaqosahResource\Widgets\JadwalMunaqosahCalendarWidget;
use App\Models\JadwalMunaqosah;
use App\Models\PlotJadwalMunaqosah;
use Carbon\Carbon;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

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

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema(JadwalMunaqosah::getInfolist());
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
            ->columns(JadwalMunaqosah::getColumns())
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
                        $now = Carbon::now();

                        $existsFalseStatus = PlotJadwalMunaqosah::where('user_id', $user->id)
                            ->where('status_terlaksana', false)
                            ->whereHas('jadwalMunaqosah', function ($query) use ($record, $now) {
                                $query->where('materi_munaqosah_id', $record->materi_munaqosah_id)
                                    ->where('waktu', '>', $now);
                            })
                            ->exists();

                        $existsTrueStatus = PlotJadwalMunaqosah::where('user_id', $user->id)
                            ->where('status_terlaksana', true)
                            ->whereHas('jadwalMunaqosah', function ($query) use ($record, $now) {
                                $query->where('materi_munaqosah_id', $record->materi_munaqosah_id)
                                    ->where('waktu', '<', $now);
                            })
                            ->exists();

                        $isMatchingClass = $record->materiMunaqosah->kelas === $user->kelas;

                        return !$existsFalseStatus && !$existsTrueStatus && $isMatchingClass;
                    })
                    ->action(
                        function (JadwalMunaqosah $record) {
                            $user = auth()->user();
                            $plots = PlotJadwalMunaqosah::where('user_id', $user->id)
                                ->whereHas('jadwalMunaqosah', function ($query) use ($record) {
                                    $query->where('materi_munaqosah_id', $record->materi_munaqosah_id);
                                })
                                ->get();

                            if ($plots->isNotEmpty()) {
                                // Ambil plot pertama untuk diupdate
                                $firstPlot = $plots->first();
                                $firstPlot->update([
                                    'jadwal_munaqosah_id' => $record->id,
                                    'status_terlaksana' => false,
                                ]);

                                // Hapus plot lainnya jika ada
                                $plots->slice(1)->each->delete();
                            } else {
                                // Jika tidak ditemukan, buat plot baru
                                PlotJadwalMunaqosah::create([
                                    'user_id' => $user->id,
                                    'jadwal_munaqosah_id' => $record->id,
                                    'status_terlaksana' => false,
                                ]);
                            }
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
