<?php

namespace Sixgweb\ClubReady\Traits;

use ApplicationException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Sixgweb\ClubReady\Models\Setting as ClubReadySetting;
use Sixgweb\ClubReady\Models\Package;
use Sixgweb\ClubReady\Models\Installment;

trait SyncsPackages
{
    public function syncClubReadyPackages()
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

                $firstPaymentAmount = $calculationData['SubTotal'] ?? 0;
                foreach ($calculationData['Payments'] as $payment) {
                    if ($payment['PayToday']) {
                        $firstPaymentAmount = $payment['Amount'];
                        break;
                    }
                }

                Installment::firstOrCreate([
                    'installment_id' => $installmentData['Id'],
                    'package_id' => $packageData['Id'],
                    'payment_count' => trim($installmentData['PaymentCount']),
                    'first_payment_amount' => $firstPaymentAmount,
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
    }
}
