<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SalesBookDownload;
use Illuminate\Support\Facades\Log;
use Exception;

class SalesBookController extends Controller
{
    public function index()
    {
        $salesBooks = SalesBookDownload::orderBy('created_at', 'desc')->paginate(20);

        return view('admin.salesbooks.index', compact('salesBooks'));
    }

    public function destroy($id)
    {
        Log::info('=== DESTROY METHOD CALLED ===', [
            'method' => 'destroy',
            'id_parameter' => $id,
            'request_method' => request()->method(),
            'full_url' => request()->fullUrl()
        ]);

        try {
            // Find the record manually to avoid route model binding issues
            $salesBook = SalesBookDownload::findOrFail($id);

            Log::info('Found SalesBook record to delete', [
                'id' => $salesBook->id,
                'name' => $salesBook->name,
                'email' => $salesBook->email
            ]);

            // Attempt deletion
            $deleted = $salesBook->delete();

            Log::info('Delete operation result', [
                'result' => $deleted,
                'id' => $id
            ]);

            // Verify deletion
            $stillExists = SalesBookDownload::where('id', $id)->exists();
            Log::info('Verification after deletion', ['still_exists' => $stillExists]);

            if ($stillExists) {
                Log::error('Record still exists after deletion attempt', ['id' => $id]);
                return redirect()->route('admin.salesbooks.index')->with('error', 'Failed to delete the record. Please try again.');
            }

            Log::info('SalesBook record deleted successfully', ['id' => $id]);
            return redirect()->route('admin.salesbooks.index')->with('success', 'Sales book download record deleted successfully.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('SalesBook record not found', ['id' => $id]);
            return redirect()->route('admin.salesbooks.index')->with('error', 'Record not found.');
        } catch (Exception $e) {
            Log::error('Error deleting SalesBook record', [
                'id' => $id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return redirect()->route('admin.salesbooks.index')->with('error', 'An error occurred while deleting the record: ' . $e->getMessage());
        }
    }
}
