<?php

namespace App\Http\Controllers\Api;

use App\Helpers\JsonResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\RewardRequest;
use App\Models\Reward;
use Illuminate\Http\Request;

class RewardController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
    */
    public function index()
    {
        $rewards = Reward::all();
        return JsonResponseHelper::respondSuccess($rewards);
    }


    /**
     * @param RewardRequest $request
     * @return \Illuminate\Http\JsonResponse
    */
    public function store(RewardRequest $request)
    {
        $validated = $request->validated();
        $reward = Reward::create($validated);
        return JsonResponseHelper::respondSuccess($reward, 201);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
    {
        $reward = Reward::find($request->id);
        if (!$reward) {
            return JsonResponseHelper::respondErrorNotFound('Reward not found!');
        }
        if ($reward->stock <= 0) {
            return JsonResponseHelper::respondFail('The reward is no longer available.');
        }
        return JsonResponseHelper::respondSuccess($reward);
    }


    /**
     * @param RewardRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(RewardRequest $request, $id)
    {
        $validated = $request->validated();
        $reward = Reward::find($id);
        if (!$reward) {
            return JsonResponseHelper::respondErrorNotFound('Reward not found!');
        }

        $reward->update($validated);
        return JsonResponseHelper::respondSuccess($reward);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        $reward = Reward::find($request->id);
        if (!$reward) {
            return JsonResponseHelper::respondErrorNotFound('Reward not found!');
        }
        $reward->delete();
        return JsonResponseHelper::respondSuccess('Reward successfully deleted!');
    }

}
