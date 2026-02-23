<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'description' => ['required', 'string', 'min:20', 'max:2000'],
            'category' => ['required', Rule::in(['buku', 'seragam', 'alat_praktikum', 'lainnya'])],
            'type' => ['required', Rule::in(['gift', 'sale'])],
            'condition' => ['required', Rule::in(['baru', 'sangat_baik', 'baik', 'cukup'])],
            'legacy_message' => ['required', 'string', 'min:10', 'max:1000'],
        ];

        // Validation for sale items
        if ($this->type === 'sale') {
            $rules['price'] = ['required', 'numeric', 'min:1000', 'max:10000000'];
        }

        // Validation for images
        if ($this->isMethod('post')) {
            $rules['images'] = ['required', 'array', 'min:1', 'max:5'];
            $rules['images.*'] = ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'];
        } else {
            $rules['images'] = ['nullable', 'array', 'max:5'];
            $rules['images.*'] = ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'];
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
            'name.required' => 'Nama barang wajib diisi.',
            'name.min' => 'Nama barang minimal 3 karakter.',
            'name.max' => 'Nama barang maksimal 255 karakter.',

            'description.required' => 'Deskripsi barang wajib diisi.',
            'description.min' => 'Deskripsi barang minimal 20 karakter.',
            'description.max' => 'Deskripsi barang maksimal 2000 karakter.',

            'category.required' => 'Kategori barang wajib dipilih.',
            'category.in' => 'Kategori barang tidak valid.',

            'type.required' => 'Tipe barang wajib dipilih.',
            'type.in' => 'Tipe barang tidak valid.',

            'condition.required' => 'Kondisi barang wajib dipilih.',
            'condition.in' => 'Kondisi barang tidak valid.',

            'legacy_message.required' => 'Pesan legacy wajib diisi.',
            'legacy_message.min' => 'Pesan legacy minimal 10 karakter.',
            'legacy_message.max' => 'Pesan legacy maksimal 1000 karakter.',

            'price.required' => 'Harga barang wajib diisi untuk tipe jual.',
            'price.numeric' => 'Harga barang harus berupa angka.',
            'price.min' => 'Harga barang minimal Rp 1.000.',
            'price.max' => 'Harga barang maksimal Rp 10.000.000.',

            'images.required' => 'Foto barang wajib diupload.',
            'images.array' => 'Format foto tidak valid.',
            'images.min' => 'Minimal upload 1 foto.',
            'images.max' => 'Maksimal upload 5 foto.',
            'images.*.image' => 'File harus berupa gambar.',
            'images.*.mimes' => 'Format gambar harus JPEG, PNG, atau JPG.',
            'images.*.max' => 'Ukuran gambar maksimal 2MB.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->type === 'gift') {
            $this->merge(['price' => 0]); // GANTI null JADI 0
        }
    }
}
