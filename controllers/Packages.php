<?php

namespace Sixgweb\ClubReady\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Sixgweb\ClubReady\Models\Package;
use Sixgweb\ClubReady\Models\Installment;

/**
 * Packages Backend Controller
 *
 * @link https://docs.octobercms.com/4.x/extend/system/controllers.html
 */
class Packages extends Controller
{
    use \Sixgweb\ClubReady\Traits\CallsApi;

    public $implement = [
        \Backend\Behaviors\FormController::class,
        \Backend\Behaviors\ListController::class,
        \Backend\Behaviors\RelationController::class,
    ];

    /**
     * @var string formConfig file
     */
    public $formConfig = 'config_form.yaml';

    /**
     * @var string listConfig file
     */
    public $listConfig = 'config_list.yaml';

    public $relationConfig = 'config_relation.yaml';

    /**
     * @var array required permissions
     */
    public $requiredPermissions = ['sixgweb.clubready.packages'];

    /**
     * __construct the controller
     */
    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Sixgweb.ClubReady', 'clubready', 'packages');
    }

    public function onSyncPackages()
    {
        $disabled = Package::where('is_enabled', false)->pluck('clubready_id')->toArray();

        Installment::query()->delete();

        foreach ($this->getApiResponse('/sales/packages') as $packageData) {

            //Skip non-automatically renewing packages and those without contracts
            if (!isset($packageData['ContractId']) || $packageData['IsAutomRenew'] == false) {
                continue;
            }

            if (in_array($packageData['Id'], $disabled)) {
                continue;
            }

            $hasInstallments = false;

            foreach ($this->getApiResponse('/sales/packages/' . $packageData['Id'] . '/installments') as $installmentData) {
                if ($installmentData['ForOnlineSale'] == false) {
                    continue;
                }

                $hasInstallments = true;
                $setupFee = 0;

                foreach ($installmentData['Fees'] as $fee) {
                    if (isset($fee['DueUpfront']) && $fee['DueUpfront'] == true) {
                        $setupFee = trim($fee['Amount']);
                        break;
                    }
                }

                $calculationData = $this->getApiResponse('/sales/packages/' . $packageData['Id'] . '/installments/calculate/' . $installmentData['Id']);

                Installment::firstOrCreate([
                    'installment_id' => $installmentData['Id'],
                    'package_id' => $packageData['Id'],
                    'payment_count' => trim($installmentData['PaymentCount']),
                    'first_payment_amount' => $calculationData['SubTotal'] ?? 0,
                    'payment_amount' => $installmentData['DuePerPayment'],
                    'setup_fee' => $setupFee,
                ]);
            }

            if (!$hasInstallments) {
                continue;
            }

            $package = Package::firstOrNew([
                'clubready_id' => $packageData['Id'],
            ]);
            $package->name = $packageData['Name'];
            $package->price = trim($packageData['Price']);
            $package->save();
        }

        \Flash::success('Packages synchronized from ClubReady.');
    }
}
