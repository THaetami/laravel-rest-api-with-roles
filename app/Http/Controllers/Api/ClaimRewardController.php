<?php

namespace App\Http\Controllers\Api;

use App\Helpers\JsonResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ClaimRewardRequest;
use App\Models\ClaimReward;
use App\Models\Reward;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClaimRewardController extends Controller
{
    /**
     * @param ClaimRewardRequest $request
     * @return \Illuminate\Http\JsonResponse
    */
    public function claimReward(ClaimRewardRequest $request)
    {
        DB::beginTransaction();

        try {
            $validated = $request->validated();
            $customer = Auth::user()->customers;

            $alreadyClaimed = ClaimReward::where('customer_id', $customer->id)
                                        ->where('reward_id', $validated['reward_id'])
                                        ->first();
            if ($alreadyClaimed) {
                return JsonResponseHelper::respondFail('The reward has been claimed');
            }

            $reward = Reward::find($validated['reward_id']);
            $point = $reward->point_reward;
            $reward->stock -= 1;
            $reward->save();

            ClaimReward::create([
                'customer_id' => $customer->id,
                'reward_id' => $validated['reward_id'],
                'claim_date' => now()
            ]);

            $customer->point_transaction += $point;
            $customer->save();

            DB::commit();
            return JsonResponseHelper::respondSuccess("$point reward point has been successfully claimed.");
        } catch (\Exception $e) {
            DB::rollBack();
            return JsonResponseHelper::respondFail('Claimed reward failed: ' . $e->getMessage(), 500);
        }
    }
}
