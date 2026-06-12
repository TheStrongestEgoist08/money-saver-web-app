<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;

class MonthlyExpenseChart
{
    public function build($labels, $data): \ArielMejiaDev\LarapexCharts\BarChart
    {
        return (new LarapexChart)
            ->barChart()
            ->setTitle('Monthly Expenses')
            ->setSubtitle(now()->year . ' • Total per Month')
            ->addData($data, 'Expenses')
            ->setXAxis($labels)
            ->setColors(['#f43f5e'])
            ->setHeight(420)
            ->setGrid(true);
    }
}
