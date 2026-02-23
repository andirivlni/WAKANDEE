<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TransactionRequest extends FormRequest
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
        return [
            'item_id' => ['required', 'exists:items,id'],
            'payment_method' => ['required', Rule::in(['qris', 'cod'])],
            'delivery_method' => ['required', Rule::in(['dropoff', 'cod'])],
            'dropoff_point' => [
                'required_if:delivery_method,dropoff',
                'nullable',
                'string',
                'max:255'
            ],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'item_id.required' => 'Barang tidak valid.',
            'item_id.exists' => 'Barang tidak ditemukan.',

            'payment_method.required' => 'Metode pembayaran wajib dipilih.',
            'payment_method.in' => 'Metode pembayaran tidak valid.',

            'delivery_method.required' => 'Metode pengiriman wajib dipilih.',
            'delivery_method.in' => 'Metode pengiriman tidak valid.',

            'dropoff_point.required_if' => 'Titik drop-off wajib dipilih untuk metode dropoff.',
            'dropoff_point.max' => 'Titik drop-off maksimal 255 karakter.',

            'notes.max' => 'Catatan maksimal 500 karakter.',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Check if item is still available
            if ($this->item_id) {
                $item = \App\Models\Item::find($this->item_id);

                if ($item && $item->status !== 'approved') {
                    $validator->errors()->add('item_id', 'Barang tidak tersedia untuk ditransaksikan.');
                }

                if ($item && $item->user_id === auth()->id()) {
                    $validator->errors()->add('item_id', 'Anda tidak dapat membeli barang Anda sendiri.');
                }
            }
        });
    }
}
