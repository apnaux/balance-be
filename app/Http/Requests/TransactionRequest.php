<?php

namespace App\Http\Requests;

use App\Models\Transaction;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class TransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'tags' => 'array|nullable',
            // 'from_recurring' => 'boolean|nullable', // if the transactions list should only include anything from a recurring transaction
            'is_selection' => 'boolean|nullable', // detemines if the list should only have label and value keys
            'per_page' => 'integer|nullable',
            'search' => 'string|nullable',
            // 'transaction_type' => 'array|nullable', // if the transaction type should only include anything from the accounts or goals
            'cycle_start_end' => 'array|nullable'
        ];
    }

    /**
     * Retrives all the transactions based on the current filters
     *
     * @return mixed
     */
    public function retrieve()
    {
        return Transaction::when($this->is_selection, function ($query) {
            $query->select([
                'name as label',
                'id as value'
            ]);
        })
        ->with([
            'tag'
        ])
        ->where('user_id', Auth::id())
        ->when(filled($this->search), function ($query) {
            $query->where('name', 'like', "%{$this->search}%");
        })
        ->when(filled($this->tags), function ($query) {
            $query->whereHas('tag', function ($query) {
                $query->whereIn('id', $this->default_tags);
            });
        })
        ->when(filled($this->cycle_start_end), function ($query) {
            $query->whereBetween('created_at', $this->cycle_start_end);
        })
        ->orderByDesc('created_at')
        ->cursorPaginate($this->per_page ?? 10);
    }
}
