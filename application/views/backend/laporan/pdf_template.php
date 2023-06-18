<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title><?= $title ?></title>
	<style>
		body {
			font-family: Arial, sans-serif;
		}

		h1 {
			color: #555;
		}

		/* .content {
			margin-top: 20px;
			padding: 10px;
			background-color: #f9f9f9;
		} */

		table {
			width: 100%;
			border-collapse: collapse;
			font-family: Arial, sans-serif;
			font-size: 9px;
		}

		th,
		td {
			padding: 8px;
			text-align: left;
			border-bottom: 1px solid #ddd;
		}

		th {
			background-color: #f2f2f2;
		}

		.table-header {
			font-weight: bold;
		}
	</style>
</head>

<body>
	<center>
		<h2><?= $title ?></h2>
		<h5><?= $sub_title ?></h5>
	</center>
	<h6>Rekap Laporan <?= $bulan . ' ' . $tahun ?></h6>
	<hr>
	<div class="content">
		<table class="table table-bordered w-100 text-nowrap" id="table_data">
			<thead>
				<tr>
					<th>No</th>
					<th>Nama</th>
					<th>Jabatan</th>
					<th>Bulan</th>
					<th>Status Gaji</th>
					<th>Gaji Pokok</th>
					<th>Bonus</th>
					<th>Uang Makan</th>
					<th>Uang Transport</th>
					<th>Pinjaman</th>
					<th>Tabungan</th>
					<th>Potongan Kemacetan</th>
					<th>Potongan Tidak Masuk</th>
					<th>Potongan Lainnya</th>
					<th>Total Gaji</th>
				</tr>
			</thead>
			<tbody>
				<?php if (!empty($laporan)) : ?>
					<?php
					$no = 1;
					$sum_total = 0;
					foreach ($laporan as $key => $value) :
					?>
						<tr>
							<td><?= $no ?></td>
							<td><?= $value['karyawan_nama'] ?></td>
							<td><?= $value['karyawan_status'] ?></td>
							<td><?= $value['gaji_bulan_ke'] . " " . $value['gaji_tahun_ke'] ?></td>
							<td><?= $value['gaji_status'] ?></td>
							<td><?= nominal($value['gaji_pokok']) ?></td>
							<td><?= $value['gaji_bonus'] ? nominal($value['gaji_bonus']) : nominal(0) ?></td>
							<td><?= $value['gaji_uang_makan'] ? nominal($value['gaji_uang_makan']) : nominal(0) ?></td>
							<td><?= $value['gaji_transport'] ? nominal($value['gaji_transport']) : nominal(0) ?></td>
							<td><?= $value['gaji_pinjaman_bayar'] ? nominal($value['gaji_pinjaman_bayar']) : nominal(0) ?></td>
							<td><?= ($value['gaji_tabungan_masuk'] ? '(+) ' . nominal($value['gaji_tabungan_masuk']) : $value['gaji_tabungan_keluar']) ? '(-) ' . nominal($value['gaji_tabungan_keluar']) : nominal(0) ?></td>
							<td><?= $value['gaji_potongan_kemacetan'] ? nominal($value['gaji_potongan_kemacetan']) : nominal(0) ?></td>
							<td><?= $value['gaji_potongan_tidak_masuk'] ? nominal($value['gaji_potongan_tidak_masuk']) : nominal(0) ?></td>
							<td><?= $value['gaji_potongan_total'] ? nominal($value['gaji_potongan_total']) : nominal(0) ?></td>
							<td><?= nominal($value['gaji_total']) ?></td>
						</tr>
					<?php
						$no++;
						$sum_total += $value['gaji_total'];
					endforeach;
					?>
				<?php else : ?>
					<tr>
						<td colspan="15">
							<center>Belum Terdapat Data Gaji</center>
						</td>
					</tr>
				<?php endif; ?>
			</tbody>
			<?php if (!empty($laporan)) : ?>
				<tfoot>
					<tr>
						<td colspan="14">
							<center><b>Total Pengeluaran Gaji </b></center>
						</td>
						<td><b><?= nominal($sum_total) ?></b></td>
					</tr>
				</tfoot>
			<?php endif; ?>
		</table>
	</div>
</body>

</html>
