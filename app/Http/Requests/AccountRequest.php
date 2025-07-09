<?php

namespace App\Http\Requests;

use App\Helpers\Utils;
use App\Models\Account;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AccountRequest extends FormRequest
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
            'search' => 'string|nullable',
            'type' => 'array|nullable',
            'per_page' => 'integer|nullable'
        ];
    }

    public function retrieve()
    {
        $options = Auth::user()->option;
        return Account::with([
                'transactions'
            ])
            ->where('user_id', Auth::id())
            ->when(filled($this->search), function ($query) {
                $query->where('name', 'like', "%{$this->search}%");
            })
            ->when(filled($this->type), function ($query) {
                $query->whereIn('type', $this->type);
            })
            ->paginate($this->per_page ?? 10)
            ->through(function ($account) use ($options){
                $previous = Utils::getProperStatementDate($options->timezone, $account->statement_date)->subMonth()->toIso8601String();
                $now = Utils::getProperStatementDate($options->timezone, $account->statement_date)->toIso8601String();
                $next = Utils::getProperStatementDate($options->timezone, $account->statement_date)->addMonth()->toIso8601String();

                $balances = Transaction::select([
                    DB::raw("SUM(
                        CASE WHEN created_at >= '$now' AND created_at <= '$next'
                        THEN amount ELSE 0 END
                    ) as statement_balance"),
                    DB::raw("SUM(
                        CASE WHEN created_at >= '$previous' AND created_at <= '$now'
                        THEN amount ELSE 0 END
                    ) as previous_balance"),
                ])
                    ->where('user_id', Auth::id())
                    ->whereHasMorph('transactable', [Account::class], function ($query) use ($account) {
                        $query->where('id', $account->id);
                    })
                    ->whereNotNull('posted_at')
                    ->whereBetween('created_at', [$previous, $next])
                    ->first();

                $account->statement_balance = $balances->statement_balance ?? 0;
                $account->previous_balance = $balances->previous_balance ?? 0;
                return $account;
            });
    }
}
