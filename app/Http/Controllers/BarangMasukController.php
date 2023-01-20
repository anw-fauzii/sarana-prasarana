<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangMasuk;
use Illuminate\Http\Request;
use DataTables;
use Validator;

class BarangMasukController extends Controller
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
            $data = BarangMasuk::select('barang_masuk.*','barang.id','barang.kategori')->join('barang','barang.id','=','barang_masuk.barang_id')->where('kategori',1)->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip" title="Hapus" data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm delete"><i class="metismenu-icon pe-7s-trash"></i></a>';
                    return $btn;
                })
                ->addColumn('barang', function($data){
                    return $data->barang->nama." (".$data->barang->ukuran.")";
                })
                ->addColumn('tanggal', function($data){
                    return $data->created_at->isoFormat('D MMMM Y/hh:mm');
                })
                ->addColumn('harga', function($data){
                    return "Rp. ".number_format($data->harga);
                })
                ->rawColumns(['action'])
                ->make(true);
            }
        $barang = Barang::where('kategori',1)->get();
        return view('barang-masuk.index', compact('barang'));
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
            'barang_id' => 'required',
            'jumlah' => 'required',
            'harga' => 'required',
        ], $messages = [
            'barang_id.required' => 'Kolom Nama Barang Wajib Diisi',
            'jumlah.required' => 'Kolom Jumlah Wajib Diisi',
            'harga.required' => 'Kolom Harga Wajib Diisi',
        ]);
        if($validator->passes()) {
            $nama = BarangMasuk::updateOrCreate(
                ['id' => $request->id],
                [
                    'barang_id' => $request->barang_id,
                    'jumlah' => $request->jumlah,
                    'keterangan' => $request->keterangan,
                    'harga' => $request->harga,
                ]
            );
            $barang = Barang::findOrfail($request->barang_id);
            $barang->stok += $request->jumlah;
            $barang->save();
            
            return response()->json($nama);
        }
        return response()->json(['error'=>$validator->errors()]);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BarangMasuk  $barangMasuk
     * @return \Illuminate\Http\Response
     */
    public function show(BarangMasuk $barangMasuk)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BarangMasuk  $barangMasuk
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $Barang = BarangMasuk::find($id);
        return response()->json($Barang);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BarangMasuk  $barangMasuk
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BarangMasuk $barangMasuk)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BarangMasuk  $barangMasuk
     * @return \Illuminate\Http\Response
     */
    public function hapus($id)
    {
        $masuk = BarangMasuk::find($id);
        $masuk->delete();

            $barang = Barang::findOrfail($masuk->barang_id);
            $barang->stok -= $masuk->jumlah;
            $barang->save();
            
        return response()->json($masuk);
    }
}
