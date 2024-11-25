<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ListUsers extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            \EightyNine\ExcelImport\ExcelImportAction::make('import_santri')
                ->label('Import Santri')
                ->mutateBeforeValidationUsing(function(array $data): array{
                    $data['id'] = Str::ulid();
                    $data['password'] = Hash::make($data['password']);
                    return $data;
                }),
        ];
    }

    public function getTabs(): array
    {
        $user = auth()->user();
        $tabs = [
            null => Tab::make('All')->query(fn ($query) => $query->where('tanggal_lulus_pondok', '=', null)),
            'Teman Kelas' => Tab::make()->query(fn ($query) => $query->whereKelas($user->kelas)->where('tanggal_lulus_pondok', '=', null)),
        ];

        if (can('view_any_users')) {
            $tabs['Ketua Kelas'] = Tab::make()->query(fn ($query) => $query->with('roles')->whereRelation('roles', 'name', '=', 'ketua_kelas'));
            $tabs['DMC Pasus'] = Tab::make()->query(fn ($query) => $query->whereHas('roles', function ($query) {
                $query->where('name', 'like', 'dmcp%');
            }));
            $tabs['Alumni'] = Tab::make()->query(fn ($query) => $query->where('tanggal_lulus_pondok', '!=', null));
            $tabs['Admin'] = Tab::make()->query(fn ($query) => $query->with('roles')->whereRelation('roles', 'name', '=', config('filament-shield.super_admin.name')));
        }

        return $tabs;
    }

    protected function getTableQuery(): Builder
    {
        $model = (new (static::$resource::getModel()))->with('roles');

        if (isNotAdmin()) {
            $model = $model->whereDoesntHave('roles', function ($query) {
                $query->where('name', '=', config('filament-shield.super_admin.name'));
            });
        }

        return $model;
    }
}
