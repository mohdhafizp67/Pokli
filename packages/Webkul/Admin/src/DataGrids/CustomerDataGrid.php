<?php

namespace Webkul\Admin\DataGrids;

use Artanis\GapSap\Models\GoldSilverHistory;
use Webkul\Ui\DataGrid\DataGrid;
use DB;
use Webkul\Customer\Models\Customer;

/**
 * CustomerDataGrid class
 *
 * @author Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CustomerDataGrid extends DataGrid
{
    protected $index = 'customer_id'; //the column that needs to be treated as index column

    protected $sortOrder = 'desc'; //asc or desc

    protected $itemsPerPage = 10;

    public function goldTotal($id){
        $purchase = GoldSilverHistory::where('customer_id', $id)->where('activity', 'purchase')->where('product_type', 'gold')->where('status', 'completed')->sum('quantity');
        $buyback = GoldSilverHistory::where('customer_id', $id)->where('activity', 'buyback')->where('product_type', 'gold')->where('status', 'completed')->sum('quantity');

        return $purchase-$buyback;
    }

    public function silverTotal($id){
        $purchase = GoldSilverHistory::where('customer_id', $id)->where('activity', 'purchase')->where('product_type', 'silver')->where('status', 'completed')->sum('quantity');
        $buyback = GoldSilverHistory::where('customer_id', $id)->where('activity', 'buyback')->where('product_type', 'silver')->where('status', 'completed')->sum('quantity');

        return $purchase-$buyback;
    }

    public function prepareQueryBuilder()
    {
        // $gold = $this->goldTotal(2);
        // $silver = $this->silverTotal(2);
        // DB::enableQueryLog();
        // $gold = DB::table('gold_silver_history')
        //         ->select('customer_id', 'activity', 'product_type', 'quantity', DB::raw('SUM(quantity) AS quantity_pg'))
        //         ->where('activity','purchase')
        //         ->where('product_type','gold')
        //         ->get();
        // dd(DB::getQueryLog());
        // dd($gold);
        

        $queryBuilder = DB::table('customers')
                ->leftJoin('customer_groups', 'customers.customer_group_id', '=', 'customer_groups.id')
                ->leftJoin('gold_silver_history as gold_history', function($leftJoin) {
                    $leftJoin->on('gold_history.customer_id', '=', 'customers.id')
                             ->distinct('gold_history.customer_id');
                })
                ->leftJoin('gold_silver_history as silver_history', function($leftJoin) {
                    $leftJoin->on('silver_history.customer_id', '=', 'customers.id')
                             ->distinct('silver_history.customer_id');
                })
                ->addSelect('customers.id as customer_id', 'customers.email', 'customer_groups.name', 'customers.status', 'gold_history.quantity', 'silver_history.quantity')
                ->addSelect(DB::raw('CONCAT(customers.first_name, " ", customers.last_name) as full_name'))->groupBy('customers.id');

        $this->addFilter('customer_id', 'customers.id');
        $this->addFilter('full_name', DB::raw('CONCAT(customers.first_name, " ", customers.last_name)'));

        $this->setQueryBuilder($queryBuilder);
        // dd($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index' => 'customer_id',
            'label' => trans('admin::app.datagrid.id'),
            'type' => 'number',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'full_name',
            'label' => trans('admin::app.datagrid.name'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'email',
            'label' => trans('admin::app.datagrid.email'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'gold_history.quantity',
            'label' => 'Gold',
            'type' => 'string',
            // 'searchable' => true,
            'sortable' => true,
            // 'filterable' => true,
            'wrapper' => function ($value) {
                return $gold = $this->goldTotal($value->customer_id);
            }
        ]);

        $this->addColumn([
            'index' => 'silver_history.quantity',
            'label' => 'Silver',
            'type' => 'string',
            // 'searchable' => true,
            'sortable' => true,
            // 'filterable' => true,
            'wrapper' => function ($value) {
                return $silver = $this->silverTotal($value->customer_id);
            }
        ]);

        $this->addColumn([
            'index' => 'name',
            'label' => trans('admin::app.datagrid.group'),
            'type' => 'string',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'status',
            'label' => trans('admin::app.datagrid.status'),
            'type' => 'boolean',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true,
            'wrapper' => function ($row) {
                if ($row->status == 1) {
                    return 'Activated';
                } else {
                    return 'Blocked';
                }
            }
        ]);
    }

    public function prepareActions() {
        $this->addAction([
            'method' => 'GET', // use GET request only for redirect purposes
            'route' => 'admin.customer.edit',
            'icon' => 'icon pencil-lg-icon',
            'title' => trans('admin::app.customers.customers.edit-help-title')
        ]);

        $this->addAction([
            'method' => 'POST', // use GET request only for redirect purposes
            'route' => 'admin.customer.delete',
            'icon' => 'icon trash-icon',
            'title' => trans('admin::app.customers.customers.delete-help-title')
        ]);

        $this->addAction([
            'method' => 'GET',
            'route' => 'admin.customer.note.create',
            'icon' => 'icon note-icon',
            'title' => trans('admin::app.customers.note.help-title')
        ]);
    }

    /**
     * Customer Mass Action To Delete And Change Their
     */
    public function prepareMassActions()
    {
        $this->addMassAction([
            'type' => 'delete',
            'label' => 'Delete',
            'action' => route('admin.customer.mass-delete'),
            'method' => 'PUT',
        ]);

        $this->addMassAction([
            'type' => 'update',
            'label' => 'Update Status',
            'action' => route('admin.customer.mass-update'),
            'method' => 'PUT',
            'options' => [
                'Active' => 1,
                'Inactive' => 0
            ]
        ]);

        $this->enableMassAction = true;
    }
}