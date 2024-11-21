<?php

namespace App\Filament\Pages\Munaqosah;

use App\Filament\Resources\JadwalMunaqosahResource;
use App\Models\JadwalMunaqosah;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;

class Munaqosah extends Page implements HasTable, HasForms, HasActions
{
    use InteractsWithTable;
    use InteractsWithActions;
    use InteractsWithForms;
    use HasPageShield;

    protected static ?string $slug = 'munaqosah';
    protected static ?string $navigationLabel = 'Munaqosah';
    protected static ?string $navigationGroup = 'Manajemen Munaqosah';
    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';

    protected static string $view = 'filament.pages.munaqosah';

    #[Url]
    public ?array $tableFilters = null;

    public function table(Table $table): Table
    {
        return JadwalMunaqosahResource::table($table)
            ->heading('Jadwal Munaqosah Saya')
            ->query(JadwalMunaqosah::query()
                ->whereHas('plotJadwalMunaqosah', fn($query) => $query->where('user_id', auth()->user()->id)
                )
            )
            ->modifyQueryUsing(fn (Builder $query) => $query->withCount([
                'plotJadwalMunaqosah as total_plotjadwalmunaqosah',
                'plotJadwalMunaqosah as terlaksana_plotjadwalmunaqosah' => function ($query) {
                    $query->where('status_terlaksana', true);
                }])
            );
    }
}
