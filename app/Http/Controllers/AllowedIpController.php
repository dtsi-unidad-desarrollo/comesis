<?php

namespace App\Http\Controllers;

use App\Models\AllowedIp;
use Illuminate\Http\Request;

class AllowedIpController extends Controller
{
    public function index()
    {
        $ips = AllowedIp::orderByDesc('created_at')->get();
        return view('admin.allowed-ips.index', compact('ips'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ip_address' => 'required|ip|unique:allowed_ips,ip_address',
            'description' => 'nullable|string|max:255',
        ]);

        AllowedIp::create($request->only(['ip_address', 'description']));

        return redirect()->route('admin.allowed-ips.index')->with(['mensaje' => 'IP agregada correctamente.', 'estatus' => 200]);
    }

    public function update(Request $request, AllowedIp $allowedIp)
    {
        $request->validate([
            'ip_address' => 'required|ip|unique:allowed_ips,ip_address,' . $allowedIp->id,
            'description' => 'nullable|string|max:255',
            'status' => 'nullable|boolean',
        ]);

        $allowedIp->update($request->only(['ip_address', 'description', 'status']));

        return redirect()->route('admin.allowed-ips.index')->with(['mensaje' => 'IP actualizada correctamente.', 'estatus' => 200]);
    }

    public function destroy(AllowedIp $allowedIp)
    {
        $allowedIp->delete();

        return redirect()->route('admin.allowed-ips.index')->with(['mensaje' => 'IP eliminada correctamente.', 'estatus' => 200]);
    }
}
