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
            'id' => 'integer|nullable',
            'currency' => 'string|nullable',
            'timezone' => 'string|nullable',
            'amount' => 'numeric|required',
            'name' => 'required|string',
            'tag_id' => 'required|integer|exists:tags,id',
            'account_id' => 'required|integer',
            'transacted_at' => 'nullable'
        ];
    }

    /**
     * Creates a new transaction based on the given request data
     *
     * @return bool
     */
    public function createTransaction()
    {
        $options = UserOption::where('user_id', Auth::id())->first();
        $transacted_at = $this->transacted_at ? Carbon::parse($this->transacted_at, $options->timezone)->timezone('UTC')->toDateTimeString()
            : now($options->timezone)->timezone('UTC')->toDateTimeString();

        $transaction = new Transaction([
            'name' => $this->name,
            'currency' => $this->timezone ?? $options->currency,
            'timezone' => $this->timezone ?? $options->timezone,
            'amount' => $this->amount,
            'tag_id' =>  $this->tag_id,
            'account_id' => $this->account_id,
            'transacted_at' => $transacted_at
        ]);

        $transaction->user()->associate(Auth::user());
        $transaction->save();

        return true;
    }

    public function updateTransaction()
    {
        $options = UserOption::where('user_id', Auth::id())->first();
        $transaction = Transaction::find($this->id);
        $transacted_at = $this->transacted_at ? Carbon::parse($this->transacted_at, $options->timezone)->timezone('UTC')->toDateTimeString() : $transaction->transacted_at;

        $transaction->update([
            'name' => $this->name,
            'amount' => $this->amount,
            'tag_id' =>  $this->tag_id,
            'account_id' => $this->account_id,
            'transacted_at' => $transacted_at
        ]);

        return true;
    }
}
