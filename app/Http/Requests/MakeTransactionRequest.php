<?php

namespace App\Http\Requests;

use App\Models\Transaction;
use App\Models\UserOption;
use Carbon\Carbon;
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
            'currency' => 'string|nullable',
            'amount' => 'numeric|required',
            'name' => 'required|string',
            'recurring_transaction_id' => 'integer|exists:recurring_transactions,id|nullable',
            'tag_id' => 'required|integer|exists:tags,id'
        ];
    }

    /**
     * Creates a new transaction based on the given request data
     *
     * @return bool
     */
    public function make()
    {

        $options = UserOption::where('user_id', Auth::id())->first();
        $transaction = new Transaction([
            'name' => $this->name,
            'currency' => $options->currency,
            'amount' => $this->amount,
            'tag_id' =>  $this->tag_id
        ]);

        $transaction->user()->associate(Auth::user());
        $transaction->save();

        return true;
    }
}
