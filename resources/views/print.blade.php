<title>Bukti Fisik Cuti {{ $name }}</title>
<style>
    .center {
        margin-left: auto;
        margin-right: auto;
    }

    .left {
        margin-left: 50px;
    }

    table {
      font-family: arial, sans-serif;
      font-size: 10px;
      border-collapse: collapse;
      width: 85%;
      
    }
    
    td, th {
      border: 1px solid #dddddd;
      text-align: left;
      padding: 8px;
    }
    
    </style>

<div class="row">
  <div style="clear:both; position:relative;">
    <div style="position:absolute; left:37px; top:15px; width:200px;">
    <img src="{{ public_path('assets/img/group.svg') }}" width="35%" class="" alt="Logo-Intek" style=/>
    </div>
    <div style="margin-left:11px; text-align:center; font-family:sans-serif">
      <h2>PT. SOLUSI INTEK INDONESIA</h2>
        <p style="font-size: 11px">
          Head Office : Emerald Commercial Blok UB No.50 , Summarecon Bekasi , Telp 021-89454790<br>
          Mkt. Office	:	Jln Tebet Barat Dalam Raya No 31, Tebet Barat, Kecamatan Tebet, Jakarta Selatan 12810	
                    <br>Telp. 021-21383852
        </p>
    </div>
</div>

    <hr>
    <br/>

    <h3 style="text-align: center; font-family:sans-serif;"><span class="border border-dark">FORM PENGAJUAN CUTI</span></h3>
    
        <table class="center">
          <tr>
            <th>Nama</th>
              <td>{{ $name }}</td>
            <th>Hak Cuti Tahun</th>
              <td><?= date("Y") ?></td>    
          </tr>
          <tr>
            <th>Posisi</th>
              <td>{{ $position }}</td>
            <th>Cuti (Hari/Tgl)</th>
              <td>{{ $days_off_date }}</td> 
          </tr>
          <tr>
            <th>Departemen</th>
              <td>{{ $departement }}</td>
            <th>Masuk (Hari/Tgl)</th>
              <td>{{ $back_to_office }}</td> 
            
          </tr>
          <tr>
            <th>Atasan Langsung</th>
              <td>{{ $supervisor }}</td>
            <th>Total Cuti</th>
              <td>{{ $total_days }} Hari</td> 
          </tr>
          <tr>
            <th>PIC Pengganti</th>
              <td>{{ $replacement_pic }}</td>
            <th>Nomor yang dapat dihubungi</th>
              <td>{{ $phone_number }}</td> 
          </tr>
          <tr>
            <th>Alasan</th>
              <td colspan="3">{{ $reason }}</td>
          </tr>
          <tr>
            <th>Pekerjaan yang diserahkan</th>
              <td colspan="3">{{ $submitted_job }}</td>
          </tr>
        </table>
        
        <br>

        <h4 style="text-align: left; padding-left:52px; font-family:sans-serif;">Diisi Oleh Bagian HRD</h4>
        <table class="left" style="width: 60%">
            <tr>
              <th>Sisa Cuti</th>
                <td style="text-align: center">{{ $remaining_days_off+$total_days }} Hari</td>
            </tr>
            <tr>
              <th>Cuti Yang Diambil</th>
                <td style="text-align: center">{{ $total_days }} Hari</td>
            </tr>
            <tr>
              <th>Saldo Akhir Cuti</th>
                <td style="text-align: center">{{ $remaining_days_off }} Hari</td>
            </tr>
          </table>

          <br>
          <br>

            <p style="text-align: start; padding-left:55px; font-size:12px; font-family:sans-serif;">Jakarta, <?= date("d-m-Y")?></p>

            <table class="center">
              <tr>
                <th style="text-align: center;">Yang Mengajukan</th>
                <th style="text-align: center;">PIC Pengganti</th>
                <th style="text-align: center;">Menyetujui Atasan</th>
                <th colspan="2" style="text-align: center;">Mengetahui</th>
              </tr>
              <tr>
                <td rowspan="1" style="height: 80px;"></td>
                <td rowspan="1" style="height: 80px;"></td>
                <td rowspan="1" style="height: 80px;"></td>
                <td rowspan="1" style="height: 80px;"></td>
                <td rowspan="1" style="height: 80px;"></td>
              </tr>
              <tr>
                <td style="text-align: center;">{{ $name }}</td>
                <td style="text-align: center;">{{ $replacement_pic }}</td>
                <td style="text-align: center;">{{ $supervisor }}</td>
                <td style="text-align: center;">Bayu Nugraha <br><b>(General Manager)</b></td>
                <td style="text-align: center;">Eka Ayu Wulandari <br><b>(Human Resources)</b></td>
              </tr>
            </table>

</div>