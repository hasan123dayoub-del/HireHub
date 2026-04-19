<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use App\Http\Requests\StoreProposalRequest;
use App\Http\Requests\UpdateProposalRequest;
use App\Http\Resources\ProposalResource;
use App\Services\ProposalService;
use Illuminate\Http\JsonResponse;

class ProposalController extends Controller
{
    protected ProposalService $proposalService;

    public function __construct(ProposalService $proposalService)
    {
        $this->proposalService = $proposalService;
    }

    public function store(StoreProposalRequest $request): JsonResponse
    {
        $proposal = $this->proposalService->submitProposal($request->user(), $request->validated());
        return response()->json(['message' => 'Success', 'data' => new ProposalResource($proposal)], 201);
    }

    public function update(UpdateProposalRequest $request, Proposal $proposal): JsonResponse
    {
        $updatedProposal = $this->proposalService->updateProposal($proposal, $request->validated());
        return response()->json(['message' => 'Updated', 'data' => new ProposalResource($updatedProposal)]);
    }

    public function show(int $id): JsonResponse
    {
        $proposal = $this->proposalService->getProposalDetails($id);
        return response()->json([
            'status' => 'success',
            'data' => new ProposalResource($proposal)
        ]);
    }
}
