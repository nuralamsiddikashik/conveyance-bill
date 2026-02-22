<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreConveyanceRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array {
        return [
            'date' => ['required', 'date'],
            'rows' => ['required', 'string'],
        ];
    }

    /**
     * Return validated and parsed rows as array.
     *
     * @return array<int, array<string, mixed>>
     */
    public function rows(): array {
        $validated = $this->validated();

        $rows = json_decode( $validated['rows'] ?? '[]', true ) ?: [];

        // Filter out completely empty rows
        $rows = array_values( array_filter( $rows, function ( $row ) {
            return isset( $row['from'], $row['to'], $row['amount'], $row['remarks'] ) &&
                ( trim( $row['from'] ?? '' ) !== '' ||
                trim( $row['to'] ?? '' ) !== '' ||
                (float) ( $row['amount'] ?? 0 ) > 0 ||
                trim( $row['remarks'] ?? '' ) !== '' );
        } ) );

        return $rows;
    }
}
