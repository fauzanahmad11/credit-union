CREATE DATABASE dbkoperasi;

#1. tb_petugas
CREATE TABLE petugas(
idpetugas INT AUTO_INCREMENT PRIMARY KEY,
noktp CHAR(16) NOT NULL,
nama VARCHAR(50) NOT NULL,
jenkel ENUM('laki-laki','perempuan'),
alamat TEXT,
jabatan ENUM('pembina','ketua','sekretaris','bendahara','pengawas','pengelola'),
tgllahir DATE,
`status` ENUM('aktif','nonaktif'),
waktudaftar DATETIME);

#2. tb_login
CREATE TABLE login (
idpetugas INT NOT NULL,
username CHAR(16) NOT NULL,
`password` VARCHAR(260) NOT NULL);

#3. tb_anggota
CREATE TABLE anggota(
nokta CHAR(15) PRIMARY KEY,
nama VARCHAR(55) NOT NULL,
jenkel ENUM('laki-laki','perempuan'),
alamat TEXT NULL,
notelepon CHAR(13) NULL,
pekerjaan VARCHAR(30) NULL,
`status` ENUM('aktif','nonaktif'),
waktudaftar DATETIME);

#4. tb_sipokok
CREATE TABLE siwapo(
idsiwapo INT AUTO_INCREMENT PRIMARY KEY,
notransaksi CHAR(15) NOT NULL,
nokta CHAR(15) NOT NULL,
idharga INT(11) NOT NULL,
idpetugas INT NOT NULL,
keterangan VARCHAR(20) NOT NULL,
subtotal INT(15) NOT NULL,
total INT(15) NOT NULL,
`status` ENUM('aktif','nonaktif'),
waktudaftar DATETIME);

#6. tb_harga_siwa
CREATE TABLE masterharga(
idharga INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
idpetugas INT NOT NULL,
nama ENUM('simpanan pokok','simpanan wajib','simpanan sukarela','simpanan masadepan','simpanan anggota','pinjaman') NOT NULL,
MAX INT(10) NOT NULL,
MIN INT(10) NOT NULL,
waktudaftar DATETIME NULL,
waktuubah DATETIME NULL);

#7. tb_sisukarela
CREATE TABLE sisukarela(
idsisukarela INT AUTO_INCREMENT PRIMARY KEY,
notransaksi CHAR(15) NOT NULL,
nokta CHAR(15) NOT NULL,
idharga INT(11) NOT NULL,
idpetugas INT NOT NULL,
debit INT(12),
kredit INT(12),
saldo INT(12),
`status` ENUM('aktif','nonaktif'),
waktutransaksi DATETIME);

#8. tb_beli_simapan beserta realasinya
CREATE TABLE simapan(
idsimapan INT AUTO_INCREMENT PRIMARY KEY,
notransaksi CHAR(15) NOT NULL,
nokta CHAR(15) NOT NULL,
idharga INT(11) NOT NULL,
idpetugas INT NOT NULL,
nokartu CHAR(10) NOT NULL,
nilai INT(15),
`status` ENUM('aktif','nonaktif'),
waktutransaksi DATETIME);

#9. tb_jual_simapan
/*CREATE TABLE tb_jual_simapan(
id_simapan CHAR(10) PRIMARY KEY,
no_kta CHAR(15),
tgl_jual DATE,
qtt INT(2),
nilai INT(8));
*/

#10. tb_bunga_sianggota
CREATE TABLE masterbunga(
idbunga INT AUTO_INCREMENT PRIMARY KEY,
idpetugas INT NOT NULL,
namabunga ENUM('bunga pinjaman','bunga sianggota') NOT NULL,
keterangan VARCHAR(25) NULL,
total FLOAT(2,2) NOT NULL,
waktudaftar DATETIME NULL,
waktuubah DATETIME NULL););

#11. tb_sianggota
CREATE TABLE sianggota(
idsianggota INT AUTO_INCREMENT PRIMARY KEY,
notransaksi CHAR(15) NOT NULL,
nokta CHAR(15) NOT NULL,
idharga INT(11) NOT NULL,
idpetugas INT NOT NULL,
tgl_masuk DATE NOT NULL,
tgl_keluar DATE NOT NULL,
dana INT(15) NOT NULL,
bunga FLOAT(1,1) NOT NULL,
totalbunga INT(15) NOT NULL,
`status` ENUM('aktif','nonaktif'),
waktutransaksi DATETIME);

#12. tb_bunga_pinjaman
/*CREATE TABLE tb_bunga_pinjaman(
id_bunga INT AUTO_INCREMENT PRIMARY KEY,
keterangan VARCHAR(55) NOT NULL,
total DOUBLE);*/

#13. tb_pinjaman
CREATE TABLE pinjaman(
idpinjaman INT AUTO_INCREMENT PRIMARY KEY,
notransaksi CHAR(15) NOT NULL,
nokta CHAR(15) NOT NULL,
idharga INT(11) NOT NULL,
idpetugas INT NOT NULL,
totalpinjam INT(11) NOT NULL,
jangkawaktu VARCHAR(15) NOT NULL,
keterangan TEXT NULL,
t_pokok INT(11) NOT NULL,
t_bunga INT(11) NOT NULL,
jumlah_setor INT(11) NOT NULL,
tgl_mulai_a DATE NOT NULL,
tgl_selesai_a DATE NOT NULL,
`status` ENUM('aktif','nonaktif'),
waktutransaksi DATETIME);

#14. tb_transaksi_angsuran
CREATE TABLE angsuran(
idangsuran INT AUTO_INCREMENT PRIMARY KEY,
idpinjaman INT(11) NOT NULL,
notransaksi CHAR(15) NOT NULL,
idpetugas INT NOT NULL,
nokta CHAR(15) NOT NULL,
angsuranbunga INT(11) NULL,
totalbunga INT(11) NOT NULL,
angsuranpokok INT(11) NULL,
totalpokok INT(11) NOT NULL,
saldokredit INT(11) NOT NULL,
`status` ENUM('aktif','nonaktif'),
waktutransaksi DATETIME);

#15. tb_riwayat_a
CREATE TABLE riwayatanggota(
idriwayatanggota INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
nokta CHAR(15) NOT NULL,
nama VARCHAR(55) NOT NULL,
waktu DATETIME NOT NULL,
keterangan TEXT NULL);

#16. tb_riwayat_s_a
CREATE TABLE riwayattransaksi(
idriwayattransaksi INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
nokta CHAR(15) NOT NULL,
notransaksi CHAR(15) NOT NULL,
idpetugas INT(11) NOT NULL,
namatransaksi VARCHAR(30) NOT NULL,
total INT(11) NOT NULL,
waktu DATETIME NOT NULL,
keterangan TEXT NULL);

#relation timessss broooooooooooooooooooooo
#1. relasi tb_login
ALTER TABLE `login`
ADD CONSTRAINT
FK_petugas
FOREIGN KEY petugas(`idpetugas`)
REFERENCES petugas(`idpetugas`);

#2.1 relasi siwapo 
ALTER TABLE `siwapo`
ADD CONSTRAINT
FK_siwapo
FOREIGN KEY anggota(`nokta`)
REFERENCES anggota(`nokta`);

#2.2 relasi siwapo ke harga
ALTER TABLE `siwapo`
ADD CONSTRAINT
FK_hargasiwapo
FOREIGN KEY masterharga(`idharga`)
REFERENCES masterharga(`idharga`);

#2.3 relasi siwapo ke petugas
ALTER TABLE `siwapo`
ADD CONSTRAINT
FK_petugassiwapo
FOREIGN KEY petugas(`idpetugas`)
REFERENCES petugas(`idpetugas`);

#5.1 relasi tb_sisukarela 
ALTER TABLE `sisukarela`
ADD CONSTRAINT
FK_sisukarela
FOREIGN KEY anggota(`nokta`)
REFERENCES anggota(`nokta`);

#5.2 relasi tb_sisukarela 
ALTER TABLE `sisukarela`
ADD CONSTRAINT
FK_hargasisukarela
FOREIGN KEY masterharga(`idharga`)
REFERENCES masterharga(`idharga`);

#5.3 relasi tb_sisukarela ke petugas
ALTER TABLE `sisukarela`
ADD CONSTRAINT
FK_petugassisukarela
FOREIGN KEY petugas(`idpetugas`)
REFERENCES petugas(`idpetugas`);

#6.1 relasi tb_jual_simapan
ALTER TABLE `simapan`
ADD CONSTRAINT FK_simapan
FOREIGN KEY anggota(`nokta`)
REFERENCES anggota(`nokta`);

#6.2 relasi tb_jual_simapan
ALTER TABLE `simapan`
ADD CONSTRAINT
FK_hargasimapan
FOREIGN KEY masterharga(`idharga`)
REFERENCES masterharga(`idharga`);

#6.3 relasi tb_jual_simapan ke petugas
ALTER TABLE `simapan`
ADD CONSTRAINT
FK_petugassimapan
FOREIGN KEY petugas(`idpetugas`)
REFERENCES petugas(`idpetugas`);

#7.1 relasi bunga sianggota ke sianggota
ALTER TABLE `sianggota`
ADD CONSTRAINT FK_masterbungasianggota
FOREIGN KEY masterbunga(`idbunga`)
REFERENCES masterbunga(`idbunga`);

#7.2 relasi harga sianggota ke sianggota
ALTER TABLE `sianggota`
ADD CONSTRAINT
FK_hargasianggota
FOREIGN KEY masterharga(`idharga`)
REFERENCES masterharga(`idharga`);

#7.3 relasi harga sianggota ke petugas
ALTER TABLE `sianggota`
ADD CONSTRAINT
FK_petugassianggota
FOREIGN KEY petugas(`idpetugas`)
REFERENCES petugas(`idpetugas`);

#7.4. relasi sianggota ke anggota
ALTER TABLE `sianggota`
ADD CONSTRAINT FK_sianggota
FOREIGN KEY anggota(`nokta`)
REFERENCES anggota(`nokta`);

#9.1 relasi tb_pinjam ke anggota
ALTER TABLE `pinjaman`
ADD CONSTRAINT FK_pinjaman
FOREIGN KEY anggota(`nokta`)
REFERENCES anggota(`nokta`);

#9.2 relasi tb_pinjam ke harga
ALTER TABLE `pinjaman`
ADD CONSTRAINT
FK_hargapinjaman
FOREIGN KEY masterharga(`idharga`)
REFERENCES masterharga(`idharga`);

#9.2 relasi tb_pinjam ke petugas
ALTER TABLE `pinjaman`
ADD CONSTRAINT
FK_petugaspinjaman
FOREIGN KEY petugas(`idpetugas`)
REFERENCES petugas(`idpetugas`);

#10. relasi tb_pinjam ke bunga
ALTER TABLE `pinjaman`
ADD CONSTRAINT FK_masterbungapinjaman
FOREIGN KEY masterbunga(`idbunga`)
REFERENCES masterbunga(`idbunga`);

#11. relasi tb_transaksi_angsuran ke pinjaman
ALTER TABLE `angsuran`
ADD CONSTRAINT FK_angsuran
FOREIGN KEY pinjaman(`idpinjaman`)
REFERENCES pinjaman(`idpinjaman`);

#11.1. relasi tb_transaksi_angsuran ke anggota
ALTER TABLE `angsuran`
ADD CONSTRAINT FK_anggotaangsuran
FOREIGN KEY anggota(`nokta`)
REFERENCES anggota(`nokta`);


#11.2. relasi tb_transaksi_angsuran ke pinjaman
ALTER TABLE `angsuran`
ADD CONSTRAINT FK_petugasangsuran
FOREIGN KEY petugas(`idpetugas`)
REFERENCES petugas(`idpetugas`);

#12.riwayat anggota
ALTER TABLE `riwayatanggota`
ADD CONSTRAINT FK_riwayatanggota
FOREIGN KEY anggota(`nokta`) 
REFERENCES anggota(`nokta`)ON UPDATE CASCADE;

#12.riwayat transaksi
ALTER TABLE `riwayattransaksi`
ADD CONSTRAINT FK_riwayattransaksi
FOREIGN KEY anggota(`nokta`)
REFERENCES anggota(`nokta`) ON DELETE NO ACTION ON UPDATE CASCADE;

#12.1.riwayat transaksi ke petugas
ALTER TABLE `riwayattransaksi`
ADD CONSTRAINT FK_petugasriwayattransaksi
FOREIGN KEY petugas(`idpetugas`)
REFERENCES petugas(`idpetugas`);

#13 relasi bunga ke petugas
ALTER TABLE `masterbunga`
ADD CONSTRAINT FK_masterbunga
FOREIGN KEY petugas(`idpetugas`)
REFERENCES petugas(`idpetugas`);

# cara drop relasi
ALTER TABLE `angsuran`
DROP FOREIGN KEY FK_angsuran;

#========================TRIGGER===============================
#1. daftar anggota
DELIMITER $$
    CREATE TRIGGER `dbkoperasi`.`daftaranggota` AFTER INSERT
    ON `dbkoperasi`.`anggota`
    FOR EACH ROW BEGIN
	INSERT INTO `riwayatanggota` SET `idriwayatanggota`='', nokta=new.nokta, nama=new.nama, waktu=NOW(), keterangan='daftar anggota baru';
    END$$
DELIMITER ;

#2. pengunduran anggota
DELIMITER $$
    CREATE TRIGGER `dbkoperasi`.`keluaranggota` AFTER UPDATE
    ON `dbkoperasi`.`anggota`
    FOR EACH ROW BEGIN
    IF new.status = 'nonaktif' THEN
	INSERT INTO `riwayatanggota` SET `idriwayatanggota`='', nokta=new.nokta, nama=new.nama, waktu=NOW(), keterangan='keluar keanggotaan';
    END IF;
    END$$
DELIMITER ;
    
#3. transaksi siwapo
DELIMITER $$ 
    CREATE TRIGGER `dbkoperasi`.`transaksisiwapo` AFTER INSERT
    ON `dbkoperasi`.`siwapo`
    FOR EACH ROW BEGIN
    IF new.keterangan = 'debit siwajib' THEN
    INSERT INTO riwayattransaksi SET idriwayattransaksi='', nokta=new.nokta, notransaksi=new.notransaksi, idpetugas=new.idpetugas, 
    namatransaksi='siwajib', total=new.subtotal, waktu=NOW(), keterangan=new.keterangan;
    END IF;
    
    IF new.keterangan = 'debit sipokok' THEN
    INSERT INTO riwayattransaksi SET idriwayattransaksi='', nokta=new.nokta, notransaksi=new.notransaksi, idpetugas=new.idpetugas,
    namatransaksi='sipokok', total=new.subtotal, waktu=NOW(), keterangan=new.keterangan;
    END IF;
    
    IF new.keterangan = 'kredit siwajib' THEN
    INSERT INTO riwayattransaksi SET idriwayattransaksi='', nokta=new.nokta, notransaksi=new.notransaksi, idpetugas=new.idpetugas,
    namatransaksi='siwajib', total=new.subtotal, waktu=NOW(), keterangan=new.keterangan;
    END IF;
    
    IF new.keterangan = 'kredit sipokok' THEN
    INSERT INTO riwayattransaksi SET idriwayattransaksi='', nokta=new.nokta, notransaksi=new.notransaksi, idpetugas=new.idpetugas,
    namatransaksi='sipokok', total=new.subtotal, waktu=NOW(), keterangan=new.keterangan;
    END IF;
    END$$
DELIMITER ;

#4. transaksi sisukarela
DELIMITER $$ 
    CREATE TRIGGER `dbkoperasi`.`transaksisisukarela` AFTER INSERT
    ON `dbkoperasi`.`sisukarela`
    FOR EACH ROW BEGIN
    IF new.kredit = 0 THEN
    INSERT INTO riwayattransaksi SET idriwayattransaksi='', nokta=new.nokta, notransaksi=new.notransaksi, idpetugas=new.idpetugas,
    namatransaksi='sisukarela', total=new.debit, waktu=NOW(), keterangan='debit';
    ELSE 
    INSERT INTO riwayattransaksi SET idriwayattransaksi='', nokta=new.nokta, notransaksi=new.notransaksi, idpetugas=new.idpetugas,
    namatransaksi='sisukarela', total=new.kredit, waktu=NOW(), keterangan='kredit';
    END IF;
    END$$
DELIMITER ;

#5. transaksi sianggota
DELIMITER $$ 
    CREATE TRIGGER `dbkoperasi`.`transaksisianggota` AFTER INSERT
    ON `dbkoperasi`.`sianggota`
    FOR EACH ROW BEGIN
    INSERT INTO riwayattransaksi SET idriwayattransaksi='', nokta=new.nokta, notransaksi=new.notransaksi, idpetugas=new.idpetugas,
    namatransaksi='sianggota', total=new.dana + new.totalbunga, waktu=NOW(), keterangan='debit';
    END$$
DELIMITER ;

#6. transaksi pencairan sianggota
DELIMITER $$ 
    CREATE TRIGGER `dbkoperasi`.`transaksitariksianggota` AFTER INSERT
    ON `dbkoperasi`.`sianggota`
    FOR EACH ROW BEGIN
    IF new.status = 'nonaktif' THEN
    INSERT INTO riwayattransaksi SET idriwayattransaksi='', nokta=new.nokta, notransaksi=new.notransaksi, idpetugas=new.idpetugas,
    namatransaksi='pencairan sianggota', total=new.dana + new.totalbunga, waktu=NOW(), keterangan='kredit';
    END IF;
    END$$
DELIMITER ;

#7. transaksi beli simapan
DELIMITER $$ 
    CREATE TRIGGER `dbkoperasi`.`transaksibelisimapan` AFTER INSERT
    ON `dbkoperasi`.`simapan`
    FOR EACH ROW BEGIN
    INSERT INTO riwayattransaksi SET idriwayattransaksi='', nokta=new.nokta, notransaksi=new.notransaksi, idpetugas=new.idpetugas,
    namatransaksi='beli simapan', total=new.nilai, waktu=NOW(), keterangan='debit';
    END$$
DELIMITER ;

#8. transaksi jual simapan
DELIMITER $$ 
    CREATE TRIGGER `dbkoperasi`.`transaksijualsimapan` AFTER UPDATE
    ON `dbkoperasi`.`simapan`
    FOR EACH ROW BEGIN
    INSERT INTO riwayattransaksi SET idriwayattransaksi='', nokta=new.nokta, notransaksi=new.notransaksi, idpetugas=new.idpetugas,
    namatransaksi='jual simapan', total=new.nilai, waktu=NOW(), keterangan='debit';
    END$$
DELIMITER ;

#9. transaksi angsuran
DELIMITER $$ 
    CREATE TRIGGER `dbkoperasi`.`transaksiangsuran` AFTER UPDATE
    ON `dbkoperasi`.`angsuran`
    FOR EACH ROW BEGIN
    INSERT INTO riwayattransaksi SET idriwayattransaksi='', nokta=new.nokta, notransaksi=new.notransaksi, idpetugas=new.idpetugas,
    namatransaksi='angsuran pinjaman', total=new.totalbunga+new.totalpokok, waktu=NOW(), keterangan='kredit';
    END$$
DELIMITER ;

#10. transaksi pinjaman
DELIMITER $$ 
    CREATE TRIGGER `dbkoperasi`.`transakpinjaman` AFTER UPDATE
    ON `dbkoperasi`.`pinjaman`
    FOR EACH ROW BEGIN
    INSERT INTO riwayattransaksi SET idriwayattransaksi='', nokta=new.nokta, notransaksi=new.notransaksi, idpetugas=new.idpetugas,
    namatransaksi='pengajuan pinjaman', total=new.jumlah_setor, waktu=NOW(), keterangan='debit';
    END$$
DELIMITER ;

#11. count saldo angsuran
DELIMITER $$ 
    CREATE TRIGGER `dbkoperasi`.`angsuran` AFTER INSERT
    ON `dbkoperasi`.`angsuran`
    FOR EACH ROW BEGIN
    INSERT INTO riwayattransaksi SET idriwayattransaksi='', nokta=new.nokta, notransaksi=new.notransaksi, idpetugas=new.idpetugas,
    namatransaksi='angsuran pinjaman', total=new.totalbunga+new.totalpokok, waktu=NOW(), keterangan='kredit';
    END$$
DELIMITER ;
#=========================== create view ======================================================
# view transaksi
CREATE VIEW `dbkoperasi`.`view-transaksi`
AS
(SELECT riwayattransaksi.*, petugas.nama AS namapetugas, anggota.nama AS namaanggota, anggota.`alamat` FROM `riwayattransaksi` 
INNER JOIN petugas ON (petugas.idpetugas=riwayattransaksi.idpetugas)
INNER JOIN anggota ON (anggota.nokta=riwayattransaksi.nokta));

# view siwapo
CREATE VIEW `dbkoperasi`.`view-siwapo`
AS
(SELECT petugas.nama AS namapetugas, anggota.`nama`,siwapo.* FROM `siwapo` 
INNER JOIN petugas ON (petugas.idpetugas=siwapo.idpetugas) 
INNER JOIN anggota ON (anggota.nokta=siwapo.nokta));

# view sisukarela
CREATE VIEW `dbkoperasi`.`view-sisukarela`
AS
(SELECT petugas.nama AS namapetugas, anggota.`nama`,sisukarela.* FROM `sisukarela` 
INNER JOIN petugas ON (petugas.idpetugas=sisukarela.idpetugas) 
INNER JOIN anggota ON (anggota.nokta=sisukarela.nokta));

# view simapan
CREATE VIEW `dbkoperasi`.`view-simapan`
AS
(SELECT petugas.nama AS namapetugas, anggota.`nama`,simapan.* FROM `simapan` 
INNER JOIN petugas ON (petugas.idpetugas=simapan.idpetugas) 
INNER JOIN anggota ON (anggota.nokta=simapan.nokta));

# view sianggota
CREATE VIEW `dbkoperasi`.`view-sianggota`
AS
(SELECT petugas.nama AS namapetugas, anggota.`nama`,sianggota.* FROM `sianggota` 
INNER JOIN petugas ON (petugas.idpetugas=sianggota.idpetugas) 
INNER JOIN anggota ON (anggota.nokta=sianggota.nokta));




SELECT SUM(subtotal) AS SALDO,`view-siwapo`.* FROM `view-siwapo` GROUP BY nokta

QUERY  Fucek
