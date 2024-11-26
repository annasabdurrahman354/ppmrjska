<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;

class SantriImport implements ToCollection
{
    /**
    * @param Collection $collection
    */

    public function collection(Collection $collection)
    {
        $header = $collection->first()->toArray(); // Get the first row as an array
        $rows = $collection->slice(1); // Skip the header row for the data

        foreach ($rows as $row) {
            // Combine header keys with current row values
            $rowData = array_combine($header, $row->toArray());

            // Modify the `id` with a new ULID
            $rowData['id'] = (string) Str::ulid();

            // Hash the `nis` field and store it in the `password` field
            if (isset($rowData['nis'])) {
                $rowData['password'] = Hash::make($rowData['nis']);
            }

            // Use $rowData for further processing (e.g., save to database)
            // Example:
            User::create($rowData);
        }
    }
}
