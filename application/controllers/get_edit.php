<?php defined('BASEPATH') OR exit('No direct script access allowed');

class get_edit extends CI_Controller{
    function __construct(){
        parent::__construct();
    }

    function pelaksanaan_pkp(){
        $id=$this->input->post('id');

        $pkp=$this->db->query("SELECT a.*,c.no_kode_propinsi,c.nama_propinsi,b.no_kabupaten,b.nm_kabupaten FROM tabel_penyelenggara_penyuluhan a 
        INNER JOIN tabel_kabupaten_kota b on b.id_urut_kabupaten=a.id_r_urut_kabupaten
        INNER JOIN tabel_propinsi c on c.no_kode_propinsi=b.no_kode_propinsi
        WHERE nomor_permohonan_penyuluhan='".$id."'
        Limit 1")->row();

        $daftar_narasumber=$this->db->query("SELECT a.id_urut_ambil_penyuluhan,b.kode_materi_penyuluhan,b.nama_materi_penyuluhan,c.kode_narasumber
        ,c.nama_narasumber FROM tabel_ambil_materi_penyuluhan a
        INNER JOIN tabel_materi_penyuluhan b on a.kode_r_materi_penyuluhan=b.kode_materi_penyuluhan
        INNER JOIN tabel_narasumber c on c.kode_narasumber=a.kode_r_narasumber
        WHERE a.nomor_r_permohonan_penyuluhan='".$id."'")->result_array();

        $peserta=$this->db->query("SELECT DISTINCT c.*,b.*,d.*,e.*,d.*,f.* FROM tabel_ambil_materi_penyuluhan a 
        INNER JOIN tabel_penyelenggara_penyuluhan b ON a.nomor_r_permohonan_penyuluhan = b.nomor_permohonan_penyuluhan 
        INNER JOIN tabel_ambil_penyuluhan c ON c.nomor_r_permohonan_penyuluhan = a.nomor_r_permohonan_penyuluhan
        INNER JOIN tabel_kabupaten_kota d ON b.id_r_urut_kabupaten= d.id_urut_kabupaten
        INNER JOIN tabel_propinsi e on d.no_kode_propinsi=e.no_kode_propinsi
        INNER JOIN tabel_pen_pengajuan_spp f on f.nomor_permohonan=c.nomor_r_permohonan
        WHere a.nomor_r_permohonan_penyuluhan = '".$id."'")
        ->result_array();
        
        $materi_tambahan=$this->db->query("SELECT * FROM tabel_materi_penyuluhan WHERE status_materi='pendukung'")
        ->result_array();

        $data=array(
            'id'=>$id,
            'pkp'=>$pkp,
            'daftar_narasumber'=>$daftar_narasumber,
            'materi_tambahan'=>$materi_tambahan,
            'peserta'=>$peserta
        );
        echo json_encode($data);
    }

    function pelaksanaan_irtp()
    {
        $provinsi=$this->input->get('provinsi');
        $kabupaten=$this->input->get('kabupaten');
        $provinsi=$provinsi!=null?$provinsi:"";
        $kabupaten=$kabupaten!=null?$kabupaten:"";
        if($provinsi!=""){ $q_provinsi = "and tabel_propinsi.no_kode_propinsi='$provinsi'"; } else { $q_provinsi = ""; }
        if($kabupaten!=""){ $q_kabupaten = "and tabel_kabupaten_kota.id_urut_kabupaten in ($kabupaten)"; } else { $q_kabupaten = ""; }
        
        $sql='SELECT * FROM tabel_pen_pengajuan_spp, tabel_daftar_perusahaan, tabel_kabupaten_kota, tabel_propinsi WHERE tabel_pen_pengajuan_spp.kode_r_perusahaan = tabel_daftar_perusahaan.kode_perusahaan and tabel_daftar_perusahaan.id_r_urut_kabupaten = tabel_kabupaten_kota.id_urut_kabupaten and tabel_kabupaten_kota.no_kode_propinsi = tabel_propinsi.no_kode_propinsi ';

        if($this->session->userdata('user_name')!='admin')
        {
            $sql.=$q_provinsi.''.$q_kabupaten.'';
        }

        $data=$this->db->query($sql)->result();

        echo json_encode($data);
    }

    function pemeriksaan_sarana()
    {
        $nomor_permohonan=$this->input->get('nomor_permohonan');

        $sql="SELECT a.*,b.*,c.*,d.*,e.* FROM tabel_pen_pengajuan_spp a
        INNER JOIN tabel_daftar_perusahaan b ON a.kode_r_perusahaan=b.kode_perusahaan
        INNER JOIN tabel_jenis_pangan c ON a.id_urut_jenis_pangan=c.id_urut_jenis_pangan
        INNER JOIN tabel_kemasan d ON a.kode_r_kemasan=d.kode_kemasan
        INNER JOIN tabel_kabupaten_kota e ON e.id_urut_kabupaten=b.id_r_urut_kabupaten
        INNER JOIN tabel_propinsi f ON f.no_kode_propinsi=e.no_kode_propinsi
        WHERE a.nomor_permohonan='".$nomor_permohonan."'";
        $permohonan=$this->db->query($sql)->result();
        $sql="SELECT b.* FROM tabel_pen_pengajuan_spp a
        INNER JOIN tabel_periksa_sarana_produksi b on a.nomor_permohonan=b.nomor_r_permohonan
        WHERE a.nomor_permohonan='".$nomor_permohonan."'
        ORDER BY b.tanggal_pemeriksaan DESC";
        $pemeriksaan=$this->db->query($sql)->result();
        $data['permohonan']=$permohonan;
        $data['pemeriksaan']['data']=$pemeriksaan;
        
        if(count($pemeriksaan)>0){
            $sql="select kode_narasumber, nip_pkp_dfi, nama_narasumber from tabel_narasumber where tot like 'DFI%'
            and nama_narasumber='".$pemeriksaan[0]->nama_pengawas."'
            order by nama_narasumber asc";
            $data['pemeriksaan']['ketua']=$this->db->query($sql)->result();
            $anggota=$pemeriksaan[0]->nama_anggota_pengawas;
            $anggotas=explode('|',$anggota);
            $i=0;
            do{
                $sql="select kode_narasumber, nip_pkp_dfi, nama_narasumber from tabel_narasumber where tot like 'DFI%'
                and nama_narasumber='".$anggotas[$i]."'
                order by nama_narasumber asc limit 1";
                $data['pemeriksaan']['anggota'][$i]=$this->db->query($sql)->result();
                $i++;
            }while($i<count($anggotas));

            $anggota=$pemeriksaan[0]->nama_observer_pengawas;
            $anggotas=explode('|',$anggota);
            $data['pemeriksaan']['observer']=$anggotas;
        }
        else
        {
            $data['pemeriksaan']['anggota']=[];
            $data['pemeriksaan']['observer']=[];
        }
        

        echo json_encode($data);
    }
}