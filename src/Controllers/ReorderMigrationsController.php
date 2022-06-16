<?php

namespace Nerbiz\ReorderMigrations\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Nerbiz\ReorderMigrations\Requests\ReorderMigrationsRequest;

class ReorderMigrationsController extends Controller
{
    /**
     * @var Filesystem
     */
    protected Filesystem $disk;

    public function __construct()
    {
        // Make a disk for the migrations directory
        $this->disk = Storage::build([
            'driver' => 'local',
            'root' => database_path('migrations'),
        ]);
    }

    /**
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        return view('reorder-migrations::index', [
            'filenames' => $this->disk->files(),
        ]);
    }

    /**
     * @param ReorderMigrationsRequest $request
     * @return RedirectResponse
     */
    public function apply(ReorderMigrationsRequest $request): RedirectResponse
    {
        $submittedFilenames = $request->input('filenames');
        $dateRegEx = '/^(?<date>\d{4}_\d{2}_\d{2}_\d{6})/';

        // Create the list of dates/prefixes to use
        if ($request->input('naming_mode') === 'current') {
            // Get the dates from all current files
            $fileDates = array_map(function (string $filename) use ($dateRegEx) {
                preg_match($dateRegEx, $filename, $matches);
                return $matches['date'];
            }, $this->disk->files());

            foreach ($submittedFilenames as $index => $filename) {
                // Rename the file
                $this->disk->move($filename, sprintf(
                    '%s%s',
                    // Get the file date for the current position
                    $fileDates[$index],
                    // Get the filename without the date part
                    preg_replace($dateRegEx, '', $filename)
                ));
            }
        } else {
            foreach ($submittedFilenames as $index => $filename) {
                // Rename the file
                $this->disk->move($filename, sprintf(
                    '%s_%s%s',
                    // Remove any trailing underscores from the prefix
                    rtrim($request->input('filename_prefix'), '_'),
                    // Make a 6-digit counter string, like 000001
                    str_pad($index + 1, 6, '0', STR_PAD_LEFT),
                    // Get the filename without the date part
                    preg_replace($dateRegEx, '', $filename)
                ));
            }
        }

        return back()->with('success', __('The migration files have been renamed'));
    }
}
