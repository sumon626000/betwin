<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\Transaction;
use App\Models\Withdrawal;
use App\Models\WithdrawMethod;
use App\Models\UserWithdrawMethod;
use Illuminate\Http\Request;

class WithdrawController extends Controller
{
    public function withdrawMoney()
    {
        $pageTitle = 'Withdraw Money';
        $withdrawMethod = WithdrawMethod::active()->get();
        $userMethods = UserWithdrawMethod::where('user_id', auth()->id())->get()->keyBy('method_id');
        return view('Template::user.withdraw.methods', compact('pageTitle', 'withdrawMethod', 'userMethods'));
    }

    public function setPin(Request $request)
    {
        $request->validate([
            'pin' => 'required|digits:4',
            'confirm_pin' => 'required|same:pin',
        ]);

        $user = auth()->user();
        $user->withdraw_pin = $request->pin;
        $user->save();

        $notify[] = ['success', 'Withdraw PIN set successfully'];
        return back()->withNotify($notify);
    }

    public function changePin()
    {
        $pageTitle = 'Change Transaction PIN';
        return view('Template::user.withdraw.change_pin', compact('pageTitle'));
    }

    public function updatePin(Request $request)
    {
        $request->validate([
            'old_pin' => 'required|digits:4',
            'password' => 'required|digits:4|confirmed',
        ]);

        $user = auth()->user();

        if ($user->withdraw_pin != $request->old_pin) {
            $notify[] = ['error', 'Old PIN does not match'];
            return back()->withNotify($notify);
        }

        $user->withdraw_pin = $request->password;
        $user->save();

        $notify[] = ['success', 'Transaction PIN changed successfully'];
        return back()->withNotify($notify);
    }

    public function bindAccount(Request $request)
    {
        $request->validate([
            'method_id' => 'required|exists:withdraw_methods,id',
            'wallet_number' => 'required|string|max:255',
        ]);

        $user = auth()->user();

        $methodExists = UserWithdrawMethod::where('user_id', $user->id)
            ->where('method_id', $request->method_id)
            ->exists();

        if ($methodExists) {
            $notify[] = ['error', 'You have already bound an account for this method.'];
            return back()->withNotify($notify);
        }

        $numberUsedInMethod = UserWithdrawMethod::where('method_id', $request->method_id)
            ->where('wallet_number', $request->wallet_number)
            ->exists();

        if ($numberUsedInMethod) {
            $notify[] = ['error', 'This wallet number is already bound to another account for this method.'];
            return back()->withNotify($notify);
        }

        $method = WithdrawMethod::where('id', $request->method_id)->first();

        UserWithdrawMethod::create([
            'user_id' => $user->id,
            'method_id' => $request->method_id,
            'method_code' => $method->id, 
            'wallet_number' => $request->wallet_number
        ]);

        $notify[] = ['success', 'Account bound successfully'];
        return back()->withNotify($notify);
    }

    public function withdrawStore(Request $request)
    {
        $request->validate([
            'method_code' => 'required',
            'amount' => 'required|numeric',
            'trans_pin' => 'required|digits:4'
        ]);

        $user = auth()->user();

        /* Turnover Requirement Check */
        if ($user->turnover_requirement > 0) {
            $notify[] = ['error', 'Please complete your turnover requirement: ' . showAmount($user->turnover_requirement)];
            return back()->withNotify($notify)->withInput();
        }

        if ($user->withdraw_pin != $request->trans_pin) {
            $notify[] = ['error', 'Invalid Transaction PIN'];
            return back()->withNotify($notify)->withInput();
        }

        $boundMethod = UserWithdrawMethod::where('user_id', $user->id)
            ->where('method_id', $request->method_code)
            ->first();

        if (!$boundMethod) {
            $notify[] = ['error', 'Please bind your account number first'];
            return back()->withNotify($notify);
        }

        $method = WithdrawMethod::where('id', $request->method_code)->active()->firstOrFail();

        if ($request->amount < $method->min_limit) {
            $notify[] = ['error', 'Your requested amount is smaller than minimum amount'];
            return back()->withNotify($notify)->withInput();
        }
        if ($request->amount > $method->max_limit) {
            $notify[] = ['error', 'Your requested amount is larger than maximum amount'];
            return back()->withNotify($notify)->withInput();
        }

        if ($request->amount > $user->balance) {
            $notify[] = ['error', 'Insufficient balance for withdrawal'];
            return back()->withNotify($notify)->withInput();
        }

        $charge = $method->fixed_charge + ($request->amount * $method->percent_charge / 100);
        $afterCharge = $request->amount - $charge;
        $finalAmount = $afterCharge * $method->rate;

        $withdraw = new Withdrawal();
        $withdraw->method_id = $method->id;
        $withdraw->user_id = $user->id;
        $withdraw->amount = $request->amount;
        $withdraw->currency = $method->currency;
        $withdraw->rate = $method->rate;
        $withdraw->charge = $charge;
        $withdraw->final_amount = $finalAmount;
        $withdraw->after_charge = $afterCharge;
        $withdraw->trx = getTrx();
        $withdraw->withdraw_information = ['Wallet Number' => $boundMethod->wallet_number]; 
        $withdraw->status = Status::PAYMENT_PENDING;
        $withdraw->save();

        $user->balance -= $withdraw->amount;
        $user->save();

        $transaction = new Transaction();
        $transaction->user_id = $withdraw->user_id;
        $transaction->amount = $withdraw->amount;
        $transaction->post_balance = $user->balance;
        $transaction->charge = $withdraw->charge;
        $transaction->trx_type = '-';
        $transaction->details = 'Withdraw request via ' . $method->name . ' to ' . $boundMethod->wallet_number;
        $transaction->trx = $withdraw->trx;
        $transaction->remark = 'withdraw';
        $transaction->save();

        $adminNotification = new AdminNotification();
        $adminNotification->user_id = $user->id;
        $adminNotification->title = 'New withdraw request from ' . $user->username;
        $adminNotification->click_url = urlPath('admin.withdraw.data.details', $withdraw->id);
        $adminNotification->save();

        \App\Lib\AdminAlert::bigWithdraw(
            (int) $user->id,
            (string) $user->username,
            (float) $withdraw->amount,
            (int) $withdraw->id
        );

        notify($user, 'WITHDRAW_REQUEST', [
            'method_name' => $withdraw->method->name,
            'method_currency' => $withdraw->currency,
            'method_amount' => showAmount($withdraw->final_amount, currencyFormat: false),
            'amount' => showAmount($withdraw->amount, currencyFormat: false),
            'charge' => showAmount($withdraw->charge, currencyFormat: false),
            'rate' => showAmount($withdraw->rate, currencyFormat: false),
            'trx' => $withdraw->trx,
            'post_balance' => showAmount($user->balance, currencyFormat: false),
        ]);

        $notify[] = ['success', 'Withdraw request sent successfully'];
        return to_route('user.withdraw.history')->withNotify($notify);
    }

    public function withdrawLog(Request $request)
    {
        $pageTitle = "Withdrawal Log";
        $withdraws = Withdrawal::where('user_id', auth()->id())->where('status', '!=', Status::PAYMENT_INITIATE);

        $status = strtolower((string) $request->status);
        if ($status === 'pending') {
            $withdraws->where('status', Status::PAYMENT_PENDING);
        } elseif ($status === 'approved' || $status === 'success') {
            $withdraws->where('status', Status::PAYMENT_SUCCESS);
        } elseif ($status === 'rejected') {
            $withdraws->where('status', Status::PAYMENT_REJECT);
        }

        if ($request->search) {
            $withdraws = $withdraws->where('trx', $request->search);
        }
        $withdraws = $withdraws->with('method')->orderBy('id', 'desc')->paginate(getPaginate());
        return view('Template::user.withdraw.log', compact('pageTitle', 'withdraws'));
    }
}