<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MallShop;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class MallShopController extends Controller
{
    public function index()
    {
        $shops = MallShop::orderBy('floor')->orderBy('number')->get();
        
        // Map to frontend format
        $data = $shops->map(function ($shop) {
            return [
                'id' => $shop->id,
                'floor' => $shop->floor,
                'number' => $shop->number,
                'ownerName' => $shop->owner_name,
                'ownerPhone' => $shop->owner_phone,
                'ownerIdNumber' => $shop->owner_id_number,
                'tenantName' => $shop->tenant_name,
                'tenantPhone' => $shop->tenant_phone,
                'tenantIdNumber' => $shop->tenant_id_number,
                'saleValue' => (float) $shop->sale_value,
                'rentValue' => (float) $shop->rent_value,
                'leaseYears' => $shop->lease_years,
                'leaseStartDate' => $shop->lease_start_date?->format('Y-m-d'),
                'leaseEndDate' => $shop->lease_end_date?->format('Y-m-d'),
                'notes' => $shop->notes,
            ];
        });
        
        return response()->json(['data' => $data]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'floor' => 'nullable|string',
            'number' => 'nullable|integer',
            'ownerName' => 'nullable|string',
            'ownerPhone' => 'nullable|string',
            'ownerIdNumber' => 'nullable|string',
            'tenantName' => 'nullable|string',
            'tenantPhone' => 'nullable|string',
            'tenantIdNumber' => 'nullable|string',
            'saleValue' => 'nullable|numeric',
            'rentValue' => 'nullable|numeric',
            'leaseYears' => 'nullable|integer',
            'leaseStartDate' => 'nullable|date',
            'leaseEndDate' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);
        
        // Map from frontend to database format using has() to allow empty strings
        $dbData = [
            'floor' => $request->floor,
            'number' => $request->number ?? 0,
            'owner_name' => $request->has('ownerName') ? $request->ownerName : null,
            'owner_phone' => $request->has('ownerPhone') ? $request->ownerPhone : null,
            'owner_id_number' => $request->has('ownerIdNumber') ? $request->ownerIdNumber : null,
            'tenant_name' => $request->has('tenantName') ? $request->tenantName : null,
            'tenant_phone' => $request->has('tenantPhone') ? $request->tenantPhone : null,
            'tenant_id_number' => $request->has('tenantIdNumber') ? $request->tenantIdNumber : null,
            'sale_value' => $request->has('saleValue') ? ($request->saleValue ?: 0) : 0,
            'rent_value' => $request->has('rentValue') ? ($request->rentValue ?: 0) : 0,
            'lease_years' => $request->has('leaseYears') ? ($request->leaseYears ?: 0) : 0,
            'lease_start_date' => $request->has('leaseStartDate') ? ($request->leaseStartDate ?: null) : null,
            'lease_end_date' => $request->has('leaseEndDate') ? ($request->leaseEndDate ?: null) : null,
            'notes' => $request->has('notes') ? $request->notes : null,
        ];

        $lookup = [
            'floor' => $dbData['floor'],
            'number' => $dbData['number'],
        ];

        $shop = MallShop::withTrashed()->where($lookup)->first();
        $isUpdate = false;
        if ($shop) {
            $isUpdate = true;
            if ($shop->trashed()) {
                $shop->restore();
            }
            $shop->fill($dbData);
            $shop->save();
        } else {
            $shop = MallShop::create($dbData);
        }

        ActivityLog::record(
            $isUpdate ? "تحديث بيانات محل" : "إضافة محل جديد",
            "محل رقم {$shop->number} - {$shop->floor}",
            "shop"
        );
        
        // Return in frontend format
        $responseData = [
            'id' => $shop->id,
            'floor' => $shop->floor,
            'number' => $shop->number,
            'ownerName' => $shop->owner_name,
            'ownerPhone' => $shop->owner_phone,
            'ownerIdNumber' => $shop->owner_id_number,
            'tenantName' => $shop->tenant_name,
            'tenantPhone' => $shop->tenant_phone,
            'tenantIdNumber' => $shop->tenant_id_number,
            'saleValue' => (float) $shop->sale_value,
            'rentValue' => (float) $shop->rent_value,
            'leaseYears' => $shop->lease_years,
            'leaseStartDate' => $shop->lease_start_date?->format('Y-m-d'),
            'leaseEndDate' => $shop->lease_end_date?->format('Y-m-d'),
            'notes' => $shop->notes,
        ];

        return response()->json(
            ['data' => $responseData, 'message' => $isUpdate ? 'تم تحديث المحل بنجاح' : 'تم إضافة المحل بنجاح'],
            $isUpdate ? 200 : 201
        );
    }

    public function show(MallShop $mallShop)
    {
        return response()->json(['data' => $mallShop]);
    }

    public function update(Request $request, MallShop $mallShop)
    {
        $validated = $request->validate([
            'floor' => 'nullable|string',
            'number' => 'nullable|integer',
            'ownerName' => 'nullable|string',
            'ownerPhone' => 'nullable|string',
            'ownerIdNumber' => 'nullable|string',
            'tenantName' => 'nullable|string',
            'tenantPhone' => 'nullable|string',
            'tenantIdNumber' => 'nullable|string',
            'saleValue' => 'nullable|numeric',
            'rentValue' => 'nullable|numeric',
            'leaseYears' => 'nullable|integer',
            'leaseStartDate' => 'nullable|date',
            'leaseEndDate' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);
        
        // Map from frontend to database format using has() to allow clearing fields
        $dbData = [];
        if ($request->has('floor')) $dbData['floor'] = $request->floor;
        if ($request->has('number')) $dbData['number'] = $request->number;
        if ($request->has('ownerName')) $dbData['owner_name'] = $request->ownerName;
        if ($request->has('ownerPhone')) $dbData['owner_phone'] = $request->ownerPhone;
        if ($request->has('ownerIdNumber')) $dbData['owner_id_number'] = $request->ownerIdNumber;
        if ($request->has('tenantName')) $dbData['tenant_name'] = $request->tenantName;
        if ($request->has('tenantPhone')) $dbData['tenant_phone'] = $request->tenantPhone;
        if ($request->has('tenantIdNumber')) $dbData['tenant_id_number'] = $request->tenantIdNumber;
        if ($request->has('saleValue')) $dbData['sale_value'] = $request->saleValue ?: 0;
        if ($request->has('rentValue')) $dbData['rent_value'] = $request->rentValue ?: 0;
        if ($request->has('leaseYears')) $dbData['lease_years'] = $request->leaseYears ?: 0;
        if ($request->has('leaseStartDate')) $dbData['lease_start_date'] = $request->leaseStartDate ?: null;
        if ($request->has('leaseEndDate')) $dbData['lease_end_date'] = $request->leaseEndDate ?: null;
        if ($request->has('notes')) $dbData['notes'] = $request->notes;
        
        $mallShop->update($dbData);
        
        ActivityLog::record("تحديث بيانات محل", "محل رقم {$mallShop->number}", "shop");
        
        // Return in frontend format
        $responseData = [
            'id' => $mallShop->id,
            'floor' => $mallShop->floor,
            'number' => $mallShop->number,
            'ownerName' => $mallShop->owner_name,
            'ownerPhone' => $mallShop->owner_phone,
            'ownerIdNumber' => $mallShop->owner_id_number,
            'tenantName' => $mallShop->tenant_name,
            'tenantPhone' => $mallShop->tenant_phone,
            'tenantIdNumber' => $mallShop->tenant_id_number,
            'saleValue' => (float) $mallShop->sale_value,
            'rentValue' => (float) $mallShop->rent_value,
            'leaseYears' => $mallShop->lease_years,
            'leaseStartDate' => $mallShop->lease_start_date?->format('Y-m-d'),
            'leaseEndDate' => $mallShop->lease_end_date?->format('Y-m-d'),
            'notes' => $mallShop->notes,
        ];
        
        return response()->json(['data' => $responseData, 'message' => 'تم تحديث المحل بنجاح']);
    }

    public function destroy(MallShop $mallShop)
    {
        $mallShop->delete();
        
        ActivityLog::record("حذف محل", "محل رقم {$mallShop->number}", "shop");
        
        return response()->json(['message' => 'تم حذف المحل بنجاح']);
    }
}
