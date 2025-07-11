<?php

namespace App\Http\Controllers;

use App\Helpers\Utils;
use App\Models\User;
use App\Models\UserOption;
use App\Models\UserTransactionCycle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserOptionController extends Controller
{
    public function setOptions(Request $request)
    {
        $request->validate([
            'first_run' => 'boolean|nullable',
            'name' => 'string|required_if:first_run,1',
            'currency' => 'string|required_if:first_run,1',
            'cycle_cutoff' => 'integer|min:1|max:31|required_if:first_run,1',
            'allocated_budget' => 'numeric|min:1|required_if:first_run,1',
            'timezone' => 'string|required_if:first_run,1',
        ]);

        if(filled($request->name)){
            User::find(Auth::id())->update(['name' => $request->name]);
        }

        User::find(Auth::id())->option()
            ->updateOrCreate(['user_id' => Auth::id()], $request->all());

        $latestCycle = UserTransactionCycle::where('user_id', $request->user()->id)->orderByDesc('active_from')->first();

        if(filled($latestCycle) && filled($request->cycle_cutoff)) {
            $options = UserOption::where('user_id', Auth::id())->first();
            $latestCycle->update([
                'active_until' => Utils::getProperStatementDate($options->timezone, $request->cycle_cutoff)->addMonth()->toIso8601String()]
            );
        }

        if(filled($latestCycle) && filled($request->allocated_budget)) {
            $latestCycle->update(['allocated_budget' => $request->allocated_budget]);
        }

        if($request->header('X-Inertia')){
            return redirect()->route('budgets.index');
        }

        return response()->json([
            'message' => 'Your options has been updated!'
        ]);
    }
}
