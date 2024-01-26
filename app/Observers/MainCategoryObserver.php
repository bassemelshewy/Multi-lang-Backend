<?php

namespace App\Observers;

use App\Models\MainCategory;


//php artisan make:observe MainCategoryObserver --model=MainCategory
class MainCategoryObserver
{
    /**
     * Handle the MainCategory "created" event.
     */
    public function created(MainCategory $mainCategory): void
    {
        //
    }

    /**
     * Handle the MainCategory "updated" event.
     */
    public function updated(MainCategory $mainCategory): void
    {
        $mainCategory -> vendors()-> update(['active' => $mainCategory -> active]);
    }

    /**
     * Handle the MainCategory "deleted" event.
     */
    public function deleted(MainCategory $mainCategory): void
    {
        //
    }

    /**
     * Handle the MainCategory "restored" event.
     */
    public function restored(MainCategory $mainCategory): void
    {
        //
    }

    /**
     * Handle the MainCategory "force deleted" event.
     */
    public function forceDeleted(MainCategory $mainCategory): void
    {
        //
    }
}
