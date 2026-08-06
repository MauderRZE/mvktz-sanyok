<?php

namespace Tests\Feature;

use App\Models\Equipment;
use App\Models\User;
use Tests\TestCase;

class EquipmentReportTest extends TestCase
{
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::firstOrCreate(
            ['login' => 'admin'],
            [
                'name' => 'admin',
                'password' => bcrypt('$B00ster!'),
                'role' => 'admin',
            ]
        );
    }

    public function test_equipment_report_handles_null_filters()
    {
        // Equipment with null status
        $eqWithNullStatus = Equipment::create([
            'inv_number' => 'EQ-NULL-STATUS',
            'account_name' => 'Тестове обладнання NULL',
            'status' => null,
        ]);

        // Equipment with non-null status
        $eqWithStatus = Equipment::create([
            'inv_number' => 'EQ-IN-USE',
            'account_name' => 'Тестове обладнання В експлуатації',
            'status' => 'в експлуатації',
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('admin.equipment.report', [
                'filterStatus' => ['null'],
            ]));

        $response->assertStatus(200);
        $response->assertSee('EQ-NULL-STATUS');
        $response->assertDontSee('EQ-IN-USE');

        $eqWithNullStatus->delete();
        $eqWithStatus->delete();
    }
}
