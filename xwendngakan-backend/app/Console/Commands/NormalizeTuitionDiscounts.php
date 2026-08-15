<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Institution;

class NormalizeTuitionDiscounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:normalize-tuition-discounts {--dry-run : Show what would change without saving}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rewrites legacy tuition discounts (a raw price stored in the discount field) as a percentage plus final_price.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = (bool) $this->option('dry-run');
        $institutions = Institution::all();
        $this->info("Found {$institutions->count()} institutions. Normalizing tuition discounts...");

        $changedInstitutions = 0;
        $changedRows = 0;

        foreach ($institutions as $inst) {
            $dirty = false;

            // 1. tuition_plans — a flat list of {dept, fee, discount, ...}
            $plans = is_array($inst->tuition_plans)
                ? $inst->tuition_plans
                : (json_decode((string) $inst->tuition_plans, true) ?: []);
            if (is_array($plans)) {
                foreach ($plans as &$plan) {
                    if (is_array($plan) && $this->normalizeRow($plan)) {
                        $dirty = true;
                        $changedRows++;
                    }
                }
                unset($plan);
            }

            // 2. colleges — nested {name, fee, discount, depts: [...]}
            $colleges = json_decode((string) $inst->colleges, true);
            if (is_array($colleges)) {
                foreach ($colleges as &$college) {
                    if (!is_array($college)) continue;
                    if ($this->normalizeRow($college)) {
                        $dirty = true;
                        $changedRows++;
                    }
                    if (is_array($college['depts'] ?? null)) {
                        foreach ($college['depts'] as &$dept) {
                            if (is_array($dept) && $this->normalizeRow($dept)) {
                                $dirty = true;
                                $changedRows++;
                            }
                        }
                        unset($dept);
                    }
                }
                unset($college);
            }

            if (!$dirty) continue;

            $changedInstitutions++;
            $this->line("  #{$inst->id} {$inst->nku}");

            if (!$dryRun) {
                $inst->tuition_plans = $plans;
                if (is_array($colleges)) {
                    $inst->colleges = json_encode($colleges, JSON_UNESCAPED_UNICODE);
                }
                $inst->save();
            }
        }

        $verb = $dryRun ? 'would be updated' : 'updated';
        $this->info("Done. {$changedRows} rows across {$changedInstitutions} institutions {$verb}.");
        if ($dryRun) {
            $this->comment('Dry run — nothing was saved. Re-run without --dry-run to apply.');
        }

        return self::SUCCESS;
    }

    /**
     * Normalises one fee/discount row in place. Returns true when it changed.
     *
     * Rows already carrying a final_price are left alone; the rest are read the
     * way the portal now reads them — a discount of 100 or less is a percentage,
     * a larger value is the price after the discount.
     */
    private function normalizeRow(array &$row): bool
    {
        $feeStr = trim((string) ($row['fee'] ?? ''));
        $discStr = trim((string) ($row['discount'] ?? ''));
        $finalStr = trim((string) ($row['final_price'] ?? ''));

        if ($feeStr === '' || $discStr === '' || $finalStr !== '') return false;

        $fee = (float) preg_replace('/[^0-9.]/', '', $feeStr);
        $disc = (float) preg_replace('/[^0-9.]/', '', $discStr);
        if ($fee <= 0 || $disc <= 0) return false;

        if ($disc <= 100) {
            $percent = $disc;
            $discountAmt = $fee * ($disc / 100);
            $final = $fee - $discountAmt;
        } elseif ($disc < $fee) {
            $final = $disc;
            $discountAmt = $fee - $disc;
            $percent = $discountAmt / $fee * 100;
        } else {
            // Discount is not smaller than the fee — leave it for a human.
            return false;
        }

        $row['discount'] = rtrim(rtrim(number_format($percent, 1, '.', ''), '0'), '.');
        $row['final_price'] = number_format($final, 0, '.', ',');
        $row['discount_amount'] = number_format($discountAmt, 0, '.', ',');

        return true;
    }
}
