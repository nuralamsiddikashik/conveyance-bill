<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class StoreConveyanceRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        $user = $this->user();

        return $user !== null && $user->isApproved();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array {
        return [
            'date' => ['required', 'date'],
            'rows' => ['required', 'string', 'json', 'max:20000'],
        ];
    }

    /**
     * Return validated and parsed rows as array.
     *
     * @return array<int, array<string, mixed>>
     */
    public function rows(): array {
        $validated = $this->validated();

        $rows = json_decode( $validated['rows'] ?? '[]', true );

        if ( ! is_array( $rows ) ) {
            throw ValidationException::withMessages( [
                'rows' => 'Invalid rows format.',
            ] );
        }

        $rows = array_map( function ( $row ) {
            $row = is_array( $row ) ? $row : [];

            $amount = $row['amount'] ?? null;
            if ( $amount === '' ) {
                $amount = null;
            }

            return [
                'from'    => trim( (string) ( $row['from'] ?? '' ) ),
                'to'      => trim( (string) ( $row['to'] ?? '' ) ),
                'amount'  => $amount,
                'remarks' => trim( (string) ( $row['remarks'] ?? '' ) ),
            ];
        }, $rows );

        if ( count( $rows ) > 200 ) {
            throw ValidationException::withMessages( [
                'rows' => 'Too many rows submitted.',
            ] );
        }

        Validator::make( $rows, [
            '*.from'    => ['nullable', 'string', 'max:255'],
            '*.to'      => ['nullable', 'string', 'max:255'],
            '*.amount'  => ['nullable', 'numeric', 'min:0', 'max:10000000'],
            '*.remarks' => ['nullable', 'string', 'max:255'],
        ] )->validate();

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
