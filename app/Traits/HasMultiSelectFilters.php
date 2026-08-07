<?php

// namespace App\Traits;

// trait HasMultiSelectFilters
// {
//     public function toggleAll(string $filterProperty, string $modelClass): void
//     {
//         $allIds = $modelClass::pluck('id')->map(fn ($id) => (string) $id)->toArray();
//         $allIds[] = 'null';

//         if (count($this->{$filterProperty}) === count($allIds)) {
//             $this->{$filterProperty} = [];
//         } else {
//             $this->{$filterProperty} = $allIds;
//         }
//     }
// }
