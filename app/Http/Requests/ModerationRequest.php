<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ModerationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $rules = [
            'action' => ['required', Rule::in(['approve', 'reject'])],
        ];

        // Validation for rejection
        if ($this->action === 'reject') {
            $rules['reason'] = ['required', 'string', 'min:10', 'max:1000'];
            $rules['reason_category'] = [
                'required',
                Rule::in([
                    'foto_kurang_jelas',
                    'deskripsi_kurang_lengkap',
                    'harga_tidak_wajar',
                    'kondisi_tidak_sesuai',
                    'kategori_salah',
                    'gambar_tidak_relevan',
                    'duplikat',
                    'melanggar_aturan',
                    'lainnya'
                ])
            ];
        }

        // Validation for approval
        if ($this->action === 'approve') {
            $rules['note'] = ['nullable', 'string', 'max:500'];
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'action.required' => 'Tindakan moderasi wajib dipilih.',
            'action.in' => 'Tindakan moderasi tidak valid.',

            'reason.required' => 'Alasan penolakan wajib diisi.',
            'reason.min' => 'Alasan penolakan minimal 10 karakter.',
            'reason.max' => 'Alasan penolakan maksimal 1000 karakter.',

            'reason_category.required' => 'Kategori alasan penolakan wajib dipilih.',
            'reason_category.in' => 'Kategori alasan penolakan tidak valid.',

            'note.max' => 'Catatan maksimal 500 karakter.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'reason' => 'alasan penolakan',
            'reason_category' => 'kategori alasan',
            'note' => 'catatan',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Clean reason text
        if ($this->has('reason')) {
            $this->merge([
                'reason' => strip_tags(trim($this->reason))
            ]);
        }

        // Clean note text
        if ($this->has('note')) {
            $this->merge([
                'note' => strip_tags(trim($this->note))
            ]);
        }
    }

    /**
     * Get the rejection reason category label.
     */
    public function getReasonCategoryLabel(): ?string
    {
        $categories = [
            'foto_kurang_jelas' => 'Foto kurang jelas/tidak sesuai',
            'deskripsi_kurang_lengkap' => 'Deskripsi kurang lengkap',
            'harga_tidak_wajar' => 'Harga tidak wajar',
            'kondisi_tidak_sesuai' => 'Kondisi barang tidak sesuai',
            'kategori_salah' => 'Kategori barang salah',
            'gambar_tidak_relevan' => 'Gambar tidak relevan',
            'duplikat' => 'Barang duplikat',
            'melanggar_aturan' => 'Melanggar aturan platform',
            'lainnya' => 'Lainnya',
        ];

        return $categories[$this->reason_category] ?? null;
    }

    /**
     * Check if the request is for approval.
     */
    public function isApproval(): bool
    {
        return $this->action === 'approve';
    }

    /**
     * Check if the request is for rejection.
     */
    public function isRejection(): bool
    {
        return $this->action === 'reject';
    }
}
