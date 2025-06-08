<?php

namespace App\Http\Requests;

use App\Models\Transaction;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class MakeTransactionRequest extends FormRequest
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
            'amount' => 'numeric|required',
            'name' => 'string|required',
            'recurring_transaction_id' => 'integer|exists:App\Models\RecurringTransaction,id|nullable',
            'tag_id' => 'integer|exists:tags,id|required',
            'transactable_type' => 'string|required',
            'transactable_id' => 'integer|required'
        ];
    }

    /**
     * Creates a new transaction based on the given request data
     *
     * @return bool
     */
    public function make()
    {
        $transactable = "App\Models\\$this->transactable_type"::find($this->transactable_id);
        if(empty($transactable)){
            throw ValidationException::withMessages([
                'transactable_id' => ['The provided account details does not exist.'],
            ]);
        }

        $transaction = new Transaction([
            'name' => $this->name,
            'amount' => $this->amount,
            'recurring_transaction_id' => $this->recurring_transaction_id ?? null,
            'tag_id' =>  $this->tag_id
        ]);

        $transaction->user()->associate(Auth::user());
        $transaction->transactable()->associate($transactable);
        $transaction->save();

        return true;
    }
}
