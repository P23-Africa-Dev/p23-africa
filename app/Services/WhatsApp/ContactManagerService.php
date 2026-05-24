<?php

namespace App\Services\WhatsApp;

use App\Models\WhatsAppContact;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ContactManagerService
{
    public function listContacts(?string $search = null, int $perPage = 20): LengthAwarePaginator
    {
        return WhatsAppContact::query()
            ->withCount('conversations')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', '%' . $search . '%')
                        ->orWhere('phone', 'like', '%' . $search . '%')
                        ->orWhere('company', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%')
                        ->orWhere('notes', 'like', '%' . $search . '%');
                });
            })
            ->orderByDesc('last_chat_at')
            ->orderByDesc('updated_at')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function createContact(array $data): WhatsAppContact
    {
        return WhatsAppContact::query()->create($this->prepareContactPayload($data));
    }

    public function updateContact(WhatsAppContact $contact, array $data): WhatsAppContact
    {
        $contact->update($this->prepareContactPayload($data, $contact));

        return $contact->refresh();
    }

    public function deleteContact(WhatsAppContact $contact): void
    {
        $contact->delete();
    }

    public function saveChatContact(string $phone, array $data): WhatsAppContact
    {
        $contact = WhatsAppContact::query()->firstOrNew(['phone' => $phone]);

        $contact->fill($this->prepareContactPayload($data, $contact));
        $contact->phone = $phone;
        $contact->unsaved_contact = false;
        $contact->last_chat_at = now();
        $contact->save();

        return $contact->refresh();
    }

    protected function prepareContactPayload(array $data, ?WhatsAppContact $existing = null): array
    {
        $avatar = $data['avatar'] ?? ($existing?->avatar);
        if (($data['avatar_file'] ?? null) instanceof UploadedFile) {
            $path = $data['avatar_file']->store('whatsapp/avatars', 'public');
            $avatar = Storage::url($path);
        }

        $profilePicture = $existing?->profile_picture;
        if ($avatar && !$profilePicture) {
            $profilePicture = $avatar;
        }

        return [
            'name' => $data['name'] ?? ($existing?->name),
            'phone' => $data['phone'] ?? ($existing?->phone),
            'country_code' => $data['country_code'] ?? ($existing?->country_code),
            'email' => $data['email'] ?? ($existing?->email),
            'company' => $data['company'] ?? ($existing?->company),
            'notes' => $data['notes'] ?? ($existing?->notes),
            'avatar' => $avatar,
            'profile_picture' => $profilePicture,
            'is_whatsapp' => $data['is_whatsapp'] ?? ($existing?->is_whatsapp),
            'validation_status' => $data['validation_status'] ?? ($existing?->validation_status),
            'validated_at' => $data['validated_at'] ?? ($existing?->validated_at),
            'validation_cache_until' => $data['validation_cache_until'] ?? ($existing?->validation_cache_until),
            'last_chat_at' => $data['last_chat_at'] ?? ($existing?->last_chat_at),
            'unsaved_contact' => $data['unsaved_contact'] ?? ($existing?->unsaved_contact ?? false),
        ];
    }
}
