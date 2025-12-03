<?php

namespace App\Services;

use App\Models\Commission;
use App\Models\Agent;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * CommissionExportService
 * 
 * Handles exporting commission data to PDF and Excel formats.
 * Provides methods to generate formatted reports for admin use.
 */
class CommissionExportService
{
    /**
     * Generate CSV data for commissions (can be used for Excel export).
     *
     * @param Collection $commissions
     * @param array $totals
     * @return string
     */
    public static function generateCSV(Collection $commissions, array $totals = []): string
    {
        $csv = "Agent Name,Store Name,Transfer ID,Transfer Amount,Commission Amount,Commission Rate,Calculation Method,Status,Created Date,Paid Date\n";

        foreach ($commissions as $commission) {
            $agentName = $commission->agent->user->name ?? 'Unknown';
            $storeName = $commission->agent->store_name ?? 'N/A';
            $transferId = $commission->transfer_id;
            $transferAmount = number_format($commission->transfer_amount, 2);
            $commissionAmount = number_format($commission->commission_amount, 2);
            $rate = $commission->commission_rate;
            $method = ucfirst($commission->calculation_method);
            $status = ucfirst($commission->status);
            $createdDate = $commission->created_at->format('Y-m-d H:i:s');
            $paidDate = $commission->paid_at ? $commission->paid_at->format('Y-m-d H:i:s') : 'Not Paid';

            $csv .= "\"{$agentName}\",\"{$storeName}\",{$transferId},{$transferAmount},{$commissionAmount},{$rate},{$method},{$status},{$createdDate},{$paidDate}\n";
        }

        // Add totals section
        $csv .= "\n\n";
        $csv .= "SUMMARY REPORT\n";
        $csv .= "Total Commission," . number_format($totals['total_commission'] ?? 0, 2) . "\n";
        $csv .= "Total Transfers," . ($totals['total_transfers'] ?? 0) . "\n";
        $csv .= "Average Commission," . number_format($totals['average_commission'] ?? 0, 2) . "\n";
        $csv .= "Pending Commission," . number_format($totals['pending'] ?? 0, 2) . "\n";
        $csv .= "Approved Commission," . number_format($totals['approved'] ?? 0, 2) . "\n";
        $csv .= "Paid Commission," . number_format($totals['paid'] ?? 0, 2) . "\n";

        return $csv;
    }

    /**
     * Generate HTML for PDF export.
     * This returns HTML that can be converted to PDF using a library like mPDF or DOMPDF.
     *
     * @param Collection $commissions
     * @param array $totals
     * @param array $filters
     * @return string
     */
    public static function generateHTML(Collection $commissions, array $totals = [], array $filters = []): string
    {
        $generatedDate = now()->format('Y-m-d H:i:s');
        $dateRange = $filters['dateRange'] ?? 'All Time';
        $totalCommission = number_format($totals['total_commission'] ?? 0, 2);
        $totalTransfers = $totals['total_transfers'] ?? 0;
        $averageCommission = number_format($totals['average_commission'] ?? 0, 2);
        $pendingCommission = number_format($totals['pending'] ?? 0, 2);
        $approvedCommission = number_format($totals['approved'] ?? 0, 2);
        $paidCommission = number_format($totals['paid'] ?? 0, 2);

        $html = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Commission Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #007bff;
        }
        .header .subtitle {
            color: #666;
            margin-top: 5px;
        }
        .meta-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            font-size: 12px;
            color: #666;
        }
        .summary {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #dee2e6;
        }
        .summary-row:last-child {
            margin-bottom: 0;
            border-bottom: none;
        }
        .summary-label {
            font-weight: bold;
        }
        .summary-value {
            text-align: right;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table thead {
            background-color: #007bff;
            color: white;
        }
        table th {
            padding: 12px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #dee2e6;
        }
        table td {
            padding: 10px 12px;
            border: 1px solid #dee2e6;
        }
        table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .amount {
            text-align: right;
            font-weight: 500;
            color: #28a745;
        }
        .status-pending {
            background-color: #ffc107;
            color: #000;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 11px;
        }
        .status-approved {
            background-color: #17a2b8;
            color: white;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 11px;
        }
        .status-paid {
            background-color: #28a745;
            color: white;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 11px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 11px;
            color: #999;
            border-top: 1px solid #dee2e6;
            padding-top: 15px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Commission Report</h1>
        <div class="subtitle">Agent Commission Earnings & Analysis</div>
    </div>

    <div class="meta-info">
        <div><strong>Report Generated:</strong> {$generatedDate}</div>
        <div><strong>Period:</strong> {$dateRange}</div>
    </div>

    <div class="summary">
        <div class="summary-row">
            <span class="summary-label">Total Commission:</span>
            <span class="summary-value">\${$totalCommission}</span>
        </div>
        <div class="summary-row">
            <span class="summary-label">Total Transfers:</span>
            <span class="summary-value">{$totalTransfers}</span>
        </div>
        <div class="summary-row">
            <span class="summary-label">Average Commission:</span>
            <span class="summary-value">\${$averageCommission}</span>
        </div>
        <div class="summary-row">
            <span class="summary-label">Pending Commission:</span>
            <span class="summary-value">\${$pendingCommission}</span>
        </div>
        <div class="summary-row">
            <span class="summary-label">Approved Commission:</span>
            <span class="summary-value">\${$approvedCommission}</span>
        </div>
        <div class="summary-row">
            <span class="summary-label">Paid Commission:</span>
            <span class="summary-value">\${$paidCommission}</span>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Agent Name</th>
                <th>Store Name</th>
                <th>Transfer ID</th>
                <th>Transfer Amount</th>
                <th>Commission Amount</th>
                <th>Rate (%)</th>
                <th>Method</th>
                <th>Status</th>
                <th>Created Date</th>
            </tr>
        </thead>
        <tbody>
HTML;

        foreach ($commissions as $commission) {
            $statusClass = 'status-' . $commission->status;
            $statusText = ucfirst($commission->status);
            $agentName = $commission->agent->user->name ?? 'N/A';
            $storeName = $commission->agent->store_name ?? 'N/A';
            $transferId = $commission->transfer_id;
            $transferAmount = number_format($commission->transfer_amount, 2);
            $commissionAmount = number_format($commission->commission_amount, 2);
            $rate = $commission->commission_rate;
            $method = ucfirst($commission->calculation_method);
            $createdDate = $commission->created_at->format('M d, Y');
            
            $html .= <<<HTML
            <tr>
                <td>{$agentName}</td>
                <td>{$storeName}</td>
                <td>#{$transferId}</td>
                <td class="amount">\${$transferAmount}</td>
                <td class="amount">\${$commissionAmount}</td>
                <td>{$rate}%</td>
                <td>{$method}</td>
                <td><span class="{$statusClass}">{$statusText}</span></td>
                <td>{$createdDate}</td>
            </tr>
HTML;
        }

        $html .= <<<HTML
        </tbody>
    </table>

    <div class="footer">
        <p>This report was automatically generated. For questions, contact the Finance Admin.</p>
        <p>© {$generatedDate} Money Transfer System. All rights reserved.</p>
    </div>
</body>
</html>
HTML;

        return $html;
    }

    /**
     * Export commissions to PDF file.
     * Requires a PDF library. This method demonstrates the structure.
     * Install: composer require barryvdh/laravel-dompdf
     *
     * @param Collection $commissions
     * @param array $totals
     * @param array $filters
     * @return \Illuminate\Http\Response
     */
    public static function exportToPDF(Collection $commissions, array $totals = [], array $filters = [])
    {
        $html = self::generateHTML($commissions, $totals, $filters);
        $filename = 'commission_report_' . now()->format('Y_m_d_H_i_s') . '.pdf';

        // This requires Laravel DOMPDF package
        // Install: composer require barryvdh/laravel-dompdf
        // @noinspection PhpUndefinedClassInspection
        if (class_exists('Barryvdh\\DomPDF\\Facade\\Pdf')) {
            // @noinspection PhpUndefinedClassInspection
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html);
            return $pdf->download($filename);
        }

        // Fallback: return HTML for manual PDF conversion
        return response($html, 200)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Export commissions to Excel file.
     * Requires Laravel Excel package.
     * Install: composer require maatwebsite/excel
     *
     * @param Collection $commissions
     * @param array $totals
     * @return \Illuminate\Http\Response
     */
    public static function exportToExcel(Collection $commissions, array $totals = [])
    {
        $csv = self::generateCSV($commissions, $totals);
        $filename = 'commission_report_' . now()->format('Y_m_d_H_i_s') . '.csv';

        return response($csv, 200)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Generate agent-specific commission summary.
     *
     * @param Agent $agent
     * @param array $dateRange
     * @return string
     */
    public static function generateAgentSummary(Agent $agent, array $dateRange = []): string
    {
        $commissions = Commission::forAgent($agent->id)
            ->when(!empty($dateRange), function ($query) use ($dateRange) {
                return $query->whereBetween('created_at', $dateRange);
            })
            ->get();

        $generatedDate = now()->format('Y-m-d H:i:s');
        $agentName = $agent->user->name ?? 'Unknown';
        $storeName = $agent->store_name ?? 'N/A';
        $rate = $agent->commission_rate ?? 0;
        $type = ucfirst($agent->commission_type ?? 'percentage');

        $totalCommission = $commissions->sum('commission_amount');
        $totalTransfers = $commissions->count();
        $averageCommission = $totalTransfers > 0 ? $totalCommission / $totalTransfers : 0;

        // Pre-format display strings to safely embed in the HTML heredoc
        $totalCommissionDisplay = '$' . number_format($totalCommission, 2);
        $averageCommissionDisplay = '$' . number_format($averageCommission, 2);

        $html = <<<HTML
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Commission Summary - {$agentName}</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 20px;
                    color: #333;
                }
                .header {
                    text-align: center;
                    margin-bottom: 30px;
                }
                .agent-info {
                    background-color: #f8f9fa;
                    border: 1px solid #dee2e6;
                    padding: 15px;
                    margin-bottom: 20px;
                    border-radius: 4px;
                }
                .stats {
                    display: grid;
                    grid-template-columns: 1fr 1fr 1fr;
                    gap: 15px;
                    margin-bottom: 20px;
                }
                .stat-card {
                    border: 1px solid #dee2e6;
                    padding: 15px;
                    border-radius: 4px;
                    text-align: center;
                }
                .stat-value {
                    font-size: 24px;
                    font-weight: bold;
                    color: #007bff;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-top: 20px;
                }
                table th, table td {
                    border: 1px solid #dee2e6;
                    padding: 10px;
                    text-align: left;
                }
                table thead {
                    background-color: #007bff;
                    color: white;
                }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>Commission Summary</h1>
                <p>{$agentName}</p>
            </div>

            <div class="agent-info">
                <p><strong>Store Name:</strong> {$storeName}</p>
                <p><strong>Commission Rate:</strong> {$rate}% ({$type})</p>
                <p><strong>Report Generated:</strong> {$generatedDate}</p>
            </div>

            <div class="stats">
                <div class="stat-card">
                    <div>Total Commission</div>
                    <div class="stat-value">{$totalCommissionDisplay}</div>
                </div>
                <div class="stat-card">
                    <div>Total Transfers</div>
                    <div class="stat-value">{$totalTransfers}</div>
                </div>
                <div class="stat-card">
                    <div>Average Commission</div>
                    <div class="stat-value">{$averageCommissionDisplay}</div>
                </div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Transfer ID</th>
                        <th>Amount</th>
                        <th>Commission</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
HTML;
        foreach ($commissions as $commission) {
            $transferAmount = number_format($commission->transfer_amount, 2);
            $commissionAmount = number_format($commission->commission_amount, 2);
            $statusText = ucfirst($commission->status);
            $date = $commission->created_at->format('M d, Y');

            $html .= "                    <tr>\n";
            $html .= "                        <td>#{$commission->transfer_id}</td>\n";
            $html .= "                        <td>$" . $transferAmount . "</td>\n";
            $html .= "                        <td>$" . $commissionAmount . "</td>\n";
            $html .= "                        <td>{$statusText}</td>\n";
            $html .= "                        <td>{$date}</td>\n";
            $html .= "                    </tr>\n";
        }

        $html .= <<<HTML
                </tbody>
            </table>
        </body>
        </html>
HTML;

        return $html;
    }
}
