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
            'tag_id' => 'required|integer|exists:tags,id',
            'transacted_at' => 'nullable'
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
        $transacted_at = $this->transacted_at ? Carbon::parse($this->transacted_at, $options->timezone)->timezone('UTC')->toDateTimeString()
            : now($options->timezone)->timezone('UTC')->toDateTimeString();

        $transaction = new Transaction([
            'name' => $this->name,
            'currency' => $options->currency,
            'amount' => $this->amount,
            'tag_id' =>  $this->tag_id,
            'transacted_at' => $transacted_at
        ]);

        $transaction->user()->associate(Auth::user());
        $transaction->save();

        return true;
    }
}
