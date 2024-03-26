<?php

namespace App\Models;

use App\Models\Job;
use App\Models\User;
use App\Models\Sales;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DesignQueue extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'design_queue';

    protected $fillable = [
        'judul',
        'sales_id',
        'job_id',
        'designer_id',
        'file_cetak',
        'ref_desain',
        'note',
        'prioritas',
        'status'
    ];

    public function sales()
    {
        return $this->belongsTo(Sales::class, 'sales_id');
    }

    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id');
    }

    public function designer()
    {
        return $this->belongsTo(User::class, 'designer_id');
    }

    public function simpanTambahDesain($request)
    {
        if($request->hasFile('ref_desain')){
            $file = $request->file('ref_desain');
            $nama_file = time()."_".$file->getClientOriginalName();
            $tujuan_upload = 'storage/ref-desain';
            $file->move($tujuan_upload,$nama_file);
        }

        $this->judul = $request->judul;
        $this->sales_id = $request->sales_id;
        $this->job_id = $request->job_id;
        $this->ref_desain = $nama_file;
        $this->note = $request->note;
        $this->prioritas = $request->prioritas == 'ON' ? 1 : 0;
        $this->status = 0;
        $this->save();
    }

    public function simpanEditDesain($request)
    {
        if($request->hasFile('ref_desain')){
            $file = $request->file('ref_desain');
            $nama_file = time()."_".$file->getClientOriginalName();
            $tujuan_upload = 'storage/ref-desain';
            $file->move($tujuan_upload,$nama_file);
        }

        $this->judul = $request->judul;
        $this->sales_id = $request->sales_id;
        $this->job_id = $request->job_id;
        $this->ref_desain = $nama_file;
        $this->note = $request->note;
        $this->prioritas = $request->prioritas == 'ON' ? 1 : 0;
        $this->status = 0;
        $this->save();
    }

    public function hapusRefDesain()
    {
        //hapus file ref desain dari storage yang sudah ada
        $file = $this->ref_desain;
        $tujuan_upload = 'storage/ref-desain';
        Storage::delete($file);
    }

    public function simpanDesainer($request)
    {
        $this->designer_id = $request->designer_id;
        $this->start_design = now();
        $this->status = 1;
        $this->save();
    }

    public function simpanFileProduksi($request)
    {
        if($request->hasFile('file_cetak')){
            $file = $request->file('file_cetak');
            $nama_file = time()."_".$file->getClientOriginalName();
            $tujuan_upload = 'storage/file-cetak';
            $file->move($tujuan_upload,$nama_file);
        }

        $this->file_cetak = $nama_file;
        $this->end_design = now();
        $this->status = 2;
        $this->save();
    }

    public function hapusAntrianDesain()
    {
        //hapus file ref desain dari storage yang sudah ada
        $file = $this->ref_desain;
        $tujuan_upload = 'storage/ref-desain';
        Storage::delete($file);

        //hapus file cetak dari storage yang sudah ada
        $file = $this->file_cetak;
        $tujuan_upload = 'storage/file-cetak';
        Storage::delete($file);

        $this->delete();
    }

    public function statusDesain($status)
    {
        switch ($status) {
            case 0:
                return '<span class="badge badge-secondary">Menunggu</span>';
                break;
            case 1:
                return '<span class="badge badge-primary">Dikerjakan</span>';
                break;
            case 2:
                return '<span class="badge badge-success">Selesai</span>';
                break;
            case 3:
                return '<span class="badge badge-danger">Dibatalkan</span>';
                break;
            default:
                return '<span class="badge badge-danger">Error</span>';
                break;
        }
    }
}
