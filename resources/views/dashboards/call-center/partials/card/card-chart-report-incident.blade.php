<div class="col-xl-6">
    <div class="card">
        <div class="card-header enhanced-header">
            <div>
                <h4 class="card-title">Laporan Call Center 112</h4>
                <p>
                    Total Laporan <span id="total-report">0</span>
                </p>
            </div>
            <div class="header-actions">
                <div class="trend-stats">
                    <span id="period-label">Last 7 Days</span>
                    <span id="percent-trend" class="text-success">
                        <i class="fi fi-rr-arrow-up"></i> 0%
                    </span>
                </div>
                <select id="filter-period" class="filter-dropdown">
                    <option value="last7days" selected>7 Hari Terakhir</option>
                    <option value="thismonth">Bulan ini</option>
                </select>
            </div>
        </div>
        <div class="card-body">
            <canvas id="chartjsBalanceTrend"></canvas>
        </div>
    </div>
</div>s
