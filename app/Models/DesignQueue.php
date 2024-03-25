<?php

namespace App\Models;

use App\Models\Job;
use App\Models\User;
use App\Models\Sales;
use Illuminate\Database\Eloquent\Model;
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
        $this->ref_desain = $request->ref_desain;
        $this->note = $request->note;
        $this->prioritas = $request->prioritas;
        $this->status = 0;
        $this->save();
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

}
