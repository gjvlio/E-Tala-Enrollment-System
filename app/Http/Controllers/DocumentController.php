<?php

namespace App\Http\Controllers;

use App\Models\ApplicationDocument;
use App\Models\EnrollmentDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentController extends Controller
{
    // Stream an admission application document. Registrar sees all; a student
    // may only open documents attached to their own application.
    public function application(Request $request, ApplicationDocument $document): StreamedResponse
    {
        $document->loadMissing('application');

        $this->authorizeView($request, $document->application?->user_id);

        return $this->stream($document->path, $document->original_name);
    }

    // Stream a Grade 12 enrollment requirement. Same ownership rules.
    public function enrollment(Request $request, EnrollmentDocument $document): StreamedResponse
    {
        $document->loadMissing('enrollment.student');

        $this->authorizeView($request, $document->enrollment?->student?->user_id);

        return $this->stream($document->path, $document->original_name);
    }

    // Registrars can view any document; students only their own uploads.
    private function authorizeView(Request $request, ?int $ownerUserId): void
    {
        $user = $request->user();

        abort_unless(
            $user && ($user->role === 'registrar' || $user->id === $ownerUserId),
            403
        );
    }

    private function stream(string $path, ?string $name): StreamedResponse
    {
        $disk = Storage::disk('public');

        abort_unless($disk->exists($path), 404);

        return $disk->response($path, $name, ['Content-Disposition' => 'inline']);
    }
}
