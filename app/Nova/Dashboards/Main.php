<?php

namespace App\Nova\Dashboards;

use App\Nova\Metrics\CouponIsActive;
use App\Nova\Metrics\NumberCoupons;
use App\Nova\Metrics\NumberPartners;
use App\Nova\Metrics\NumberProductForCategory;
use Laravel\Nova\Cards\Help;
use App\Nova\Metrics\NewAdmin;
use App\Nova\Metrics\NewUsers;
use App\Nova\Metrics\NumberOrders;
use App\Nova\Metrics\NumberProduct;
use App\Nova\Metrics\Order\AcceptedOrdersValueMetrics;
use App\Nova\Metrics\Order\CanceledOrdersValueMetrics;
use App\Nova\Metrics\Order\FinishedOrdersValueMetrics;
use App\Nova\Metrics\Order\OrderPurchaseTrend;
use App\Nova\Metrics\Order\PendingOrdersValueMetrics;
use App\Nova\Metrics\Order\RejectedOrdersValueMetrics;
use App\Nova\Metrics\Order\TotalOrdersValueMetrics;
use App\Nova\Metrics\pendingOrders;
use Laravel\Nova\Dashboards\Main as Dashboard;

class Main extends Dashboard
{
    /**
     * Get the cards for the dashboard.
     *
     * @return array
     */
    public function cards()
    {
        return [
            new NewUsers,
        ];
    }


    public function name()
    {
        return (__('Main'));
    }
}
