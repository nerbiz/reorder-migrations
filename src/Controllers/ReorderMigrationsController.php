<?php

namespace Nerbiz\ReorderMigrations\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Nerbiz\ReorderMigrations\Requests\ConfirmChangesRequest;
use Nerbiz\ReorderMigrations\Requests\PreviewChangesRequest;

class ReorderMigrationsController extends Controller
{
    /**
     * @var Filesystem
     */
    protected Filesystem $disk;

    /**
     * The regular expression to match the date-prefix with
     * @var string
     */
    protected string $dateRegEx = '/^(?<date>\d{4}_\d{2}_\d{2}_\d{6})/';

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
    public function reorder(Request $request): View
    {
        return view('reorder-migrations::index', [
            'filenames' => $this->disk->files(),
        ]);
    }

    /**
     * @param PreviewChangesRequest $request
     * @return RedirectResponse
     */
    public function processReorder(PreviewChangesRequest $request): RedirectResponse
    {
        $currentFilenames = $this->disk->files();
        $submittedFilenames = $request->input('filenames');
        $newFilenames = [];

        // Create the list of dates/prefixes to use
        if ($request->input('naming_mode') === 'current') {
            // Get the dates from all current files
            $fileDates = array_map(function (string $filename) {
                preg_match($this->dateRegEx, $filename, $matches);
                return $matches['date'];
            }, $currentFilenames);

            foreach ($submittedFilenames as $index => $filename) {
                $newFilenames[] = sprintf(
                    '%s%s',
                    // Get the file date for the current position
                    $fileDates[$index],
                    // Get the filename without the date part
                    preg_replace($this->dateRegEx, '', $filename)
                );
            }
        } else {
            foreach ($submittedFilenames as $index => $filename) {
                $newFilenames[] = sprintf(
                    '%s_%s%s',
                    // Remove any trailing underscores from the prefix
                    rtrim($request->input('filename_prefix'), '_'),
                    // Make a 6-digit counter string, like 000001
                    str_pad($index + 1, 6, '0', STR_PAD_LEFT),
                    // Get the filename without the date part
                    preg_replace($this->dateRegEx, '', $filename)
                );
            }
        }

        return redirect()
            ->route('reorderMigrations.confirm')
            ->with(compact('currentFilenames', 'newFilenames'));
    }

    /**
     * @param Request $request
     * @return View
     */
    public function confirm(Request $request): View
    {
        // Keep the changes, for when a user refreshes the page
        session()->keep(['currentFilenames', 'newFilenames']);

        return view('reorder-migrations::confirm', [
            'currentFilenames' => session('currentFilenames'),
            'newFilenames' => session('newFilenames'),
        ]);
    }

    /**
     * @param ConfirmChangesRequest $request
     * @return RedirectResponse
     */
    public function processConfirm(ConfirmChangesRequest $request): RedirectResponse
    {
        $currentFilenames = $request->input('current_filenames');
        $newFilenames = $request->input('new_filenames');

        // Create an array of file names, with current date prefix
        $files = [];
        foreach ($currentFilenames as $filename) {
            // Get the prefix and name from the filename
            preg_match($this->dateRegEx, $filename, $matches);
            $name = preg_replace($this->dateRegEx, '', $filename);
            $files[$name] = ['current' => $matches['date']];
        }

        // Add the new date prefixes to the array
        foreach ($newFilenames as $filename) {
            // Get the prefix and name from the filename
            preg_match($this->dateRegEx, $filename, $matches);
            $name = preg_replace($this->dateRegEx, '', $filename);
            $files[$name]['new'] = $matches['date'];
        }

        // Rename the files
        foreach ($files as $name => $prefixes) {
            $this->disk->move(
                $prefixes['current'] . $name,
                $prefixes['new'] . $name
            );
        }

        return redirect()
            ->route('reorderMigrations.reorder')
            ->with('success', __('The migration files have been renamed'));
    }
}
