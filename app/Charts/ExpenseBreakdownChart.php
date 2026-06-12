<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;

class ExpenseBreakdownChart
{
    public function build($labels, $data): \ArielMejiaDev\LarapexCharts\PieChart
    {
        return (new LarapexChart)
            ->pieChart()
            ->setTitle('Expense Breakdown')
            ->setSubtitle('This Month by Category')
            ->addData($data)
            ->setLabels($labels)
            ->setColors([
                '#ef4444', '#f97316', '#f59e0b', '#eab308', '#84cc16',
                '#22c55e', '#10b981', '#14b8a6', '#06b6d4', '#0ea5e9',
                '#3b82f6', '#6366f1', '#8b5cf6', '#a855f7', '#d946ef'
            ])
            ->setHeight(380);
    }
}
