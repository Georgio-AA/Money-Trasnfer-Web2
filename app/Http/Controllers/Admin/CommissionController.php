<?php

namespace App\Http\Controllers\Admin;

use App\Models\Agent;
use App\Models\Commission;
use App\Models\Transfer;
use App\Services\CommissionExportService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * CommissionController
 * 
 * Manages agent commission reporting, calculations, and tracking.
 * Restricted to super admin and finance admin roles.
 */
class CommissionController extends Controller
{
    /**
     * Display list of all agents with commission summary.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get all agents with their commission stats
        $agents = Agent::with('user')
            ->get()
            ->map(function ($agent) {
                return array_merge(
                    $agent->toArray(),
                    $this->getAgentCommissionStats($agent->id)
                );
            });

        return view('admin.commissions.index', compact('agents'));
    }

    /**
     * Display detailed commission report for a specific agent.
     *
     * @param int $agentId
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function detail($agentId, Request $request)
    {
        $agent = Agent::with('user')->findOrFail($agentId);
        
        // Get date range from request or default to current month
        $dateRange = $this->parseDateRange($request);
        
        // Get commissions for this agent within date range
        $commissions = Commission::forAgent($agentId)
            ->whereBetween('created_at', $dateRange)
            ->with('transfer')
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        // Calculate stats for the date range
        $stats = $this->calculateAgentCommissionStats($agentId, $dateRange);

        return view('admin.commissions.detail', compact('agent', 'commissions', 'stats', 'dateRange'));
    }

    /**
     * Display filtered commission report with date range and status filtering.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function report(Request $request)
    {
        $this->validateReportFilters($request);

        $query = Commission::with(['agent.user', 'transfer']);

        // Apply date range filter
        $dateRange = $this->parseDateRange($request);
        $query->whereBetween('created_at', $dateRange);

        // Apply agent filter
        if ($request->filled('agent_id')) {
            $query->where('agent_id', $request->agent_id);
        }

        // Apply status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Apply calculation method filter
        if ($request->filled('calculation_method')) {
            $query->where('calculation_method', $request->calculation_method);
        }

        // Get filtered commissions
        $commissions = $query->orderBy('created_at', 'desc')->paginate(100);

        // Calculate totals
        $totals = $this->calculateReportTotals($dateRange, $request);

        // Get agents for dropdown
        $agents = Agent::with('user')->get();

        return view('admin.commissions.report', compact('commissions', 'totals', 'agents', 'dateRange'));
    }

    /**
     * API endpoint: Get commission statistics for dashboard.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStats(Request $request)
    {
        $dateRange = $this->parseDateRange($request);

        $stats = [
            'total_commission' => Commission::whereBetween('created_at', $dateRange)->sum('commission_amount'),
            'total_transfers' => Transfer::whereBetween('created_at', $dateRange)->count(),
            'pending_commission' => Commission::whereBetween('created_at', $dateRange)->pending()->sum('commission_amount'),
            'approved_commission' => Commission::whereBetween('created_at', $dateRange)->approved()->sum('commission_amount'),
            'paid_commission' => Commission::whereBetween('created_at', $dateRange)->paid()->sum('commission_amount'),
            'top_agents' => $this->getTopAgents($dateRange, 10),
        ];

        return response()->json($stats);
    }

    /**
     * Create a commission record for a transfer.
     * This would typically be called when a transfer is completed.
     *
     * @param int $transferId
     * @return Commission
     */
    public function createCommissionForTransfer($transferId)
    {
        $transfer = Transfer::findOrFail($transferId);
        
        // Get agent from transfer
        $agent = Agent::where('user_id', $transfer->agent_id)->first();
        
        if (!$agent) {
            return null;
        }

        // Check if commission already exists for this transfer
        $existingCommission = Commission::where('transfer_id', $transferId)->first();
        if ($existingCommission) {
            return $existingCommission;
        }

        // Calculate commission
        $commissionData = $this->calculateCommission($agent, $transfer);

        // Create commission record
        $commission = Commission::create([
            'agent_id' => $agent->id,
            'transfer_id' => $transferId,
            'commission_amount' => $commissionData['amount'],
            'commission_rate' => $commissionData['rate'],
            'calculation_method' => $commissionData['method'],
            'transfer_amount' => $transfer->amount,
            'status' => 'pending',
        ]);

        return $commission;
    }

    /**
     * Mark a commission as approved.
     *
     * @param int $commissionId
     * @return Commission
     */
    public function approveCommission($commissionId)
    {
        $commission = Commission::findOrFail($commissionId);
        $commission->update(['status' => 'approved']);
        
        return $commission;
    }

    /**
     * Mark commissions as paid.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsPaid(Request $request)
    {
        $validated = $request->validate([
            'commission_ids' => 'required|array',
            'commission_ids.*' => 'integer|exists:agent_commissions,id',
        ]);

        Commission::whereIn('id', $validated['commission_ids'])->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'Commissions marked as paid']);
    }

    /**
     * Calculate commission for an agent based on transfer amount and agent settings.
     *
     * @param Agent $agent
     * @param Transfer $transfer
     * @return array
     */
    private function calculateCommission(Agent $agent, Transfer $transfer)
    {
        $commissionType = $agent->commission_type ?? 'percentage';
        $commissionRate = $agent->commission_rate ?? 0;

        if ($commissionType === 'percentage') {
            $amount = ($transfer->amount * $commissionRate) / 100;
        } else {
            // Fixed fee
            $amount = $commissionRate;
        }

        return [
            'amount' => round($amount, 2),
            'rate' => $commissionRate,
            'method' => $commissionType,
        ];
    }

    /**
     * Get commission statistics for a specific agent.
     *
     * @param int $agentId
     * @param array|null $dateRange
     * @return array
     */
    public function getAgentCommissionStats($agentId, $dateRange = null)
    {
        if ($dateRange === null) {
            $dateRange = [
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth(),
            ];
        }

        $agent = Agent::find($agentId);
        $commissionQuery = Commission::forAgent($agentId)->whereBetween('created_at', $dateRange);
        
        // Get transfers for this agent
        $transfers = Transfer::where('agent_id', $agent->user_id)
            ->whereBetween('created_at', $dateRange)
            ->get();

        // Get commission data
        $commissions = $commissionQuery->get();
        $totalTransferAmount = $commissions->sum('transfer_amount');
        $totalCommissions = $commissions->sum('commission_amount');
        $commissionCount = $commissions->count();

        return [
            'total_transfers' => $transfers->count() > 0 ? $transfers->count() : $commissionCount,
            'total_transfer_amount' => $transfers->sum('amount') > 0 ? $transfers->sum('amount') : $totalTransferAmount,
            'total_commission' => $totalCommissions,
            'pending_commission' => (clone $commissionQuery)->pending()->sum('commission_amount'),
            'approved_commission' => (clone $commissionQuery)->approved()->sum('commission_amount'),
            'paid_commission' => (clone $commissionQuery)->paid()->sum('commission_amount'),
            'commission_count' => $commissionCount,
            'average_commission' => $commissionCount > 0 ? $totalCommissions / $commissionCount : 0,
            'average_commission_per_transfer' => $commissionCount > 0 ? $totalCommissions / $commissionCount : 0,
        ];
    }

    /**
     * Calculate detailed commission statistics for a date range.
     *
     * @param int $agentId
     * @param array $dateRange
     * @return array
     */
    private function calculateAgentCommissionStats($agentId, $dateRange)
    {
        $agent = Agent::find($agentId);
        $query = Commission::forAgent($agentId)->whereBetween('created_at', $dateRange);
        
        $transfers = Transfer::where('agent_id', $agent->user_id)
            ->whereBetween('created_at', $dateRange)
            ->get();

        return [
            'total_transfers' => $transfers->count(),
            'total_transfer_amount' => $transfers->sum('amount'),
            'total_commission' => $query->sum('commission_amount'),
            'pending_commission' => $query->pending()->sum('commission_amount'),
            'approved_commission' => $query->approved()->sum('commission_amount'),
            'paid_commission' => $query->paid()->sum('commission_amount'),
            'commission_count' => $query->count(),
            'average_commission_per_transfer' => $query->count() > 0 ? $query->avg('commission_amount') : 0,
            'average_commission_percentage' => $transfers->count() > 0 
                ? ($query->sum('commission_amount') / $transfers->sum('amount')) * 100 
                : 0,
        ];
    }

    /**
     * Calculate totals for a commission report.
     *
     * @param array $dateRange
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    private function calculateReportTotals($dateRange, Request $request)
    {
        $query = Commission::whereBetween('created_at', $dateRange);

        if ($request->filled('agent_id')) {
            $query->where('agent_id', $request->agent_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('calculation_method')) {
            $query->where('calculation_method', $request->calculation_method);
        }

        return [
            'total_commission' => $query->sum('commission_amount'),
            'total_transfers' => $query->count(),
            'average_commission' => $query->count() > 0 ? $query->avg('commission_amount') : 0,
            // Use the same filtered query and apply status scopes so results respect all filters and date range
            'pending' => (clone $query)->pending()->sum('commission_amount'),
            'approved' => (clone $query)->approved()->sum('commission_amount'),
            'paid' => (clone $query)->paid()->sum('commission_amount'),
        ];
    }

    /**
     * Get top performing agents by commission.
     *
     * @param array $dateRange
     * @param int $limit
     * @return \Illuminate\Support\Collection
     */
    private function getTopAgents($dateRange, $limit = 10)
    {
        return Commission::whereBetween('created_at', $dateRange)
            ->groupBy('agent_id')
            ->selectRaw('agent_id, SUM(commission_amount) as total_commission, COUNT(*) as transfer_count')
            ->orderByDesc('total_commission')
            ->limit($limit)
            ->with('agent.user')
            ->get();
    }

    /**
     * Parse date range from request.
     * Supports: daily, weekly, monthly, custom (with start_date and end_date)
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    private function parseDateRange(Request $request)
    {
        $period = $request->get('period', 'monthly');
        
        $now = Carbon::now();

        switch ($period) {
            case 'daily':
                return [$now->copy()->startOfDay(), $now->copy()->endOfDay()];
            case 'weekly':
                return [$now->copy()->startOfWeek(), $now->copy()->endOfWeek()];
            case 'yearly':
                return [$now->copy()->startOfYear(), $now->copy()->endOfYear()];
            case 'custom':
                $startDate = $request->get('start_date') ? Carbon::parse($request->get('start_date')) : $now->copy()->startOfMonth();
                $endDate = $request->get('end_date') ? Carbon::parse($request->get('end_date')) : $now->copy()->endOfMonth();
                return [$startDate, $endDate];
            case 'monthly':
            default:
                return [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()];
        }
    }

    /**
     * Validate report filter inputs.
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    private function validateReportFilters(Request $request)
    {
        $request->validate([
            'period' => 'nullable|in:daily,weekly,monthly,yearly,custom',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'agent_id' => 'nullable|exists:agents,id',
            'status' => 'nullable|in:pending,approved,paid',
            'calculation_method' => 'nullable|in:percentage,fixed',
        ]);
    }

    /**
     * Export commission report to PDF.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function exportPDF(Request $request)
    {
        $dateRange = $this->parseDateRange($request);
        $query = Commission::with(['agent.user', 'transfer']);

        // Apply filters
        $query->whereBetween('created_at', $dateRange);

        if ($request->filled('agent_id')) {
            $query->where('agent_id', $request->agent_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('calculation_method')) {
            $query->where('calculation_method', $request->calculation_method);
        }

        $commissions = $query->get();
        $totals = $this->calculateReportTotals($dateRange, $request);
        $filters = ['dateRange' => $dateRange[0]->format('M d, Y') . ' to ' . $dateRange[1]->format('M d, Y')];

        return CommissionExportService::exportToPDF($commissions, $totals, $filters);
    }

    /**
     * Export commission report to Excel.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function exportExcel(Request $request)
    {
        $dateRange = $this->parseDateRange($request);
        $query = Commission::with(['agent.user', 'transfer']);

        // Apply filters
        $query->whereBetween('created_at', $dateRange);

        if ($request->filled('agent_id')) {
            $query->where('agent_id', $request->agent_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('calculation_method')) {
            $query->where('calculation_method', $request->calculation_method);
        }

        $commissions = $query->get();
        $totals = $this->calculateReportTotals($dateRange, $request);

        return CommissionExportService::exportToExcel($commissions, $totals);
    }
}

