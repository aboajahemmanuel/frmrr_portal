<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use App\Models\SubscriptionTier;
use App\Models\SubscriptionSubTier;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Seeds the fee structure approved in the "Supporting Requirements for the FMRR Fee Plan"
 * memo: the Institutional Subscription tenors and the Academic Subscription tier with its
 * three sub-tiers (Individual Academic, Institutional Academic, Student Research).
 *
 * Also deactivates (status = 0, not deleted) the old Monthly/Annual Members/Non-Members
 * test plans so they stop showing up as signup options on the public subscribe page.
 *
 * Idempotent: safe to re-run, records are matched by name and updated in place.
 *
 * Out of scope (no enforcement exists yet, tracked for later slices):
 * - Student Research's ID verification, view-only/no-download restriction
 * - The 6-month 100% launch promotional discount
 */
class FmrrFeeStructureSeeder extends Seeder
{
    private string $groupId = '5';

    /**
     * Pre-existing test/placeholder plans that predate this fee structure. Deactivated
     * (not deleted) so any subscriber already on one keeps their access, but they no
     * longer appear as a signup option alongside the real Institutional/Academic plans.
     */
    private array $legacyPlanNames = [
        'Monthly (Non-Members)',
        'Monthly (Members)',
        'Annual (Non-Members)',
        'Annual (Members)',
    ];

    public function run(): void
    {
        $this->deactivateLegacyPlans();
        $this->seedInstitutionalSubscription();

        $academicTier = $this->seedTier('Academic Subscription', 'Discounted subscription access for academic users conducting non-commercial research.');

        $individual = $this->seedSubTier($academicTier, 'Individual Academic Subscription', 'Access subject to verification via institutional email or other proof of academic affiliation. Billed at 20% of the Institutional Subscription fee.');
        $this->seedPlan('Individual Academic Subscription - Monthly', 34668.75, 34.67, 30, $individual->id);
        $this->seedPlan('Individual Academic Subscription - Quarterly', 96212.50, 96.21, 90, $individual->id);
        $this->seedPlan('Individual Academic Subscription - Annual', 332820.00, 332.82, 365, $individual->id);

        $institutionalAcademic = $this->seedSubTier($academicTier, 'Institutional Academic Subscription', 'For eligible university departments, faculties, and recognised research centres. Grants access to up to 5 authorised faculty members/students for 12 months. Billed at 50% of the Annual Institutional Subscription fee.');
        $this->seedPlan('Institutional Academic Subscription - Annual', 832050.00, 832.05, 365, $institutionalAcademic->id, seatLimit: 5);

        $studentResearch = $this->seedSubTier($academicTier, 'Student Research', 'Complimentary 30-day access for students undertaking dissertations, theses, or other research projects, subject to verification and approval. View-only; downloads disabled.');
        $this->seedPlan('Student Research - Complimentary (30 Days)', 0, 0, 30, $studentResearch->id, downloadLimit: 0);
    }

    private function deactivateLegacyPlans(): void
    {
        SubscriptionPlan::whereIn('name', $this->legacyPlanNames)->update(['status' => 0]);
    }

    private function seedInstitutionalSubscription(): void
    {
        $this->seedPlan('Institutional Subscription - Monthly', 173343.75, 173.34, 30, null);
        $this->seedPlan('Institutional Subscription - Quarterly', 481062.50, 481.06, 90, null);
        $this->seedPlan('Institutional Subscription - Annual', 1664100.00, 1664.10, 365, null);
    }

    private function seedTier(string $name, string $description): SubscriptionTier
    {
        $tier = SubscriptionTier::firstOrNew(['name' => $name]);
        $tier->slug = Str::slug($name);
        $tier->description = $description;
        $tier->group_id = $this->groupId;
        $tier->status = 1;
        $tier->admin_status = 1;
        $tier->save();

        return $tier;
    }

    private function seedSubTier(SubscriptionTier $tier, string $name, string $description): SubscriptionSubTier
    {
        $subTier = SubscriptionSubTier::firstOrNew(['name' => $name, 'subscription_tier_id' => $tier->id]);
        $subTier->slug = Str::slug($name);
        $subTier->description = $description;
        $subTier->group_id = $this->groupId;
        $subTier->status = 1;
        $subTier->admin_status = 1;
        $subTier->save();

        return $subTier;
    }

    private function seedPlan(string $name, float $priceNgn, float $priceUsd, int $durationDays, ?int $subTierId, int $downloadLimit = 9999, ?int $seatLimit = null): void
    {
        // SubscriptionPlan has no $fillable (matches TransactionController's own convention of
        // setting properties directly rather than mass-assigning), so build it the same way here.
        $plan = SubscriptionPlan::firstOrNew(['name' => $name]);
        $plan->price = $priceNgn;
        $plan->price_usd = $priceUsd;
        $plan->duration = $durationDays;
        $plan->description = $name;
        $plan->notification_days = 7;
        $plan->download_limit = $downloadLimit;
        $plan->seat_limit = $seatLimit;
        $plan->group_id = $this->groupId;
        $plan->subscription_sub_tier_id = $subTierId;
        $plan->status = 1;
        $plan->admin_status = 1;
        $plan->save();
    }
}
