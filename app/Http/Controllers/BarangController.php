<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use DNS2D;
use DataTables;
use Validator;

class BarangController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth','revalidate']);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Barang::where('kategori',1)->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn ='<a href="'.route('data-barang.show', $row->id).'" data-toggle="tooltip" title="Edit" data-id="'.$row->id.'" data-original-title="Barcode" class="btn btn-success btn-sm barcod"><i class="metismenu-icon pe-7s-plugin"></i></a>';
                    $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip" title="Edit" data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-warning btn-sm edit"><i class="metismenu-icon pe-7s-pen"></i></a>';
                    $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip" title="Hapus" data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm delete"><i class="metismenu-icon pe-7s-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
            }
        return view('barang.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'lokasi' => 'required',
        ], $messages = [
            'nama.required' => 'Kolom Nama Barang Wajib Diisi',
            'lokasi.required' => 'Kolom Lokasi Wajib Diisi',
        ]);
        if($validator->passes()) {
            $qr_code = Str::random(20);
            $nama = Barang::updateOrCreate(
                ['id' => $request->id],
                [
                    'qr_code' => $qr_code,
                    'nama' => $request->nama,
                    'lokasi' => $request->lokasi,
                    'keterangan' => DNS2D::getBarcodeHTML("http://sarpras.primainsanigarut.sch.id/scan-barang/".$qr_code, 'QRCODE'),
                    'kategori' => 1,
                    'stok' => 0,
                ]
            );
            return response()->json($nama);
        }
        return response()->json(['error'=>$validator->errors()]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tamu  $tamu
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tamu = Barang::find($id);
        return view('barang.show', compact('tamu'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tamu  $tamu
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $Tamu = Barang::find($id);
        return response()->json($Tamu);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tamu  $tamu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tamu $tamu)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tamu  $tamu
     * @return \Illuminate\Http\Response
     */
    public function hapus($id)
    {
        $Tamu = Barang::find($id);
        $Tamu->delete();
        return response()->json($Tamu);
    }
}
